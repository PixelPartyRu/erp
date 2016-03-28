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
		$clients = Client::all();
		$debtors = Debtor::all();
    	return view('chargeCommission.index',['debtors'=>$debtors,'clients'=>$clients]);
    }

    public function store(){
    	$financeId = Input::get('finance');
    	
		$nds = 18;

		$dateNow = new Carbon(date('Y-m-d'));
		$finance = Finance::find($financeId);
		$deliveyToFinances = $finance->deliveryToFinance;

		if (count($deliveyToFinances) != 0){
			foreach ($deliveyToFinances as $deliveyToFinance){
				$allDailyArray = [];
				$delivery = $deliveyToFinance->delivery;
		        $relation = $delivery->relation;//связь
		        $tariff = $relation->tariff;//тарифы

    			$commission = new ChargeCommission;
	    		$commission->client = $delivery->client_id;
	    		$commission->debtor = $delivery->debtor_id;
	    		$commission->registry = $delivery->registry;
	    		$commission->waybill = $delivery->waybill;
	    		$commission->date_of_waybill = $delivery->date_of_waybill; 
	    		$commission->waybill_status = $delivery->state;
	    		$commission->date_of_funding = $delivery->date_of_funding;
	    		$commission->delivery_id = $delivery->id;

	    		$commission->percent = 0;
		        $commission->percent_nds = 0;
		        $commission->udz = 0;
		        $commission->udz_nds = 0;
		        $commission->deferment_penalty = 0;
		        $commission->deferment_penalty_nds = 0;
		        $commission->fixed_charge = 0;
		        $commission->fixed_charge_nds = 0;
		        ///---------------------------------------------

		  //       //Дней со дня финансирования
		        $dateOfFunding = new Carbon($delivery->date_of_funding);    
		        $dateOfFundingDiff = $dateOfFunding->diffInDays($dateNow,false);
		  //        //Фиксированный сбор
		        $fixed_charge_w_nds = 0;
		        $fixed_charge_nds = 0;
		        if ($dateOfFundingDiff > 0){
		            $fixed_charge_var = $tariff->commissions()->where('type','document')->first();
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
		        	$percent_commission = $tariff->commissions()->where('type','finance')->first();
		        	$udz_commission = $tariff->commissions()->where('type','udz')->first();
		        	$penalty_commission = $tariff->commissions()->where('type','peni')->first();

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

				                if($actualDeferment > 0){
				                        $rage = $penalty_commission->commissionsRages()->where('min', '<=', $actualDeferment)
						                				->where(function($query) use ($actualDeferment)
												            {
												                $query->where('max','>=', $actualDeferment)
												                      ->orWhere('max','=', 0);
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
		        	}

		        	for($i=0;$i<$dateOfFundingDiff;$i++){
		        		$dailyArray = [];
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
		      //       	//Процент
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
			                    $dailyPercentNds = ($dailyPercent / 100.00) * $nds;
			                } 
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
			                $rage = $udz_commission->commissionsRages()->where('min', '<=', $dateOfFundingDiffTest)
								                				->where(function($query) use ($dateOfFundingDiffTest)
														            {
														                $query->where('max','>=', $dateOfFundingDiffTest)
														                      ->orWhere('max','=', 0);
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
			                        $dailyUdzNds = ($dailyUdz / 100.00) * $nds;
			                    }			                    
			                }             
			            }else{
			               // var_dump('Коммиссии не найдено');
			            }
			     //         //Пеня за просрочку

		                $dailyDeferment = 0;
		                $dailyDefermentNds = 0;
			            if ($penalty_commission){
			                $dayOrYear = $penalty_commission->rate_stitching;
			                //Нахождение разницы
			                $penalty_commission_id = $penalty_commission->id;

			                if($actualDeferment > 0){
			                	$rage = $penalty_commission->commissionsRages()->where('min', '<=', $actualDeferment)
						                				->where(function($query) use ($actualDeferment)
												            {
												                $query->where('max','>=', $actualDeferment)
												                      ->orWhere('max','=', 0);
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
				                        $dailyDefermentNds = ($dailyDeferment / 100.00) * $nds; 
				                    }                    
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
				            'dailyWithNds' => $daily_with_nds,
				            'dateNow' => $dateNowVar->format('Y-m-d')
			            ];
			            array_push($allDailyArray,$dailyArray);
			            
			        }//цикл
		        }else{
		            $commission->percent = 0;
		            $commission->percent_nds = 0;
		            $commission->udz = 0;
		            $commission->udz_nds = 0;
		            $commission->deferment_penalty = 0;
		            $commission->deferment_penalty_nds = 0;
		        }
		  //       //без НДС
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
		        $commission->charge_date = $dateNow;
		        if ($commission->save()){
		        	foreach ($allDailyArray as $array){
		        		$this->createDailyCharge($array,$commission->id,$delivery->id);
		        	}
		        }
		        $delivery->stop_commission = false;
		        $delivery->save();
			}
		}  	
    }

    public function createDailyCharge($dailyArray,$commissionId,$deliveryId){

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
		$daily->created_at = $dailyArray['dateNow'];
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

	public function getFilterData(){
		$client_id = Input::get('ClientId');
		$debtor_id = Input::get('DebtorId');
		$status = Input::get('Status');

		$deliveries = Delivery::where('status','=','Профинансирована');
		if($status == 1){
			$deliveries->where('state',true);
		}
		if($status == 2){
			$deliveries->where('state',false);
		}
		$commissions = array();
		if(!empty($client_id)){
			$deliveries->where('client_id','=',$client_id);
		}
		if(!empty($debtor_id)){
			$deliveries->where('debtor_id','=',$debtor_id);
		}

		//$ChargeCommission->delivery()->orderBy('waybill');
		$deliveries = $deliveries->get();
		$view = view('chargeCommission.tableRow', ['deliveries' => $deliveries])->render();
		$callback = 'success';
		return $view;
	}
}
