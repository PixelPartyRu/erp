<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use IlluminateDatabaseEloquentModel;
use Carbon\Carbon;
use App\Delivery;
use App\ChargeCommission;
use App\ChargeCommissionView;
use App\DailyChargeCommission;
use App\CommissionsRage;


class Recalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Recalculation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   

        $dateNow = new Carbon(date('Y-m-d'));
        $this->setDeliveries($dateNow); 
        $nds = 18;
        $deliveries = Delivery::where('state',false)->where('status','=','Профинансирована')->get();

        foreach ($deliveries as $delivery){
        	$dailyArray = [];
        	$relation = $delivery->relation;//связь
            $tariff = $relation->tariff;//тарифы
            $commission = $delivery->chargeCommission;

        	$fixed_charge_var = $tariff->commissions->where('type','document')->first();
            $percent_commission = $tariff->commissions->where('type','finance')->first();
            $udz_commission = $tariff->commissions->where('type','udz')->first();
            $penalty_commission = $tariff->commissions->where('type','peni')->first();

            $dateOfFunding = new Carbon($delivery->date_of_funding); 
          	$dateOfFundingDiff = $dateOfFunding->diffInDays($dateNow,false); 
          	$daysInYear = date("L", mktime(0,0,0, 7,7, $dateNow->year))?366:365; 

          	$dailyFixed = 0;
          	$dailyFixedNds = 0;
          	//фиксированный сбор
          	if ($dateOfFundingDiff == 1){
	      		if ($fixed_charge_var){
	                $fixed_charge_w_nds = $fixed_charge_var->commission_value;
	                $dailyFixed = $fixed_charge_w_nds;
	                $commission->fixed_charge = $dailyFixed;

	                if ($fixed_charge_var->nds == true){
	                    $fixed_charge_nds = ($fixed_charge_w_nds / 100.00) * $nds;
	                    $dailyFixedNds = $fixed_charge_nds;
	                    $commission->fixed_charge_nds = $dailyFixedNds;
	                }
	            } 
          	}

          	//actual deferment
          	$dailyPenalty = 0;
          	$dailyPenaltyNds = 0;
          	$dateOfRecourse = new Carbon($delivery->date_of_recourse);
          	$actualDeferment = $dateOfRecourse->diffInDays($dateNow,false);
          	if ($actualDeferment > 0){
          		if ($penalty_commission){
	                $dayOrYear = $penalty_commission->rate_stitching;
	                //Нахождение разницы
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

                        $penalty_w_nds = ($waybillOrFirstPayment / 100.00) * $penalty; 
	                    //без ндс
	                    $dailyPenalty = $penalty_w_nds;
	                    $commission->deferment_penalty += $dailyPenalty;
	                    //Ндс
	                    if ($penalty_commission->nds == true){
	                        $penalty_nds = ($penalty_w_nds / 100.00) * $nds;
	                        $dailyPenaltyNds = $penalty_nds;
	                        $commission->deferment_penalty_nds += $dailyPenaltyNds;
	                    }
                    }//диапазон
	            } 
          	}

          	$dailyPercent = 0;
            $dailyPercentNds = 0;
            $dailyUdz = 0;
            $dailyUdzNds = 0;

          	if ($dateOfFundingDiff > 0){//если дата финансирования меньше сегодняшней даты
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
	                	$dailyPercent = ($delivery->balance_owed / 100.00) * $percent;
	                }else{
	                	$dailyPercent = ($delivery->remainder_of_the_debt_first_payment / 100.00) * $percent;
	                }
	                $commission->percent += $dailyPercent;
	                //var_dump($delivery->remainder_of_the_debt_first_payment);
	                if ($percent_commission->nds == true){
	                    $dailyPercentNds = ($dailyPercent / 100.00) * $nds;
	                    $commission->percent_nds += $dailyPercentNds;
	                } 
	            }

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
	                $rage = $udz_commission->commissionsRages()->where('min', '<=', $dateOfFundingDiff)
						                				->where(function($query) use ($dateOfFundingDiff)
												            {
												                $query->where('max','>=', $dateOfFundingDiff)
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
	                    //без ндс
	                    $commission->udz += $dailyUdz;
	                    //Ндс
	                    if ($udz_commission->nds == true){
                    	 	$dailyUdzNds = ($dailyUdz / 100.00) * $nds;
	                        $commission->udz_nds += $dailyUdzNds;
	                       
	                    }			                    
	                }             
	            }else{
	               // var_dump('Коммиссии не найдено');
	            }
          	}
          	$daily_without_nds = $dailyFixed + $dailyPercent + $dailyUdz + $dailyPenalty;
	        $daily_nds = $dailyFixedNds + $dailyPercentNds + $dailyUdzNds + $dailyPenaltyNds;
	        $daily_with_nds = $daily_without_nds + $daily_nds;

	        if ($daily_with_nds > 0){
	        	$dailyArray = [
	            	'dailyFixed' => $dailyFixed,
	                'dailyFixedNds' => $dailyFixedNds,
	                'dailyPercent' => $dailyPercent,
	                'dailyPercentNds' => $dailyPercentNds,
	                'dailyUdz' => $dailyUdz,
	                'dailyUdzNds' => $dailyUdzNds,
	                'dailyDeferment' => $dailyPenalty,
	                'dailyDefermentNds' => $dailyPenaltyNds,
	                'dailyWithoutNds' => $daily_without_nds,
		            'dailyNds' => $daily_nds,
		            'dailyWithNds' => $daily_with_nds,
		            'dateNow' => $dateNow->format('Y-m-d')
	            ];
	            $this->createDailyCharge($dailyArray,$commission->id,$delivery->id);
	            $commission->without_nds += $daily_without_nds;
	            $commission->nds += $daily_nds;
	            $commission->with_nds += $daily_with_nds;
	            $commission->debt += $daily_with_nds;
	            $commission->save(); 
	        }
        }//foreach
    }

    public function setDeliveries($dateNow){
      $deliveries = Delivery::where('state',false)->get();
      foreach ($deliveries as $delivery){
          //Сегодняшнее число
          $dateOfRecourse = new Carbon($delivery->date_of_recourse);
          $actualDeferment = $dateOfRecourse->diffInDays($dateNow,false);//Фактическая просрочка
         
          $delivery->the_actual_deferment = $actualDeferment;
          $delivery->save();
          
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

    // public function addToCommission($commission,$dailyArray,$date){
    //     $commission->fixed_charge += $dailyArray['dailyFixed'];
    //     $commission->percent += $dailyArray['dailyPercent'];
    //     $commission->udz += $dailyArray['dailyUdz'];
    //     $commission->deferment_penalty += $dailyArray['dailyDeferment'];
    //     $commission->fixed_charge_nds += $dailyArray['dailyFixedNds'];
    //     $commission->percent_nds += $dailyArray['dailyPercentNds'];
    //     $commission->udz_nds += $dailyArray['dailyUdzNds'];
    //     $commission->deferment_penalty_nds += $dailyArray['dailyDefermentNds'];

    //     $without_nds = $commission->fixed_charge + $commission->percent + $commission->udz + $commission->deferment_penalty;
    //     $commission->without_nds = $without_nds;

    //     //НДС
    //     $nds_amount = $without_nds = $commission->fixed_charge_nds + $commission->percent_nds + $commission->udz_nds + $commission->deferment_penalty_nds;
    //     $commission->nds = $nds_amount;

    //     //с НДС
    //     $commission_sum = $without_nds + $nds_amount;
    //     $commission->with_nds = $commission_sum;

    //     $commission->debt = $commission_sum;
    //     $commission->charge_date = $date;

    //     $commission->save();
    // }
}
