<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use IlluminateDatabaseEloquentModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Artisan;

use Carbon\Carbon;
use App\ChargeCommission;
use App\DailyChargeCommission;
use App\ChargeCommissionView;
use App\CommissionsRage;
use App\Finance;
use App\Debtor;
use App\Client;
use App\Delivery;

class ChargeCommissionController extends Controller
{
    public function index()
    {		
    	//$commissions = ChargeCommissionView::where('debt','!=',0)->get();
    	//$commissions = ChargeCommission::all();
    	$chargeCommission = ChargeCommission::Where('waybill_status',false)->first();
    	if ($chargeCommission){
        	$nowDate = $chargeCommission->charge_date;		
    	}else{
    		$nowDate = null;
    	}

		$clients = Client::all();
		$debtors = Debtor::all();
    	return view('chargeCommission.index',['debtors'=>$debtors,'clients'=>$clients,'nowDate' =>$nowDate]);
    }

    public function store(){
    	$financeId = Input::get('finance');
    		
		$nds = 18;
		//$daysInYear = date('L')?366:365;
		$finance = Finance::find($financeId);
		$deliveyToFinances = $finance->deliveryToFinance;

		if (count($deliveyToFinances) != 0){
			foreach ($deliveyToFinances as $deliveyToFinance){
				$delivery = $deliveyToFinance->delivery;
    			$commission = new ChargeCommission;
	    		$commission->client = $delivery->client_id;
	    		$commission->debtor = $delivery->debtor_id;
	    		$commission->registry = $delivery->registry;
	    		$commission->waybill = $delivery->waybill;
	    		$commission->date_of_waybill = $delivery->date_of_waybill; 
	    		$commission->waybill_status = $delivery->state;
	    		$commission->date_of_funding = $delivery->date_of_funding;
	    		$commission->delivery_id = $delivery->id;

	    		//Дней со дня финансирования
	    		$dateNowVar = new Carbon(date('Y-m-d'));//Сегодняшнее число
	    		$dateNowVar->addDays(1);
	    		$dateOfFunding = new Carbon($delivery->date_of_funding);	
	    		$dateOfFundingDiff = $dateOfFunding->diffInDays($dateNowVar,false);

	    		//Фиксированный сбор
	    		$fixed_charge_w_nds = 0;
	    		$fixed_charge_nds = 0;
	    		$commission->fixed_charge = $fixed_charge_w_nds;//--------------------

	    		//Процент
	    		$percent_w_nds = 0;
	    		$percent_nds = 0;
	    		$commission->percent = $percent_w_nds;//----------------------------
	   //  		//udz
	    		$udz_w_nds = 0;
	    		$udz_nds = 0;
	    		$commission->udz = $udz_w_nds;//-------------------------

	    		//Пеня за просрочку
	    		$penalty_w_nds = 0;
	    		$penalty_nds = 0;
	    		$commission->deferment_penalty = $penalty_w_nds;//----------------------------------
		    	
	    		//без НДС
	    		$without_nds = $fixed_charge_w_nds + $percent_w_nds + $udz_w_nds + $penalty_w_nds;
	    		$commission->without_nds = $without_nds;

				//НДС
				$nds_amount = $fixed_charge_nds + $percent_nds + $udz_nds + $penalty_nds;
	    		$commission->nds = $nds_amount;

	    		//с НДС
	    		$commission_sum = $without_nds + $nds_amount;
	    		$commission->with_nds = $commission_sum;
				
				//Долг по коммиссии
				$commission->debt = $commission_sum;

	    		if ($commission->save()){
	    			$commissionView = new ChargeCommissionView;
	    			$this->createCommissionView($commission,$commissionView);
	    		}
			}
		}  	
    }

    public function recalculationTest(){
    	$dateTest = Input::get('dateTest');
    	//Artisan::call('Recalculation', ['dateTest' => Input::get('dateTest')]);
        $dateNowVar = new Carbon($dateTest);
        $this->setDeliveries($dateNowVar);

        $commissions = ChargeCommission::where('waybill_status',false)->get();
        $nds = 18;
        $dailyChargeCommission = DailyChargeCommission::all();

        if (count($dailyChargeCommission) > 0){
        	foreach ($dailyChargeCommission as $value) {
        		$value->delete();
        	}
        }

        foreach ($commissions as $commission){
        	$commission->percent = 0;
            $commission->percent_nds = 0;
            $commission->udz = 0;
            $commission->udz_nds = 0;
            $commission->deferment_penalty = 0;
            $commission->deferment_penalty_nds = 0;
            $commission->fixed_charge = 0;
            $commission->fixed_charge_nds = 0;
            $commission->charge_date = null;
            ///---------------------------------------------
            $delivery = $commission->delivery;
            $relation = $delivery->relation;//связь
            $tariff = $relation->tariff;//тарифы
            //Дней со дня финансирования
            $dateOfFunding = new Carbon($delivery->date_of_funding);    
            $dateOfFundingDiff = $dateOfFunding->diffInDays($dateNowVar,false);
            //Пеня за просрочку

           //Фиксированный сбор
            $fixed_charge_w_nds = 0;
            $fixed_charge_nds = 0;
            if ($dateOfFundingDiff > 0){
                $fixed_charge_var = $tariff->commissions->where('type','document')->first();
                if ($fixed_charge_var){
                    $fixed_charge_w_nds = $fixed_charge_var->commission_value;
                    $commission->fixed_charge = $fixed_charge_w_nds;

                    if ($fixed_charge_var->nds == true){
                        $fixed_charge_nds = ($fixed_charge_w_nds / 100.00) * $nds;
                        $commission->fixed_charge_nds = $fixed_charge_nds;
                    }
                }
            }else{
                $commission->fixed_charge = 0;
                $commission->fixed_charge_nds = 0;
            }
            $percent_w_nds = 0;
            $percent_nds = 0;
    		$udz_w_nds = 0;
            $udz_nds = 0;
            $penalty_w_nds = 0;
            $penalty_nds = 0;

            if ($dateOfFundingDiff > 0){//если сегодняшнее число меньше даты финансирования
            	$dateOfFundingClone = clone $dateOfFunding;
            	$percent_commission = $tariff->commissions->where('type','finance')->first();
            	$udz_commission = $tariff->commissions->where('type','udz')->first();
            	$penalty_commission = $tariff->commissions->where('type','peni')->first();

            	$dateOfRecourse = new Carbon($delivery->date_of_recourse);
            	$dateOfRecourseClone = clone $dateOfRecourse;
            	$dateRecourceFunding = $dateOfRecourse->diffInDays($dateOfFunding,false);
            	if ($dateRecourceFunding > 0){ 
            		for($i=0;$i<$dateRecourceFunding;$i++){ 
            			$dateNowVarFunding = $dateOfRecourseClone->addDays(1);
            			$daysInYear = date("L", mktime(0,0,0, 7,7, $dateNowVarFunding->year))?366:365; 
		  				$actualDeferment = $dateOfRecourse->diffInDays($dateNowVarFunding,false);//Фактическая просрочка

		  				if ($penalty_commission){
			                $dayOrYear = $penalty_commission->rate_stitching;
			                //Нахождение разницы
			                $penalty_commission_id = $penalty_commission->id;

			                if($actualDeferment > 0){
			                	$rage = CommissionsRage::Where('commission_id','=',$penalty_commission_id)
			        									->where(function($query) use ($actualDeferment)
												            {
												                $query->where('min', '<=', $actualDeferment)
												                      ->where('max', '>=', $actualDeferment);
												            })
											            ->orWhere(function($query) use ($actualDeferment)
												            {
												                $query->where('min', '<=', $actualDeferment)
												                      ->where('max', '=', 0);
												            })
			                                            ->first(); 
			                    if ($rage){
			                    	$handle = $penalty_commission->additional_sum;
			                    	//проверка накладной и финансирования
			                        if ($handle == true){
			                            $waybillOrFirstPayment = $delivery->balance_owed;
			                        }else{
			                            $waybillOrFirstPayment = $delivery->remainder_of_the_debt_first_payment;
			                        }

			                        if ($dayOrYear == true){
			                            $penalty = ($rage->value) / $daysInYear;
			                        }else{
			                            $penalty = $rage->value;
			                        }

			                        $penalty_w_nds = $commission->deferment_penalty + (($waybillOrFirstPayment / 100.00) * $penalty); 
				                    //без ндс
				                    $commission->deferment_penalty = $penalty_w_nds;
				                    //Ндс
				                    if ($penalty_commission->nds == true){
				                        $penalty_nds = ($penalty_w_nds / 100.00) * $nds;
				                        $commission->deferment_penalty_nds = $penalty_nds;
				                    }
			                    }//диапазон
			                }//просрочка меньше нуля 
			            } 
            		}
            		$commission->save();
            	}

            	for($i=0;$i<$dateOfFundingDiff;$i++){
            		$dateNowVar = $dateOfFundingClone->addDays(1);
            		$dateOfFundingDiffTest = $dateOfFunding->diffInDays($dateNowVar,false);
		            $daysInYear = date("L", mktime(0,0,0, 7,7, $dateNowVar->year))?366:365; 
		  			$actualDeferment = $dateOfRecourse->diffInDays($dateNowVar,false);//Фактическая просрочка
		  			if($i == 0){
		  				$dailyFixed = $fixed_charge_w_nds;
		                $dailyFixedNds = $fixed_charge_nds;
		  			}else{
		  				$dailyFixed = 0;
		                $dailyFixedNds = 0;
		  			}

	                $dailyPercent = 0;
	                $dailyPercentNds = 0;
	            	//Процент
		            if ($percent_commission){
		                //Годовые/дни
		                $handle = $percent_commission->additional_sum;

		                if ($percent_commission->rate_stitching == true){
		                    $percent = ($percent_commission->commission_value) / $daysInYear;
		                }else{
		                    $percent = $percent_commission->commission_value;
		                }
		                
		                //От финансирование либо накладной
		                if ($handle == true){
		                	$dailyPercent = (($delivery->balance_owed / 100.00) * $percent);
		                    $percent_w_nds = $commission->percent + $dailyPercent;
		                    $commission->percent = $percent_w_nds;
		                }else{
		                	$dailyPercent = (($delivery->remainder_of_the_debt_first_payment / 100.00) * $percent);
		                    $percent_w_nds = $commission->percent + $dailyPercent;
		                    $commission->percent = $percent_w_nds;
		                }
		                //var_dump($delivery->remainder_of_the_debt_first_payment);
		                if ($percent_commission->nds == true){
		                    $percent_nds = ($percent_w_nds / 100.00) * $nds;
		                    $commission->percent_nds = $percent_nds;
		                }
		                $dailyPercentNds = ($dailyPercent / 100.00) * $nds;
		            }

		            $dailyUdz = 0;
	                $dailyUdzNds = 0;
		            if ($udz_commission){
		                $dayOrYear = $udz_commission->rate_stitching;
		                //Нахождение разницы
		                $handle = $udz_commission->additional_sum;
		                //проверка накладной и финансирования
		                if ($handle == true){
		                    $waybillOrFirstPayment = $delivery->balance_owed;
		                }else{
		                    $waybillOrFirstPayment = $delivery->remainder_of_the_debt_first_payment;
		                }
		                $udz_commission_id = $udz_commission->id;
		                $rage = CommissionsRage::Where('commission_id','=',$udz_commission_id)
            									->where(function($query) use ($dateOfFundingDiffTest)
										            {
										                $query->where('min', '<=', $dateOfFundingDiffTest)
										                      ->where('max', '>=', $dateOfFundingDiffTest);
										            })
									            ->orWhere(function($query) use ($dateOfFundingDiffTest)
										            {
										                $query->where('min', '<=', $dateOfFundingDiffTest)
										                      ->where('max', '=', 0);
										            })
                                                ->first();                              
		                if($rage){
	                        if ($dayOrYear == true){
	                            $udz = ($rage->value) / $daysInYear;
	                        }else{
	                            $udz = $rage->value;
	                        }
	                        $dailyUdz = ($waybillOrFirstPayment / 100.00) * $udz;
	                        $udz_w_nds = $commission->udz + $dailyUdz; 
		                    //без ндс
		                    $commission->udz = $udz_w_nds;
		                    //Ндс
		                    if ($udz_commission->nds == true){
		                        $udz_nds = ($udz_w_nds / 100.00) * $nds;
		                        $commission->udz_nds = $udz_nds;
		                    }
		                    $dailyUdzNds = ($dailyUdz / 100.00) * $nds;
		                }             
		            }else{
		               // var_dump('Коммиссии не найдено');
		            }
		             //Пеня за просрочку
		            
	                $dailyDeferment = 0;
	                $dailyDefermentNds = 0;
		            if ($penalty_commission){
		                $dayOrYear = $penalty_commission->rate_stitching;
		                //Нахождение разницы
		                $penalty_commission_id = $penalty_commission->id;

		                if($actualDeferment > 0){
		                	$rage = CommissionsRage::Where('commission_id','=',$penalty_commission_id)
		        									->where(function($query) use ($actualDeferment)
											            {
											                $query->where('min', '<=', $actualDeferment)
											                      ->where('max', '>=', $actualDeferment);
											            })
										            ->orWhere(function($query) use ($actualDeferment)
											            {
											                $query->where('min', '<=', $actualDeferment)
											                      ->where('max', '=', 0);
											            })
		                                            ->first(); 
		                    if ($rage){
		                    	$handle = $penalty_commission->additional_sum;
		                    	//проверка накладной и финансирования
		                        if ($handle == true){
		                            $waybillOrFirstPayment = $delivery->balance_owed;
		                        }else{
		                            $waybillOrFirstPayment = $delivery->remainder_of_the_debt_first_payment;
		                        }

		                        if ($dayOrYear == true){
		                            $penalty = ($rage->value) / $daysInYear;
		                        }else{
		                            $penalty = $rage->value;
		                        }

		                        $dailyDeferment = ($waybillOrFirstPayment / 100.00) * $penalty;
		                        $penalty_w_nds = $commission->deferment_penalty + $dailyDeferment; 
		                        if($i == 0){
					            	$dailyDeferment = $penalty_w_nds;
					            }
			                    //без ндс
			                    $commission->deferment_penalty = $penalty_w_nds;
			                    //Ндс
			                    if ($penalty_commission->nds == true){
			                        $penalty_nds = ($penalty_w_nds / 100.00) * $nds;
			                        $commission->deferment_penalty_nds = $penalty_nds;
			                    }
			                    $dailyDefermentNds = ($dailyDeferment / 100.00) * $nds; 
		                    }//диапазон
		                }//просрочка меньше нуля   
		            } 

		            $daily_without_nds = $dailyFixed + $dailyPercent + $dailyUdz + $dailyDeferment;
		            $daily_nds = $dailyFixedNds + $dailyPercentNds + $dailyUdzNds + $dailyDefermentNds;
		            $daily_with_nds = $daily_without_nds + $daily_nds;

		            $dailyArray = [
		            	'dailyFixed' => $dailyFixed,
		                'dailyFixedNds' => $dailyFixedNds,
		                'dailyPercent' => $dailyPercent,
		                'dailyPercentNds' => $dailyPercentNds,
		                'dailyUdz' => $dailyUdz,
		                'dailyUdzNds' => $dailyUdzNds,
		                'dailyDeferment' => $dailyDeferment,
		                'dailyDefermentNds' => $dailyDefermentNds,
		                'dailyWithoutNds' => $daily_without_nds,
			            'dailyNds' => $daily_nds,
			            'dailyWithNds' => $daily_with_nds
		            ];
		            $this->createDailyCharge($dailyArray,$commission->id,$delivery->id,$dateNowVar);
		        }//цикл
            }else{
                $commission->percent = 0;
                $commission->percent_nds = 0;
                $commission->udz = 0;
                $commission->udz_nds = 0;
                $commission->deferment_penalty = 0;
                $commission->deferment_penalty_nds = 0;
            }
            //var_dump($dateOfFunding.'->'.$delivery->the_actual_deferment.'->'.$delivery->remainder_of_the_debt_first_payment.'->'.$commission->deferment_penalty);

            //без НДС
            $without_nds = $fixed_charge_w_nds + $percent_w_nds + $udz_w_nds + $penalty_w_nds;
            $commission->without_nds = $without_nds;

            //НДС
            $nds_amount = $fixed_charge_nds + $percent_nds + $udz_nds + $penalty_nds;
            $commission->nds = $nds_amount;

            //с НДС
            $commission_sum = $without_nds + $nds_amount;
            $commission->with_nds = $commission_sum;
            
            //Долг по коммиссии
            $commission->debt = $commission_sum;
            $commission->charge_date = $dateNowVar;
            if ($commission->save()){
                $commissionView = $commission->chargeCommissionView;
               	$this->createCommissionView($commission,$commissionView);
            }

        }
        
    	return Redirect::to('chargeCommission');

    }

    public function createDailyCharge($dailyArray,$commissionId,$deliveryId,$date){

    	$daily = new DailyChargeCommission;
    	$daily->delivery_id = $deliveryId;
		$daily->charge_commission_id = $commissionId;

		$daily->fixed_charge = $dailyArray['dailyFixed'];
		$daily->percent = $dailyArray['dailyPercent'];
		$daily->udz = $dailyArray['dailyUdz'];		
		$daily->deferment_penalty = $dailyArray['dailyDeferment'];

		$daily->nds = $dailyArray['dailyNds'];
		$daily->without_nds = $dailyArray['dailyWithoutNds'];
		$daily->with_nds = $dailyArray['dailyWithNds'];
		$daily->handler = false;

		$daily->fixed_charge_nds = $dailyArray['dailyFixedNds'];
		$daily->percent_nds = $dailyArray['dailyPercentNds'];
		$daily->udz_nds = $dailyArray['dailyUdzNds'];
		$daily->deferment_penalty_nds = $dailyArray['dailyDefermentNds'];
		$daily->created_at = $date;

		$daily->save();
    }

    public function setDeliveries($dateNowVar){
    	$deliveries = Delivery::where('state',false)->get();
        foreach ($deliveries as $delivery){
            //Сегодняшнее число
            $dateOfRecourse = new Carbon($delivery->date_of_recourse);
            $actualDeferment = $dateOfRecourse->diffInDays($dateNowVar,false);//Фактическая просрочка
           
            $delivery->the_actual_deferment = $actualDeferment;
            $delivery->save();
            
        }
    }

    public function createCommissionView($commission,$commissionView){
		$commissionView->client_id = $commission->client;
		$commissionView->debtor_id = $commission->debtor;
		$commissionView->registry = $commission->registry;
		$commissionView->waybill = $commission->waybill;
		$commissionView->date_of_waybill = $commission->date_of_waybill; 
		$commissionView->waybill_status = $commission->waybill_status;
		$commissionView->date_of_funding = $commission->date_of_funding;
		$commissionView->charge_commission_id = $commission->id;
		$commissionView->fixed_charge = $commission->fixed_charge;
		$commissionView->percent = $commission->percent;
		$commissionView->udz = $commission->udz;
		$commissionView->nds = $commission->nds;
		$commissionView->deferment_penalty = $commission->deferment_penalty;

		$commissionView->fixed_charge_nds = $commission->fixed_charge_nds;
		$commissionView->percent_nds = $commission->percent_nds;
		$commissionView->udz_nds = $commission->udz_nds;
		$commissionView->deferment_penalty_nds = $commission->deferment_penalty_nds;

		$commissionView->without_nds = $commission->without_nds;
		$commissionView->with_nds = $commission->with_nds;
		$commissionView->debt = $commission->debt;

		$commissionView->charge_date = $commission->charge_date;

		$commissionView->save();
    }

	public function getFilterData(){
		$client_id = Input::get('ClientId');
		$debtor_id = Input::get('DebtorId');
		$status = Input::get('Status');

		$ChargeCommission = ChargeCommissionView::query();
		if($status == 1){
			$ChargeCommission->where('waybill_status',true);
		}
		if($status == 2){
			$ChargeCommission->where('waybill_status',false)->where('debt','>',0);
		}
		if(!empty($client_id)){
			$ChargeCommission->where('client_id',$client_id);
		}
		if(!empty($debtor_id)){
			$ChargeCommission->where('debtor_id',$debtor_id);
		}

		$ChargeCommission = $ChargeCommission->get();
		$view = view('chargeCommission.tableRow', ['commissions' => $ChargeCommission])->render();
		$callback = 'success';
		return $view;
	}
}
