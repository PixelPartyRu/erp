<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use IlluminateDatabaseEloquentModel;
use Carbon\Carbon;
use App\Delivery;
use App\ChargeCommission;
use App\CommissionsRage;


class Recalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Recalculation{dateTest?}';

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

        if ( $this->argument('dateTest')){
            $dateTest = $this->argument('dateTest');
            $dateNowVar = new Carbon($dateTest);
            //$dateNowVar->addDays(1);
        }else{
            $dateNowVar = new Carbon(Carbon::now());
        }
        $deliveries = Delivery::where('state',false)->get();
        foreach ($deliveries as $delivery){
            //Сегодняшнее число
            $dateOfRecourse = new Carbon($delivery->date_of_recourse);
            $dateOfRecourse->addDays(1);//для включения в осрочку
            $actualDeferment = $dateOfRecourse->diffInDays($dateNowVar,false);//Фактическая просрочка

            $delivery->the_actual_deferment = $actualDeferment;

            $delivery->save();
            
        }
        $commissions = ChargeCommission::where('waybill_status',false)->get();
        $daysInYear = date('L')?366:365;
        $nds = 18;
        foreach ($commissions as $commission){

            $delivery = $commission->delivery;
            $relation = $delivery->relation;//связь
            $tariff = $relation->tariff;//тарифы
            //Дней со дня финансирования
            $dateOfFunding = new Carbon($delivery->date_of_funding); 
            $dateOfWaybill = new Carbon($delivery->date_of_waybill);      
            $dateOfFundingDiff = $dateOfFunding->diffInDays($dateNowVar,false);
            $dateOfWaybillDiff = $dateOfWaybill->diffInDays($dateNowVar,false);
            //Пеня за просрочку
   
           //Фиксированный сбор
            $fixed_charge_w_nds = 0;
            $fixed_charge_nds = 0;
            if ($dateOfFundingDiff > 0){
                $fixed_charge_var = $tariff->commissions->where('name','Плата за обработку одного документа')->first();
                if ($fixed_charge_var){
                    $fixed_charge_w_nds = $fixed_charge_var->commission_value;
                    $commission->fixed_charge = $fixed_charge_w_nds;


                    if ($fixed_charge_var->nds == true){
                        $fixed_charge_nds = ($fixed_charge_w_nds / 100.00) * $nds;
                    }
                }
            }else{
                $commission->fixed_charge = 0;
            }
            //Процент
            $percent_w_nds = 0;
            $percent_nds = 0;
            if ($dateOfFundingDiff > 0){//если сегодняшнее число больше даты финансирования
                $percent_commission = $tariff->commissions->where('name','Вознаграждение за пользование денежными средствами')->first();
                if ($percent_commission){
                    //Годовые/дни
                    if ($percent_commission->rate_stitching == true){
                        $percent = ($percent_commission->commission_value) / $daysInYear;
                    }else{
                        $percent = $percent_commission->commission_value;
                    }

                    $handle = $percent_commission->additional_sum;
                    //От финансирование либо накладной
                    if ($handle == true){
                        $percent_w_nds = (($delivery->waybill_amount / 100.00) * $percent) *  $dateOfWaybillDiff;
                        $commission->percent = $percent_w_nds;
                    }else{
                        $percent_w_nds = (($delivery->remainder_of_the_debt_first_payment / 100.00) * $percent) * $dateOfFundingDiff;
                        $commission->percent = $percent_w_nds;
                    }
                    
                    if ($percent_commission->nds == true){
                        $percent_nds = ($percent_w_nds / 100.00) * $nds;
                    }
                }
            }else{
                $commission->percent = 0;
            }
   //       //true- waybill, false-first payment
   //       //udz
            $udz_w_nds = 0;
            $udz_nds = 0;
            if ($dateOfFundingDiff > 0){//если сегодняшнее число больше даты финансирования
                $udz_commission = $tariff->commissions->where('name','Вознаграждение за УДЗ')->first();
                if ($udz_commission){
                    $dayOrYear = $udz_commission->rate_stitching;
                    //Нахождение разницы
                    $handle = $percent_commission->additional_sum;
                    //проверка накладной и финансирования
                    if ($handle == true){
                        $dateInterval = $dateOfWaybillDiff;
                        $waybillOrFirstPayment = $delivery->waybill_amount;
                    }else{
                        $dateInterval = $dateOfFundingDiff;
                        $waybillOrFirstPayment = $delivery->remainder_of_the_debt_first_payment;
                    }
                    $dateIntervalSum = $dateInterval;
                    $udz_commission_id = $udz_commission->id;
                    $commissionsRages = CommissionsRage::Where('commission_id','=',$udz_commission_id)
                                                        ->get()
                                                        ->sortBy('min');
                    if($commissionsRages){
                        foreach ($commissionsRages as $rage){
                            $min = $rage->min;

                            if ($rage->max != 0){
                                $max = $rage->max;
                            }else{
                                $max = 99999;
                            }

                            if ($dayOrYear == true){
                                $udz = ($rage->value) / $daysInYear;
                            }else{
                                $udz = $rage->value;
                            }

                            if ($dateInterval >= $min && $dateInterval <= $max){
                                $udz_w_nds += (($waybillOrFirstPayment / 100.00) * $udz) * $dateIntervalSum;      
                            }elseif($dateInterval >= $max){
                                //Включение пределов интервала
                                if ($min != 0){
                                    $handlerInterval = 1;
                                }else{
                                    $handlerInterval = 0;
                                }

                                $diff = $max - $min + $handlerInterval;
                                $udz_w_nds += (($waybillOrFirstPayment / 100.00) * $udz) * $diff; 
                                $defermentSum -= $diff;
                            }
                        }
                        //без ндс
                        $commission->udz = $udz_w_nds;
                        //Ндс
                        if ($udz_commission->nds == true){
                            $udz_nds = ($udz_w_nds / 100.00) * $nds;
                        }
                    }
                }else{
                   // var_dump('Коммиссии не найдено');
                }
            }else{
                $commission->udz = 0;
            }

   //       //Пеня за просрочку
            $penalty_w_nds = 0;
            $penalty_nds = 0;
            if ($dateOfFundingDiff > 0){//если сегодняшнее число больше даты финансирования
                $penalty_commission = $tariff->commissions->where('name','Пеня за просрочку')->first();
                if ($penalty_commission){
                    $dayOrYear = $penalty_commission->rate_stitching;
                    $deferment = $delivery->the_actual_deferment;
                    $defermentSum = $deferment;
                    //Нахождение разницы
                    $penalty_commission_id = $penalty_commission->id;
                    $commissionsRages = CommissionsRage::Where('commission_id','=',$penalty_commission_id)
                                                        ->get()
                                                        ->sortBy('min');

                    if ($commissionsRages){
                        foreach ($commissionsRages as $rage){
                            $min = $rage->min;

                            if ($rage->max != 0){
                                $max = $rage->max;
                            }else{
                                $max = 99999;
                            }
                            
                            if ($dayOrYear == true){
                                $penalty = ($rage->value) / $daysInYear;
                            }else{
                                $penalty = $rage->value;
                            }

                            if ($deferment >= $min && $deferment <= $max){
                                $penalty_w_nds += (($delivery->remainder_of_the_debt_first_payment / 100.00) * $penalty) * $defermentSum;      
                            }elseif($deferment >= $max){
                                //Включение пределов интервала
                                if ($min != 0){
                                    $handlerInterval = 1;
                                }else{
                                    $handlerInterval = 0;
                                }

                                $diff = $max - $min + $handlerInterval;
                                $penalty_w_nds += (($delivery->remainder_of_the_debt_first_payment / 100.00) * $penalty) * $diff;
                                $defermentSum -= $diff;
                            }
                        }
                        //без ндс
                        $commission->deferment_penalty = $penalty_w_nds;
                        //Ндс
                        if ($penalty_commission->nds == true){
                            $penalty_nds = ($penalty_w_nds / 100.00) * $nds;
                        }
                    }else{
                        //Нет диапазона
                    }
                }
            }else{
                 $commission->deferment_penalty = 0;
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
