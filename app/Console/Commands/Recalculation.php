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
        $commissions = ChargeCommission::where('waybill_status',false)->get();
        $nds = 18;

        foreach ($commissions as $commission){
            ///---------------------------------------------
            $stop = true;
            $delivery = $commission->delivery;
            $relation = $delivery->relation;//связь
            $tariff = $relation->tariff;//тарифы

            $fixed_charge_var = $tariff->commissions->where('type','document')->first();
            $percent_commission = $tariff->commissions->where('type','finance')->first();
            $udz_commission = $tariff->commissions->where('type','udz')->first();
            $penalty_commission = $tariff->commissions->where('type','peni')->first();
        
            if ($commission->debt == 0){
              $dailyArray = [
                'dailyFixed' => 0,
                'dailyFixedNds' => 0,
                'dailyPercent' => 0,
                'dailyPercentNds' => 0,
                'dailyUdz' => 0,
                'dailyUdzNds' => 0,
                'dailyDeferment' => 0,
                'dailyDefermentNds' => 0,
                'dailyWithoutNds' => 0,
                'dailyNds' => 0,
                'dailyWithNds' => 0
              ];           
              // Дней со дня финансирования 
              $dateOfFunding = new Carbon($delivery->date_of_funding); 
              $dateOfFundingDiff = $dateOfFunding->diffInDays($dateNow,false); 

              $dateOfFundingClone = clone $dateOfFunding;

              for($i=0;$i<$dateOfFundingDiff;$i++){
                if ($i == 0){ 
                  if ($fixed_charge_var){
                    $fixed_charge_w_nds = $fixed_charge_var->commission_value;
                    $commission->fixed_charge = $fixed_charge_w_nds;

                    if ($fixed_charge_var->nds == true){
                        $fixed_charge_nds = ($fixed_charge_w_nds / 100.00) * $nds;
                        $commission->fixed_charge_nds = $fixed_charge_nds;
                    }
                    $dailyFixed = $fixed_charge_w_nds;
                    $dailyFixedNds = $fixed_charge_nds;
                  } 
                }else{ 
                  $dailyFixed = 0;
                  $dailyFixedNds = 0;
                }
                $dateNowVar = $dateOfFundingClone->addDays(1);
                $daily = $this->dailyCommission($delivery,$dailyFixed,$dailyFixedNds,$percent_commission,$udz_commission,$penalty_commission,$nds,$dateNowVar);
                
                $dailyArray['dailyFixed'] += $daily['dailyFixed'];
                $dailyArray['dailyFixedNds'] += $daily['dailyFixedNds'];
                $dailyArray['dailyPercent'] += $daily['dailyPercent'];
                $dailyArray['dailyPercentNds'] += $daily['dailyPercentNds'];
                $dailyArray['dailyUdz'] += $daily['dailyUdz'];
                $dailyArray['dailyUdzNds'] += $daily['dailyUdzNds'];
                $dailyArray['dailyDeferment'] += $daily['dailyDeferment'];
                $dailyArray['dailyDefermentNds'] += $daily['dailyDefermentNds'];
                $dailyArray['dailyWithoutNds'] += $daily['dailyWithoutNds'];
                $dailyArray['dailyNds'] += $daily['dailyNds'];
                $dailyArray['dailyWithNds'] += $daily['dailyWithNds'];
              } 
            }else{
              $chargeDate = new Carbon ($commission->charge_date);
              $chargeDateDiff = $chargeDate->diffInDays($dateNow,false);
              if ($chargeDateDiff > 0){
                $dailyFixed = 0;
                $dailyFixedNds = 0;
                $dailyArray = $this->dailyCommission($delivery,$dailyFixed,$dailyFixedNds,$percent_commission,$udz_commission,$penalty_commission,$nds,$dateNow);
              }else{ 
                $stop = false;
              }
            }
            if ($stop){ 
              $this->createDailyCharge($dailyArray,$commission->id,$delivery->id,$dateNow);
              $this->addToCommission($commission,$dailyArray,$dateNow);
              $commissionView = $commission->chargeCommissionView;
              $this->addToCommission($commissionView,$dailyArray,$dateNow);
            }
        }
    }

    public function dailyCommission($delivery,$dailyFixed,$dailyFixedNds,$percent_commission,$udz_commission,$penalty_commission,$nds,$dateNow)
    {   
      $daysInYear = date("L", mktime(0,0,0, 7,7, $dateNow->year))?366:365;
      $dateOfFunding = new Carbon($delivery->date_of_funding); 
      $dateOfRecourse = new Carbon($delivery->date_of_recourse);
      $actualDeferment = $dateOfRecourse->diffInDays($dateNow,false);
      $dateOfFundingDiff = $dateOfFunding->diffInDays($dateNow,false);
      //Процент
      $dailyPercent = 0;
      $dailyPercentNds = 0;
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

          if ($percent_commission->nds == true){
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
          $rage = CommissionsRage::Where('commission_id','=',$udz_commission_id)
                    ->where(function($query) use ($dateOfFundingDiff)
                      {
                          $query->where('min', '<=', $dateOfFundingDiff)
                                ->where('max', '>=', $dateOfFundingDiff);
                      })
                    ->orWhere(function($query) use ($dateOfFundingDiff)
                      {
                          $query->where('min', '<=', $dateOfFundingDiff)
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
              //Ндс
              if ($udz_commission->nds == true){
                  $dailyUdzNds = ($dailyUdz / 100.00) * $nds;
              }
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
              //Ндс
              if ($penalty_commission->nds == true){
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
        'dailyWithNds' => $daily_with_nds
      ];
      return $dailyArray;
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

    public function addToCommission($commission,$dailyArray,$date){
        $commission->fixed_charge += $dailyArray['dailyFixed'];
        $commission->percent += $dailyArray['dailyPercent'];
        $commission->udz += $dailyArray['dailyUdz'];
        $commission->deferment_penalty += $dailyArray['dailyDeferment'];
        $commission->fixed_charge_nds += $dailyArray['dailyFixedNds'];
        $commission->percent_nds += $dailyArray['dailyPercentNds'];
        $commission->udz_nds += $dailyArray['dailyUdzNds'];
        $commission->deferment_penalty_nds += $dailyArray['dailyDefermentNds'];

        $without_nds = $commission->fixed_charge + $commission->percent + $commission->udz + $commission->deferment_penalty;
        $commission->without_nds = $without_nds;

        //НДС
        $nds_amount = $without_nds = $commission->fixed_charge_nds + $commission->percent_nds + $commission->udz_nds + $commission->deferment_penalty_nds;
        $commission->nds = $nds_amount;

        //с НДС
        $commission_sum = $without_nds + $nds_amount;
        $commission->with_nds = $commission_sum;

        $commission->debt = $commission_sum;
        $commission->charge_date = $date;

        $commission->save();
    }
}
