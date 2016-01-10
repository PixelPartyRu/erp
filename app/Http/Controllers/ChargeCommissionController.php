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
use App\CommissionsRage;
use App\Finance;

class ChargeCommissionController extends Controller
{
    public function index()
    {		
    	$commissions = ChargeCommission::where('without_nds','!=',0)->get();
    	return view('chargeCommission.index',['commissions' => $commissions]);
    }

    public function store(){
    	$financeArray = Input::get('financeArray');
    	foreach ($financeArray as $financeId){
    		
    		$nds = 18;
    		$daysInYear = date('L')?366:365;
    		$finance = Finance::find($financeId);
    		$deliveries = $finance->deliveries;
    		if (count($deliveries) != 0){
    			foreach ($deliveries as $delivery){
	    			$commission = new ChargeCommission;
	    			$relation = $delivery->relation;//связь
	    			$tariff = $relation->tariff;//тарифы
		    		$commission->client = $delivery->client_id;
		    		$commission->debtor = $delivery->debtor_id;
		    		$commission->registry = $delivery->registry;
		    		$commission->waybill = $delivery->waybill;
		    		$commission->date_of_waybill = $delivery->date_of_waybill; 
		    		$commission->waybill_status = $delivery->state;
		    		$commission->date_of_funding = $delivery->date_of_funding;
		    		$commission->delivery_id = $delivery->id;

		    		//Дней со дня финансирования
		    		$dateNowVar = new Carbon(Carbon::now());//Сегодняшнее число
		    		$dateNowVar->addDays(1);
		    		$dateOfFunding = new Carbon($delivery->date_of_funding);	
		    		$dateOfFundingDiff = $dateOfFunding->diffInDays($dateNowVar,false);

		    		//Фиксированный сбор
		    		$fixed_charge_w_nds = 0;
		    		$fixed_charge_nds = 0;
		    		$fixed_charge_var = $relation->tariff->commissions->where('name','Плата за обработку одного документа')->first();
		    		if ($fixed_charge_var){
		    			$fixed_charge_w_nds = $fixed_charge_var->commission_value;
						$commission->fixed_charge = $fixed_charge_w_nds;

						if ($fixed_charge_var->nds == true){
							$fixed_charge_nds = ($fixed_charge_w_nds / 100.00) * $nds;
						}
		    		}
		    		//Процент
		    		$percent_w_nds = 0;
		    		$percent_nds = 0;
		    		if ($dateOfFundingDiff > 0){//если сегодняшнее число больше даты финансирования
			    		$percent_commission = $relation->tariff->commissions->where('name','Вознаграждение за пользование денежными средствами')->first();
			    		if ($percent_commission){
			    			//Годовые/дни
			    			if ($percent_commission->rate_stitching == true){
			    				$percent = $percent_commission->commission_value;
			    			}else{
			    				$percent = ($percent_commission->commission_value) / $daysInYear;
			    			}
			    			$handle = $percent_commission->additional_sum;
			    			//От финансирование либо накладной
			    			if ($handle == true){
			    				$percent_w_nds = (($delivery->waybill_amount / 100.00) * $percent) * $dateOfFundingDiff;
			    				$commission->percent = $percent_w_nds;
			    			}else{
			    				$percent_w_nds = (($delivery->remainder_of_the_debt_first_payment / 100.00) * $percent) * $dateOfFundingDiff;
			    				$commission->percent = $percent_w_nds;
			    			}

			    			if ($percent_commission->nds == true){
								$percent_nds = ($percent_w_nds / 100.00) * $nds;
							}
			    		}
			    	}
		   //  		//true- waybill, false-first payment
		   //  		//udz
		    		$udz_w_nds = 0;
		    		$udz_nds = 0;
		    		if ($dateOfFundingDiff > 0){//если сегодняшнее число больше даты финансирования
			    		$udz_commission = $relation->tariff->commissions->where('name','Вознаграждение за УДЗ')->first();
			    		if ($udz_commission){
			    			//Нахождение разницы
			    			$udz_commission_id = $udz_commission->id;
			    			$commissionsRages = CommissionsRage::Where('commission_id','=',$udz_commission_id)
			    												->where('min','<=',$dateOfFundingDiff)
																 ->where('max','>=',$dateOfFundingDiff)
																 ->first();
			    			if($commissionsRages){
			    				//Годовые/дни
			    				if ($udz_commission->rate_stitching == true){
				    				$udz = $commissionsRages->value;
				    			}else{
				    				$udz = ($commissionsRages->value) / $daysInYear;
				    			}

				    			$handle = $percent_commission->additional_sum;
				    			//проверка накладной и финансирования
				    			if ($handle == true){
				    				//without range
				    				$udz_w_nds = (($delivery->waybill_amount / 100.00) * $udz) * $dateOfFundingDiff;
				    				$commission->udz = $udz_w_nds;
				    			}else{
				    				$udz_w_nds = (($delivery->remainder_of_the_debt_first_payment / 100.00) * $udz) * $dateOfFundingDiff;
				    				$commission->udz = $udz_w_nds;
				    			}

				    			if ($udz_commission->nds == true){
									$udz_nds = ($udz_w_nds / 100.00) * $nds;
								}
			    			}else{
			    				var_dump('Интервала не найдено');
			    			}	
			    		}else{
			    			var_dump('Коммиссии не найдено');
			    		}
			    	}

		   //  		//Пеня за просрочку
		    		$penalty_w_nds = 0;
		    		$penalty_nds = 0;

		    		if ($dateOfFundingDiff > 0){//если сегодняшнее число больше даты финансирования
			    		$penalty_commission = $relation->tariff->commissions->where('name','Пеня за просрочку')->first();
			    		if ($penalty_commission){
			    			//Нахождение разницы
			    			$penalty_commission_id = $penalty_commission->id;
			    			$commissionsRages = CommissionsRage::Where('commission_id','=',$penalty_commission_id)
			    												->where('min','<=',$dateOfFundingDiff)
																 ->where('max','>=',$dateOfFundingDiff)
																 ->first();
							if ($commissionsRages){
				    			//Годовые/дни
				    			if ($penalty_commission->rate_stitching == true){
				    				$penalty = $commissionsRages->value;
				    			}else{
				    				$penalty = ($commissionsRages->value) / $daysInYear;
				    			}
				    			$deferment = $delivery->the_actual_deferment;
				    			if ($deferment > 0){
				    				//без интервалов
				    				$penalty_w_nds = (($delivery->remainder_of_the_debt_first_payment / 100.00) * $penalty) * $deferment;
				    				$commission->deferment_penalty = $penalty_w_nds;

				    				if ($penalty_commission->nds == true){
										$penalty_nds = ($penalty_w_nds / 100.00) * $nds;
									}
				    			}
							}else{
								var_dump('no range');
							}
			    		}
			    	}
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
		    		$commission->save();
				}
    		}
    	}
    	//return $commission;
    }

    public function recalculationTest(){

    	Artisan::call('Recalculation', ['dateTest' => Input::get('dateTest')]);
    	return Redirect::to('chargeCommission');

    }
}
