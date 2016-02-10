<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use IlluminateDatabaseEloquentModel;
use Carbon\Carbon;
use App\Delivery;
use App\Agreement;
use App\ChargeCommission;
use App\ChargeCommissionView;
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
        $agreement = Agreement::first();
        $agreement->description = 'Misha rabotaet';
        $agreement->save();
       //  if ( $this->argument('dateTest')){
       //      $dateTest = $this->argument('dateTest');
       //      $dateNowVar = new Carbon($dateTest);
       //  }else{
       //      $dateNowVar = new Carbon(date('Y-m-d'));
       //  }
       //  $this->setDeliveries($dateNowVar);

       //  $commissions = ChargeCommission::where('waybill_status',false)->get();
       //  $nds = 18;
       //  foreach ($commissions as $commission){
       //  	$commission->percent = 0;
       //      $commission->percent_nds = 0;
       //      $commission->udz = 0;
       //      $commission->udz_nds = 0;
       //      $commission->deferment_penalty = 0;
       //      $commission->deferment_penalty_nds = 0;
       //      $commission->fixed_charge = 0;
       //      $commission->fixed_charge_nds = 0;
       //      ///---------------------------------------------
       //      $delivery = $commission->delivery;
       //      $relation = $delivery->relation;//связь
       //      $tariff = $relation->tariff;//тарифы
       //      //Дней со дня финансирования
       //      $dateOfFunding = new Carbon($delivery->date_of_funding);    
       //      $dateOfFundingDiff = $dateOfFunding->diffInDays($dateNowVar,false);
       //      //Пеня за просрочку

       //     //Фиксированный сбор
       //      $fixed_charge_w_nds = 0;
       //      $fixed_charge_nds = 0;
       //      if ($dateOfFundingDiff > 0){
       //          $fixed_charge_var = $tariff->commissions->where('type','document')->first();
       //          if ($fixed_charge_var){
       //              $fixed_charge_w_nds = $fixed_charge_var->commission_value;
       //              $commission->fixed_charge = $fixed_charge_w_nds;

       //              if ($fixed_charge_var->nds == true){
       //                  $fixed_charge_nds = ($fixed_charge_w_nds / 100.00) * $nds;
       //                  $commission->fixed_charge_nds = $fixed_charge_nds;
       //              }
       //          }
       //      }else{
       //          $commission->fixed_charge = 0;
       //          $commission->fixed_charge_nds = 0;
       //      }

       //      if ($dateOfFundingDiff > 0){//если сегодняшнее число меньше даты финансирования
       //      	$dateOfFundingClone = clone $dateOfFunding;
       //      	$percent_commission = $tariff->commissions->where('type','finance')->first();
       //      	$udz_commission = $tariff->commissions->where('type','udz')->first();
       //      	$penalty_commission = $tariff->commissions->where('type','peni')->first();
       //      	$dateOfRecourse = new Carbon($delivery->date_of_recourse);
           
       //      	for($i=0;$i<$dateOfFundingDiff;$i++){
       //      		$dateNowVar = $dateOfFundingClone->addDays(1);
       //      		$dateOfFundingDiffTest = $dateOfFunding->diffInDays($dateNowVar,false);
		     //        $daysInYear = date("L", mktime(0,0,0, 7,7, $dateNowVar->year))?366:365; 
		  			// $actualDeferment = $dateOfRecourse->diffInDays($dateNowVar,false);//Фактическая просрочка

	      //       	//Процент
		     //        $percent_w_nds = 0;
		     //        $percent_nds = 0;
		            
		     //        if ($percent_commission){
		     //            //Годовые/дни
		     //            $handle = $percent_commission->additional_sum;

		     //            if ($percent_commission->rate_stitching == true){
		     //                $percent = ($percent_commission->commission_value) / $daysInYear;
		     //            }else{
		     //                $percent = $percent_commission->commission_value;
		     //            }
		                
		     //            //От финансирование либо накладной
		     //            if ($handle == true){
		     //                $percent_w_nds = $commission->percent + (($delivery->balance_owed / 100.00) * $percent);
		     //                $commission->percent = $percent_w_nds;
		     //            }else{
		     //                $percent_w_nds = $commission->percent + (($delivery->remainder_of_the_debt_first_payment / 100.00) * $percent);
		     //                $commission->percent = $percent_w_nds;
		     //            }
		     //            //var_dump($delivery->remainder_of_the_debt_first_payment);
		     //            if ($percent_commission->nds == true){
		     //                $percent_nds = ($percent_w_nds / 100.00) * $nds;
		     //                $commission->percent_nds = $percent_nds;
		     //            }
		     //        }

		     //        $udz_w_nds = 0;
		     //        $udz_nds = 0;
		            
		     //        if ($udz_commission){
		     //            $dayOrYear = $udz_commission->rate_stitching;
		     //            //Нахождение разницы
		     //            $handle = $udz_commission->additional_sum;
		     //            //проверка накладной и финансирования
		     //            if ($handle == true){
		     //                $waybillOrFirstPayment = $delivery->balance_owed;
		     //            }else{
		     //                $waybillOrFirstPayment = $delivery->remainder_of_the_debt_first_payment;
		     //            }
		     //            $udz_commission_id = $udz_commission->id;
		     //            $rage = CommissionsRage::Where('commission_id','=',$udz_commission_id)
       //      									->where(function($query) use ($dateOfFundingDiffTest)
							// 			            {
							// 			                $query->where('min', '<=', $dateOfFundingDiffTest)
							// 			                      ->where('max', '>=', $dateOfFundingDiffTest);
							// 			            })
							// 		            ->orWhere(function($query) use ($dateOfFundingDiffTest)
							// 			            {
							// 			                $query->where('min', '<=', $dateOfFundingDiffTest)
							// 			                      ->where('max', '=', 0);
							// 			            })
       //                                          ->first();                              
		     //            if($rage){
	      //                   if ($dayOrYear == true){
	      //                       $udz = ($rage->value) / $daysInYear;
	      //                   }else{
	      //                       $udz = $rage->value;
	      //                   }
	      //                   $udz_w_nds = $commission->udz + (($waybillOrFirstPayment / 100.00) * $udz); 
		     //                //без ндс
		     //                $commission->udz = $udz_w_nds;
		     //                //Ндс
		     //                if ($udz_commission->nds == true){
		     //                    $udz_nds = ($udz_w_nds / 100.00) * $nds;
		     //                    $commission->udz_nds = $udz_nds;
		     //                }
		     //            }
		     //        }else{
		     //           // var_dump('Коммиссии не найдено');
		     //        }

		     //         //Пеня за просрочку
		     //        $penalty_w_nds = 0;
		     //        $penalty_nds = 0;
		            
		     //        if ($penalty_commission){
		     //            $dayOrYear = $penalty_commission->rate_stitching;

		     //            //Нахождение разницы
		     //            $penalty_commission_id = $penalty_commission->id;

		     //            if($actualDeferment > 0){
		     //            	$rage = CommissionsRage::Where('commission_id','=',$penalty_commission_id)
		     //    									->where(function($query) use ($actualDeferment)
							// 				            {
							// 				                $query->where('min', '<=', $actualDeferment)
							// 				                      ->where('max', '>=', $actualDeferment);
							// 				            })
							// 			            ->orWhere(function($query) use ($actualDeferment)
							// 				            {
							// 				                $query->where('min', '<=', $actualDeferment)
							// 				                      ->where('max', '=', 0);
							// 				            })
		     //                                        ->first(); 
		     //                if ($rage){
		     //                	$handle = $penalty_commission->additional_sum;
		     //                	//проверка накладной и финансирования
		     //                    if ($handle == true){
		     //                        $waybillOrFirstPayment = $delivery->balance_owed;
		     //                    }else{
		     //                        $waybillOrFirstPayment = $delivery->remainder_of_the_debt_first_payment;
		     //                    }

		     //                    if ($dayOrYear == true){
		     //                        $penalty = ($rage->value) / $daysInYear;
		     //                    }else{
		     //                        $penalty = $rage->value;
		     //                    }

		     //                    $penalty_w_nds = $commission->deferment_penalty + (($waybillOrFirstPayment / 100.00) * $penalty); 
			    //                 //без ндс
			    //                 $commission->deferment_penalty = $penalty_w_nds;
			    //                 //Ндс
			    //                 if ($penalty_commission->nds == true){
			    //                     $penalty_nds = ($penalty_w_nds / 100.00) * $nds;
			    //                     $commission->deferment_penalty_nds = $penalty_nds;
			    //                 }
		     //                }//диапазон
		     //            }//просрочка меньше нуля  
		     //        }
		     //    }//цикл
       //      }else{
       //          $commission->percent = 0;
       //          $commission->percent_nds = 0;
       //          $commission->udz = 0;
       //          $commission->udz_nds = 0;
       //          $commission->deferment_penalty = 0;
       //          $commission->deferment_penalty_nds = 0;
       //      }
       //      //var_dump($dateOfFunding.'->'.$delivery->the_actual_deferment.'->'.$delivery->remainder_of_the_debt_first_payment.'->'.$commission->deferment_penalty);

       //      //без НДС
       //      $without_nds = $fixed_charge_w_nds + $percent_w_nds + $udz_w_nds + $penalty_w_nds;
       //      $commission->without_nds = $without_nds;

       //      //НДС
       //      $nds_amount = $fixed_charge_nds + $percent_nds + $udz_nds + $penalty_nds;
       //      $commission->nds = $nds_amount;

       //      //с НДС
       //      $commission_sum = $without_nds + $nds_amount;
       //      $commission->with_nds = $commission_sum;
            
       //      //Долг по коммиссии
       //      $commission->debt = $commission_sum;
       //      if ($commission->save()){
       //          $commissionView = $commission->chargeCommissionView;
       //          $commissionView->client = $commission->client;
       //          $commissionView->debtor = $commission->debtor;
       //          $commissionView->registry = $commission->registry;
       //          $commissionView->waybill = $commission->waybill;
       //          $commissionView->date_of_waybill = $commission->date_of_waybill; 
       //          $commissionView->waybill_status = $commission->waybill_status;
       //          $commissionView->date_of_funding = $commission->date_of_funding;
       //          $commissionView->charge_commission_id = $commission->id;
       //          $commissionView->fixed_charge = $commission->fixed_charge;
       //          $commissionView->percent = $commission->percent;
       //          $commissionView->udz = $commission->udz;
       //          $commissionView->deferment_penalty = $commission->deferment_penalty;
       //          $commissionView->without_nds = $commission->without_nds;
       //          $commissionView->with_nds = $commission->with_nds;
       //          $commissionView->nds = $commission->nds;
       //          $commissionView->debt = $commission->debt;

       //          $commissionView->save();
       //      }

       //  }

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
}
