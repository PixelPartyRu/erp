<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use IlluminateDatabaseEloquentModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Client;
use App\Debtor;
use App\Delivery;
use App\Repayment;
use App\Relation;
use App\DeliveryToFinance;
use App\ChargeCommissionView;
use Carbon\Carbon;
use App\Finance;
use App\RepaymentInvoice;
use App\DailyChargeCommission;

class RepaymentController extends Controller
{
    public function index(){
        $repayments = Repayment::all();
        $clients = Client::all();
        $debtors = Debtor::all();
    	return view('repayment.index',['repayments' => $repayments, 'clients' => $clients, 'debtors' => $debtors]);
    }

    public function store(){
        $sendArray = Input::get('sendArray');
        $outputArray = [];
        //Проверка валидности
        foreach ($sendArray as $array) {
            $errorArray = [];
            if ($array['clientId'] == 0){
                $callback = 'danger';
                $messageShot = 'Ошибка!';
                $message = 'Выберите клиента по п/п № '.$array['number'];
                array_push($errorArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]);
            }else{
                if ($array['debtorId'] != 0){
                    $relation = Relation::where('client_id',$array['clientId'])
                                   ->where('debtor_id', $array['debtorId'])
                                   ->first();
                    if ($relation == null){
                        $callback = 'danger';
                        $messageShot = 'Ошибка!';
                        $message = 'В системе по п/п № '.$array['number'].' отсутствует связь!';
                        array_push($errorArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]);
                    }   
                }
            }         
        }
        //-----------------------------------
        if (count($errorArray) == 0){
            //save
            $messageArray = [];
            $callback = 'success';
            $messageShot = 'Успешно!';
            foreach ($sendArray as $array) {
                $repayment = new Repayment;
                $repayment->number = $array['number'];
                $repayment->date = new Carbon($array['date']);
                $repayment->info = $array['info'];
                $repayment->sum = $array['sum'];
                $repayment->balance = $array['sum'];
                $repayment->purpose_of_payment = $array['purpose_of_payment'];
                $repayment->inn = $array['inn'];
                $clientId = $array['clientId'];
                $debtorId = $array['debtorId'];
                if ($debtorId == 0){
                    $repayment->type = 1;
                    $repayment->client_id = $clientId;
                }else{
                    $client = Client::where('inn',$array['inn'])->first();
                    $debtor = Debtor::where('inn',$array['inn'])->first();

                    if ($client != null && $debtor == null){
                        $repayment->type = -1;
                    }elseif($client == null && $debtor != null){
                        $repayment->type = 0;
                    }else{
                        $repayment->type = -1;//
                    }
                    $repayment->client_id = $clientId;
                    $repayment->debtor_id = $debtorId;
                }
                $repayment->save();
                $message = 'П/п № '.$array['number'].' успешно добавлено!';
                array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]);
            }
            $outputArray = ['error' => false,'data' => $messageArray];
            //-------------------------
        }else{
            $outputArray = ['error' => true,'data' => $errorArray];
        }
        return $outputArray;        
    }

    public function createStore(){
        $send = Input::get('sendArray');
        $outputArray = [];
        $error = false;
        if ((empty($send['number'])) || (empty($send['purpose_of_payment'])) || (empty($send['sum'])) || ($send['clientId'] == 0)){
            $callback = 'danger';
            $messageShot = 'Ошибка!';
            $message = 'Введите все значения';
            $error = true;
        }else{
            if (is_numeric($send['sum'])){
                if (($send['radioChoice'] == 1) && ($send['debtorId'] == 0)){
                    $callback = 'danger';
                    $messageShot = 'Ошибка!';
                    $message = 'Выберите дебитора';
                    $error = true;
                }else{
                    
                    $repayment = new Repayment;
                    $repayment->number = $send['number'];
                    $repayment->date = new Carbon($send['date']);
                    $repayment->sum = $send['sum'];
                    $repayment->balance = $send['sum'];
                    $repayment->purpose_of_payment = $send['purpose_of_payment'];
                    $client = Client::find($send['clientId']);
                    $debtor = Debtor::find($send['debtorId']);
                    if ($send['radioChoice'] == 0){
                        $repayment->client_id = $send['clientId'];
                        $repayment->inn = $client->inn;
                        $repayment->type = 1;
                        $repayment->info = $client->name;
                        $repayment->save();
                        $callback = 'success';
                        $messageShot = 'Успешно!';
                        $message = 'П/п № '.$repayment->number.' успешно добавлено!'; 
                    }else{
                        $repayment->client_id = $send['clientId'];
                        $repayment->debtor_id = $send['debtorId'];
                        $relation = Relation::where('client_id',$send['clientId'])
                                   ->where('debtor_id',$send['debtorId'])
                                   ->first();
                        if ($relation == null){
                            $callback = 'danger';
                            $messageShot = 'Ошибка!';
                            $message = 'В системе по п/п отсутствует связь!';
                            $error = true;
                        }else{
                            if ($send['typeOfPayment'] == 1){
                                //1-клиент
                                $repayment->inn = $client->inn;
                                $repayment->info = $client->name;
                                $repayment->type = -1;
                            }else{
                                //0-дебитор
                                $repayment->inn = $debtor->inn;
                                $repayment->info = $debtor->name;
                                $repayment->type = 0;
                            }
                            $repayment->save();
                            $callback = 'success';
                            $messageShot = 'Успешно!';
                            $message = 'П/п № '.$repayment->number.' успешно добавлено!'; 
                        }
                    }
                }
            }else{
                $callback = 'danger';
                $messageShot = 'Ошибка!';
                $message = 'Некорректное значение суммы';
                $error = true;
            }
        }
        $outputArray = ['error' => $error,'data' => ['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]];
        return $outputArray;
    }

    public function getImportFile(){
    	$contents = Input::get('contents');
    	$contentsArray = explode("\r\n",$contents);
    	$start = false;
    	$resultArray=[];
    	$i = 0;
    	$startVarOne = 'СекцияДокумент=Платежное поручение';
        $startVarTwo = 'СекцияДокумент=Объявление на взнос наличными';
    	$stopVar = 'КонецДокумента';
    	foreach ($contentsArray as $row){
    		if ($start === true){
    			parse_str($row,$var);
    			$key = key($var);
    			$resultArray[$i][$key] = $var[$key];
    		}
    		if ($row === $startVarOne || $row === $startVarTwo){
    			$start = true;
    		}
    		if ($row === $stopVar){
    			$start = false;
    			$i++;
    		}
    	}

    	$clients = Client::all();
    	$debtors = Debtor::all();
    	return view('repayment.rowTable',['resultArray' => $resultArray, 'clients' => $clients, 'debtors' => $debtors]);
    }

    public function getRepayment(){
        $outputArray = [];
        $repayment = Repayment::find(Input::get('id'));
        if ($repayment->balance > 0){
            $client = $repayment->client;
            if ($repayment->type === 0){
                $cor = $repayment->debtor;
            }else{
                $cor = $repayment->client;
            }
            $view = view('repayment.repaymentModalContent',['repayment' => $repayment, 'client' => $client,'cor'=>$cor])->render();
            $outputArray = ['error' => false, 'data' => $view];
        }else{
            $callback = 'danger';
            $messageShot = 'Ошибка!';
            $message = 'Выбранное п/п имеет нулевой баланс';
            $outputArray = ['error' => true, 'data' => ['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]];
        }
        return $outputArray;
    }

    public function getDelivery(){
        $repayment = Repayment::find(Input::get('repaymentId'));
        $handler = Input::get('dataVar');
        if ($handler === 'delivery'){
            $deliveries = $this->getDeliveries($repayment,false);
            return view('repayment.repaymentModalTableDelivery',['deliveries' => $deliveries, 'type' => 'delivery']);
        }elseif($handler === 'commission'){
            $deliveries = $this->getDeliveries($repayment,false);
            return view('repayment.tableCommission',['deliveries' => $deliveries,'type' => 'commission']);    
        }else{
            $deliveries = $this->getDeliveries($repayment,true);
            return view('repayment.repaymentModalTableDelivery',['deliveries' => $deliveries,'type' => 'toClient']);
        }
    }

    public function updateBalance(){
        $id = Input::get('repaymentId');
        $repayment = Repayment::find($id);
        return $repayment->balance;
    }

    public function getDeliveries($repayment,$state){
        if ($repayment->type === 1){
            $deliveries = $repayment->client->deliveries()
                                    ->where('status','=','Профинансирована')
                                    ->where('state','=',$state)
                                    ->get();
        }else{
            $clientVar = $repayment->client->id;
            $debtorVar = $repayment->debtor->id;
            $relation = Relation::where('client_id','=',$clientVar)
                                  ->where('debtor_id','=',$debtorVar)
                                  ->first();
            $deliveries = $relation->deliveries()
                                    ->where('status','=','Профинансирована')
                                    ->where('state','=',$state)
                                    ->get();
        }
        return $deliveries;
    }

    public function repayment(){
        $handler = Input::get('handler');
        $deliveries = Input::get('delivery');
        $repaymentId = Input::get('repaymentId');
        $repayment = Repayment::find(Input::get('repaymentId'));
        if ($handler === 'delivery'){
            $callback = $this->deliveryRepayment($deliveries,$repayment);
        }elseif($handler === 'commission'){
            $callback = $this->commissionRepayment($deliveries,$repayment);
        }else{
            $callback = $this->toClientRepayment($deliveries,$repayment);
        }
        $repayments = Repayment::all();
        $view = view('repayment.tableRepaymentRow',['repayments' => $repayments])->render();
        return ['callback' => $callback, 'view' => $view];
    }

    public function deliveryRepayment($deliveries,$repayment){
        $dateNow = new Carbon(date('Y-m-d'));
        $balance = $repayment->balance;
        $secondFinanceArray = [];
        $messageArray = [];
        $callback = 'success';
        $messageShot = 'Успешно! ';

        foreach ($deliveries as $delivery){
            $dailyArray = [];
            $id = $delivery['delivery'];
            $sum = floatval($delivery['sum']);
            $delivery = Delivery::find($id);
            $first = $delivery->remainder_of_the_debt_first_payment;
            $dailyRepaymentSum = $sum;
            $dayliFirstPaymentDebtBefore = $delivery->remainder_of_the_debt_first_payment;
            $dayliBalanceOwedAfter = 0;
            $dayliToClient = 0;
            $dayliFirstPaymentSum = 0;
            $dayliFirstPaymentDebtAfter = 0;
            $dailyTypeOfPayment = null;
            $dailyRepayment = $repayment->id;

            if ($sum >= $delivery->balance_owed){//полное погашение
                $dayliFirstPaymentSum = $first;

                if($first > 0){
                    $delivery->balance_owed = $delivery->balance_owed - $first;
                    $balance = $balance - $first;
                    $delivery->remainder_of_the_debt_first_payment = 0;
                    $delivery->state = true;
                    $delivery->end_date_of_funding = $repayment->date;
                    $delivery->date_of_payment = $repayment->date;
                }
                $commission = $delivery->chargeCommission()->where('waybill_status',false)->first();
                if ($commission){
                    $debt = $commission->debt;
                    $balance -= $debt;
                    $delivery->balance_owed -= $debt;
                    $dailyFixed = $commission->fixed_charge;
                    $dailyFixedNds = $commission->fixed_charge_nds;
                    $dailyPercent = $commission->percent;
                    $dailyPercentNds = $commission->percent_nds;
                    $dailyUdz = $commission->udz;
                    $dailyUdzNds = $commission->udz_nds;
                    $dailyDeferment = $commission->deferment_penalty;
                    $dailyDefermentNds = $commission->deferment_penalty_nds;
                    $this->repaymentFullCommission($commission,$repayment);
                    //var_dump($commission->debt);
                    //Second pay
                }else{
                    //коммиссии не найдено
                }

                if ($delivery->balance_owed > 0){
                    array_push($secondFinanceArray,[
                        $delivery->id,
                        $delivery->registry,
                        $delivery->balance_owed + $delivery->second_pay,
                        $delivery->date_of_registry,
                        $delivery->client->name
                    ]);
                    $dayliToClient = $delivery->balance_owed;
                    $balance = $balance - $delivery->balance_owed;
                    $delivery->balance_owed = 0;
                }

                $this->sendInvoice($delivery->id,$sum,'Полное погашение поставки',$repayment->id);
                $message = 'Накладная '.$delivery->waybill.' полностью погашена';
                array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]);
            }else{//частичное
                 $delivery->balance_owed = $delivery->balance_owed - $sum;
                 $dayliBalanceOwedAfter = $delivery->balance_owed;
                 $balance = $balance - $sum;
                 $this->sendInvoice($delivery->id,$sum,'Частичное погашение поставки',$repayment->id);
                 if ($sum > $first){//полное погашение поставки
                    $dayliFirstPaymentSum = $first;
                    $sum -= $first;
                    $delivery->remainder_of_the_debt_first_payment = 0;
                    $delivery->state = true;
                    $delivery->end_date_of_funding = $repayment->date;

                    //коммиссии
                    $commission = $delivery->chargeCommission()->where('waybill_status',false)->first();
                    if ($commission){
                        $debt = $commission->debt;
                        if ($sum > $debt){//полное погашение коммисии
                            $dailyFixed = $commission->fixed_charge;
                            $dailyFixedNds = $commission->fixed_charge_nds;
                            $dailyPercent = $commission->percent;
                            $dailyPercentNds = $commission->percent_nds;
                            $dailyUdz = $commission->udz;
                            $dailyUdzNds = $commission->udz_nds;
                            $dailyDeferment = $commission->deferment_penalty;
                            $dailyDefermentNds = $commission->deferment_penalty_nds;
                            $this->repaymentFullCommission($commission,$repayment);
                            //second pay add
                        }else{//частичное пошашение коммиссии
                            $dailyFixed = 0;
                            $dailyFixedNds = 0;
                            $dailyPercent = 0;
                            $dailyPercentNds = 0;
                            $dailyUdz = 0;
                            $dailyUdzNds = 0;
                            $dailyDeferment = 0;
                            $dailyDefermentNds = 0;
                            $commission->debt = $debt - $sum;
                            if ($sum > $commission->fixed_charge){
                                $sum -= $commission->fixed_charge;
                                $dailyFixed = $commission->fixed_charge;
                                $commission->fixed_charge = 0;
                            }else{
                                $dailyFixed = $sum;
                                $commission->fixed_charge = $commission->fixed_charge - $sum;
                                $sum = 0;
                            }
                            if ($sum > 0){
                                if ($sum > $commission->fixed_charge_nds){
                                    $dailyFixedNds = $commission->fixed_charge_nds;
                                    $sum -= $commission->fixed_charge_nds;
                                    $commission->fixed_charge_nds = 0;
                                    $commission->fixed_charge_return = true;
                                }else{
                                    $commission->fixed_charge_nds = $sum;
                                    $commission->fixed_charge_nds = $commission->fixed_charge_nds - $sum;
                                    $sum = 0;
                                }
                                if ($sum > 0){
                                    if ($sum > $commission->percent){
                                        $dailyPercent = $commission->percent;
                                        $sum -= $commission->percent;
                                        $commission->percent = 0;
                                    }else{
                                        $dailyPercent = $sum;
                                        $commission->percent = $commission->percent - $sum;
                                        $sum = 0;
                                    }
                                    if ($sum > 0){
                                        if ($sum > $commission->percent_nds){
                                            $dailyPercentNds = $commission->percent_nds;
                                            $sum -= $commission->percent_nds;
                                            $commission->percent_nds = 0;
                                            $commission->percent_return = true;
                                        }else{
                                            $commission->percent_nds = $sum;
                                            $commission->percent_nds = $commission->percent_nds - $sum;
                                            $sum = 0;
                                        }
                                        if ($sum > 0){
                                            if ($sum > $commission->udz){
                                                $dailyUdz = $commission->udz;
                                                $sum -= $commission->udz;
                                                $commission->udz = 0;
                                            }else{
                                                $dailyUdz = $sum;
                                                $commission->udz = $commission->udz - $sum;
                                                $sum = 0;
                                            }
                                            if ($sum > 0){
                                                if ($sum > $commission->udz_nds){
                                                    $dailyUdzNds = $commission->udz_nds;
                                                    $sum -= $commission->udz_nds;
                                                    $commission->udz_nds = 0;
                                                    $commission->udz_return = true;
                                                }else{
                                                    $dailyUdzNds = $sum;
                                                    $commission->udz_nds = $commission->udz_nds - $sum;
                                                    $sum = 0;
                                                }
                                                if ($sum > 0){
                                                    if ($sum > $commission->deferment_penalty){
                                                        $dailyDeferment = $commission->deferment_penalty;
                                                        $sum -= $commission->deferment_penalty;
                                                        $commission->deferment_penalty = 0;
                                                    }else{
                                                        $dailyDeferment = $sum;
                                                        $commission->deferment_penalty = $commission->deferment_penalty - $sum;
                                                        $sum = 0;
                                                    }
                                                    if ($sum > 0){
                                                        if ($sum > $commission->deferment_penalty_nds){
                                                            $dailyDefermentNds = $commission->deferment_penalty_nds;
                                                            $sum -= $commission->deferment_penalty_nds;
                                                            $commission->deferment_penalty_nds = 0;
                                                            $commission->deferment_penalty_return = true;
                                                        }else{
                                                            $dailyDefermentNds = $sum;
                                                            $commission->deferment_penalty_nds = $commission->deferment_penalty_nds - $sum;
                                                            $sum = 0;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if ($sum > 0){
                                $delivery->second_pay = $sum;
                                $sum = 0;
                            }
                            $commission->without_nds = $commission->fixed_charge + $commission->percent + $commission->udz + $commission->deferment_penalty;
                            $commission->nds = $commission->fixed_charge_nds + $commission->percent_nds + $commission->udz_nds + $commission->deferment_penalty_nds;
                            $commission->with_nds = $commission->without_nds + $commission->nds;
                            $commission->save();
                        }
                    }else{
                        //коммиссии не найдено
                    }
                }else{//частичное погашение поставки
                    $delivery->remainder_of_the_debt_first_payment = $first - $sum;
                    $dayliFirstPaymentSum = $sum;
                    $dayliFirstPaymentDebtAfter = $delivery->remainder_of_the_debt_first_payment;
                    $sum = 0;
                }

                
                $message = 'Накладная '.$delivery->waybill.' погашена частично';
                array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]);
            }

            $return = $delivery->return;
            $returnType = $repayment->type;

                if ($returnType === 0){
                    if (($return === '') || ($return == 'Д')){
                        $returnHandler = 'Д';
                    }else{
                        $returnHandler = 'К/Д';
                    } 
                }else{
                    if (($return === '') || ($return == 'К')){
                        $returnHandler = 'К';
                    }else{
                        $returnHandler = 'К/Д';
                    }
                }

            $delivery->return = $returnHandler;
            $dailyTypeOfPayment = $returnHandler;
            $delivery->save();

            $repayment->balance = $balance;
            $repayment->save();

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
                'dailyRepaymentSum' => $dailyRepaymentSum,
                'dayliFirstPaymentDebtBefore' => $dayliFirstPaymentDebtBefore,
                'dayliBalanceOwedAfter' => $dayliBalanceOwedAfter,
                'dayliToClient' => $dayliToClient,
                'dayliFirstPaymentSum' => $dayliFirstPaymentSum,
                'dayliFirstPaymentDebtAfter' => $dayliFirstPaymentDebtAfter,
                'dailyTypeOfPayment' => $dailyTypeOfPayment,
                'dailyRepayment' => $dailyRepayment,
                'dayliFirstPaymentDebtAfter' => $dayliFirstPaymentDebtAfter

            ];
            $this->createDailyCharge($dailyArray,$commission->id,$delivery->id);
        } 
        //second pay
        if (count($secondFinanceArray) > 0){
            usort($secondFinanceArray,function($a, $b){
                if ($a[1] == $b[1]) {
                    return 0;
                }
                return ($a[1] < $b[1]) ? -1 : 1;
            });

            $waybillNumber = 1;
            $deliveryArray = [];
            $typeFinance = 'Второй платеж';
            $statusFinance = 'К финансированию';

            for($i = 0; $i<count($secondFinanceArray); $i++){
                $registry = $secondFinanceArray[$i][1];
                array_push($deliveryArray,$secondFinanceArray[$i][0]);
                if (isset($secondFinanceArray[$i + 1])){
                    if ($secondFinanceArray[$i + 1][1] == $registry){
                        $secondFinanceArray[$i + 1][2] += $secondFinanceArray[$i][2];
                        $waybillNumber++;
                    }else{
                        $finance = new Finance;
                        $finance->client = $secondFinanceArray[$i][4];
                        $finance->sum = $secondFinanceArray[$i][2];
                        $finance->number_of_waybill = $waybillNumber;
                        $finance->type_of_funding = $typeFinance;
                        $finance->registry = $secondFinanceArray[$i][1];
                        $finance->date_of_registry = $secondFinanceArray[$i][3];
                        $finance->status = $statusFinance;
                        if ($finance->save()){
                            foreach ($deliveryArray as $id){
                                $deliveryToFinance = new DeliveryToFinance;
                                $deliveryToFinance->delivery_id = $id;
                                $deliveryToFinance->finance_id = $finance->id;
                                $deliveryToFinance->save();
                            }   
                        }
                        $deliveryArray = [];
                        $waybillNumber = 1; 
                    }
                }else{
                    $finance = new Finance;
                    $finance->client = $secondFinanceArray[$i][4];
                    $finance->sum = $secondFinanceArray[$i][2];
                    $finance->number_of_waybill = $waybillNumber;
                    $finance->type_of_funding = $typeFinance;
                    $finance->registry = $secondFinanceArray[$i][1];
                    $finance->date_of_registry = $secondFinanceArray[$i][3];
                    $finance->status = $statusFinance;
                    if ($finance->save()){
                        foreach ($deliveryArray as $id){
                            $deliveryToFinance = new DeliveryToFinance;
                            $deliveryToFinance->delivery_id = $id;
                            $deliveryToFinance->finance_id = $finance->id;
                            $deliveryToFinance->save();
                        }   
                    }
                    $deliveryArray = [];
                    $waybillNumber = 1; 
                }
            }
        }
        return $messageArray;
    }

    public function repaymentFullCommission($commission,$repayment){

        $commission->debt = 0;
        $commission->fixed_charge = 0;
        $commission->percent = 0;
        $commission->udz = 0;
        $commission->deferment_penalty = 0;

        $commission->fixed_charge_nds = 0;
        $commission->percent_nds = 0;
        $commission->udz_nds = 0;
        $commission->deferment_penalty_nds = 0;

        $commission->fixed_charge_return = true;
        $commission->percent_return = true;
        $commission->udz_return = true;
        $commission->deferment_penalty_return = true;

        $commission->with_nds = 0;
        $commission->without_nds = 0;
        
        $commission->nds = 0;
        $commission->date_of_repayment = $repayment->date;
        $commission->waybill_status = true;
        $commission->save();

        $commissionView = $commission->chargeCommissionView;
        $commissionView->date_of_repayment = $commission->date_of_repayment;
        $commissionView->waybill_status = $commission->waybill_status;
        $commissionView->charge_commission_id = $commission->id;
        $commissionView->save();
    }

    public function commissionRepayment($deliveries,$repayment){
        $balance = $repayment->balance;
        $messageArray = [];
        $callback = 'success';
        $messageShot = 'Успешно! ';
        $dateNow = new Carbon(date('Y-m-d'));
        
        foreach ($deliveries as $delivery){
            $id = $delivery['delivery'];
            $sum = floatval($delivery['sum']);
            $type = $delivery['type'];
            $delivery = Delivery::find($id);
            $dailyFixed = 0;
            $dailyFixedNds = 0;
            $dailyPercent = 0;
            $dailyPercentNds = 0;
            $dailyUdz = 0;
            $dailyUdzNds = 0;
            $dailyDeferment = 0;
            $dailyDefermentNds = 0;

            $dailyRepaymentSum = $sum;
            $dayliFirstPaymentDebtBefore = $delivery->remainder_of_the_debt_first_payment;
            $dayliBalanceOwedAfter = 0;
            $dayliToClient = 0;
            $dayliFirstPaymentSum = 0;
            $dayliFirstPaymentDebtAfter = $delivery->remainder_of_the_debt_first_payment;
            $dailyTypeOfPayment = null;
            $dailyRepayment = $repayment->id;

            $delivery->balance_owed -= $sum;
            $dayliBalanceOwedAfter = $delivery->balance_owed;

            $commission = $delivery->chargeCommission()->where('waybill_status',false)->first();
            if ($commission){
                $balance-=$sum;
                $this->sendInvoice($delivery->id,$sum,'Погашение коммисии',$repayment->id);
                if ($type === 'commission'){
                    if ($sum < $commission->fixed_charge){//частичное погашение коммисии
                        $dailyFixed = $sum;
                        $commission->fixed_charge = $commission->fixed_charge - $sum;
                        $sum=0;
                        $message = 'Коммиссии по накладной '.$delivery->waybill.' погашено частичны';
                        array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]);
                    }else{
                        $dailyFixed = $commission->fixed_charge;
                        $sum -= $commission->fixed_charge;
                        $commission->fixed_charge = 0;
                        if ($sum < $commission->fixed_charge_nds){//частичное погашение ндс
                            $dailyFixedNds = $sum;
                            $commission->fixed_charge_nds -= $sum;
                            $sum = 0;
                        }else{
                            $dailyFixedNds = $commission->fixed_charge_nds;
                            $commission->fixed_charge_nds = 0;
                            $commission->fixed_charge_return = true;
                            $sum = 0;
                        }
                        $message = 'Коммиссии по накладной '.$delivery->waybill.' полностью погашены';
                        array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]);
                    }
                }else{//--------------------------
                    if ($sum < $commission->debt - ($commission->fixed_charge + $commission->fixed_charge_nds)){//частичное погашение коммисии
                        if ($sum < $commission->percent){
                            $dailyPercent = $sum;
                            $commission->percent = $commission->percent - $sum;
                            $sum = 0;
                        }else{
                            $dailyPercent = $commission->percent;
                            $sum -= round($commission->percent,2,PHP_ROUND_HALF_UP);
                            $commission->percent = 0;
                            if ($sum < $commission->percent_nds){
                                $dailyPercentNds = $sum;
                                $commission->percent_nds -= $sum;
                                $sum = 0;
                            }else{
                                $dailyPercentNds = $commission->percent_nds;
                                $sum -= round($commission->percent_nds,2,PHP_ROUND_HALF_UP);
                                $commission->percent_nds = 0;
                                $commission->percent_return = true;
                            }
                            if ($sum < $commission->udz){
                                $dailyUdz = $sum;
                                $commission->udz -= $sum;
                                $sum = 0;
                            }else{
                                $dailyUdz = $commission->udz;
                                $sum -= round($commission->udz,2,PHP_ROUND_HALF_UP);
                                $commission->udz = 0;
                                if ($sum < $commission->udz_nds){
                                    $dailyUdzNds = $sum;
                                    $commission->udz_nds -= $sum;
                                    $sum = 0;
                                }else{
                                    $dailyUdzNds = $commission->udz_nds;
                                    $sum -= round($commission->udz_nds,2,PHP_ROUND_HALF_UP);
                                    $commission->udz_nds = 0;
                                    $commission->udz_return = true;
                                }
                                if ($sum < $commission->deferment_penalty){
                                    $dailyDeferment = $sum;
                                    $commission->deferment_penalty = $commission->deferment_penalty - $sum;
                                    $sum = 0;
                                }else{
                                    $dailyDeferment = $commission->deferment_penalty;
                                    $sum -= round($commission->deferment_penalty,2,PHP_ROUND_HALF_UP);
                                    $commission->deferment_penalty = 0;
                                    if ($sum < $commission->deferment_penalty_nds){
                                        $dailyDefermentNds = $sum;
                                        $commission->deferment_penalty_nds -= $sum;
                                        $sum = 0;
                                    }else{
                                        $dailyDefermentNds = $commission->deferment_penalty_nds;
                                        $sum -= round($commission->deferment_penalty_nds,2,PHP_ROUND_HALF_UP);
                                        $commission->deferment_penalty_nds = 0;
                                        $commission->deferment_penalty_return = true;
                                    }
                                }
                            }
                        }
                        $message = 'Проценты по коммиссии по накладной '.$delivery->waybill.' частично погашены';
                        array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]);
                    }else{//---------------------------------------
                        $sum=0;

                        $dailyFixed = $commission->fixed_charge;
                        $dailyFixedNds = $commission->fixed_charge_nds;
                        $dailyPercent = $commission->percent;
                        $dailyPercentNds = $commission->percent_nds;
                        $dailyUdz = $commission->udz;
                        $dailyUdzNds = $commission->udz_nds;
                        $dailyDeferment = $commission->deferment_penalty;
                        $dailyDefermentNds = $commission->deferment_penalty_nds;

                        $commission->debt = 0;
                        $commission->fixed_charge = 0;
                        $commission->percent = 0;
                        $commission->udz = 0;
                        $commission->deferment_penalty = 0;

                        $commission->fixed_charge_nds = 0;
                        $commission->percent_nds = 0;
                        $commission->udz_nds = 0;
                        $commission->deferment_penalty_nds = 0;

                        $commission->fixed_charge_return = true;
                        $commission->percent_return = true;
                        $commission->udz_return = true;
                        $commission->deferment_penalty_return = true;
                        $message = 'Проценты по коммиссии по накладной '.$delivery->waybill.' полностью погашены';
                        array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]);
                    }
                }
                $commission->without_nds = $commission->fixed_charge + $commission->percent + $commission->udz + $commission->deferment_penalty;
                $commission->nds = $commission->fixed_charge_nds + $commission->percent_nds + $commission->udz_nds + $commission->deferment_penalty_nds;
                $commission->with_nds = $commission->without_nds + $commission->nds;
                $commission->debt = $commission->with_nds;
                if ($commission->debt <= 0){
                    $commission->date_of_repayment = $repayment->date();
                    $commission->waybill_status = true; 
                }
                $commission->save();

                $daily_without_nds = $dailyFixed + $dailyPercent + $dailyUdz + $dailyDeferment;
                $daily_nds = $dailyFixedNds + $dailyPercentNds + $dailyUdzNds + $dailyDefermentNds;
                $daily_with_nds = $daily_without_nds + $daily_nds;

                $return = $delivery->return;
                $returnType = $repayment->type;

                    if ($returnType === 0){
                        if (($return === '') || ($return == 'Д')){
                            $returnHandler = 'Д';
                        }else{
                            $returnHandler = 'К/Д';
                        } 
                    }else{
                        if (($return === '') || ($return == 'К')){
                            $returnHandler = 'К';
                        }else{
                            $returnHandler = 'К/Д';
                        }
                    }

                $delivery->return = $returnHandler;
                $dailyTypeOfPayment = $returnHandler;
                $delivery->save();

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
                    'dailyRepaymentSum' => $dailyRepaymentSum,
                    'dayliFirstPaymentDebtBefore' => $dayliFirstPaymentDebtBefore,
                    'dayliBalanceOwedAfter' => $dayliBalanceOwedAfter,
                    'dayliToClient' => $dayliToClient,
                    'dayliFirstPaymentSum' => $dayliFirstPaymentSum,
                    'dayliFirstPaymentDebtAfter' => $dayliFirstPaymentDebtAfter,
                    'dailyTypeOfPayment' => $dailyTypeOfPayment,
                    'dailyRepayment' => $dailyRepayment,
                    'dayliFirstPaymentDebtAfter' => $dayliFirstPaymentDebtAfter
                ];
                $this->createDailyCharge($dailyArray,$commission->id,$delivery->id);
            }

            $repayment->balance = $balance;
            $repayment->save();   
        }

        return $messageArray;
    }

    public function toClientRepayment($deliveries,$repayment){
        $messageArray = [];
        $callback = 'success';
        $messageShot = 'Успешно! ';

        foreach ($deliveries as $delivery){
            $id = $delivery['delivery'];
            $sum = $delivery['sum'];
            $delivery = Delivery::find($id);

            $finance = new Finance;
            $finance->client = $delivery->client->name;
            $finance->sum = $sum;
            $finance->number_of_waybill = 1;
            $finance->type_of_funding = "Перечислено клиенту";
            //$finance->date_of_funding
            $finance->registry = $delivery->registry;
            $finance->date_of_registry = $delivery->date_of_registry;
            $finance->status = "К финансированию";
            if ($finance->save()){
                $return = $delivery->return;
                $returnType = $repayment->type;

                    if ($returnType === 0){
                        if (($return === '') || ($return == 'Д')){
                            $returnHandler = 'Д';
                        }else{
                            $returnHandler = 'К/Д';
                        } 
                    }else{
                        if (($return === '') || ($return == 'К')){
                            $returnHandler = 'К';
                        }else{
                            $returnHandler = 'К/Д';
                        }
                    }

                $delivery->return = $returnHandler;
                $delivery->save();
                $this->sendInvoice($delivery->id,$sum,'Перечислено клиенту',$repayment->id);

                $deliveryToFinance = new DeliveryToFinance;
                $deliveryToFinance->delivery_id = $delivery->id;
                $deliveryToFinance->finance_id = $finance->id;
                $deliveryToFinance->save();

                if ($sum > $repayment->balance){
                    $repayment->balance = 0;
                }else{
                    $repayment->balance = $repayment->balance - $sum;
                }
                $repayment->save();
            } 
            $message = 'Денежные средства по накладной '.$delivery->waybill.' перечислены клиенту';
            array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]);
        }

    return $messageArray;

    }

    public function sendInvoice($client,$sum,$type,$repayment){
        $invoice = new RepaymentInvoice;
        $invoice->sum = $sum;
        $invoice->delivery_id = $client;
        $invoice->type = $type;
        $invoice->repayment_id = $repayment;
        $invoice->save();
    }

    public function getDeliveryFirstPayment(){
        $id = Input::get('dataId');
        $delivery = Delivery::find($id);
        return $delivery->balance_owed;
    }

    public function getWaybillAmount(){
        $id = Input::get('dataId');
        $delivery = Delivery::find($id);
        return $delivery->waybill_amount;
    }

    public function getCommissionData(){
        $id = Input::get('dataId');
        $delivery = Delivery::find($id);
        $commission = $delivery->chargeCommission;
        $type = Input::get('type');

        if ($type === 'commission'){
            return $commission->fixed_charge + $commission->fixed_charge_nds;
        }else{
            return $commission->debt - ($commission->fixed_charge + $commission->fixed_charge_nds);
        }
        
    }

    public function getIndexRepayment(){
        $repayments = Repayment::all();
        $clients = Client::all();
        $debtors = Debtor::all();
        return view('repayment.tableRepaymentRow',['repayments' => $repayments, 'clients' => $clients, 'debtors' => $debtors]);
    }

    public function deleteRepayment()
    {   
        $id = Input::get('id');
        $repayment = Repayment::find($id);
        if ($repayment->balance == $repayment->sum) {
            $callback = 'success';
            $dataId = $id;
             return ['callback' => $callback,'id'=>$dataId];
        }else {
            $callback = 'danger';
            $messageShot = 'Ошибка!';
            $message = 'П/п не может быть удалено';
            return ['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot];
        }     
    }

    public function deleteConfirm(){
        $id = Input::get('id');
        $repayment = Repayment::find($id);
        Repayment::destroy($id);
        $callback = 'success';
        $messageShot = 'Успешно!';
        $message = 'П/п под номером '.$repayment->number.' успешно удалено';

        $repayments = Repayment::all();
        $view = view('repayment.tableRepaymentRow',['repayments' => $repayments])->render();

        return ['view' => $view,'data' => ['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]];
    }

    public function createDailyCharge($dailyArray,$commissionId,$deliveryId){

        $daily = new DailyChargeCommission;
        $repayment = Repayment::find($dailyArray['dailyRepayment']);
        $daily->delivery_id = $deliveryId;
        $daily->charge_commission_id = $commissionId;

        $daily->fixed_charge = $dailyArray['dailyFixed'];
        $daily->percent = $dailyArray['dailyPercent'];
        $daily->udz = $dailyArray['dailyUdz'];      
        $daily->deferment_penalty = $dailyArray['dailyDeferment'];

        $daily->nds = $dailyArray['dailyNds'];
        $daily->without_nds = $dailyArray['dailyWithoutNds'];
        $daily->with_nds = $dailyArray['dailyWithNds'];
        $daily->handler = true;

        $daily->fixed_charge_nds = $dailyArray['dailyFixedNds'];
        $daily->percent_nds = $dailyArray['dailyPercentNds'];
        $daily->udz_nds = $dailyArray['dailyUdzNds'];
        $daily->deferment_penalty_nds = $dailyArray['dailyDefermentNds'];
        $daily->created_at = $repayment->date;
        $daily->repayment_id = $dailyArray['dailyRepayment'];
        $daily->repayment_sum = $dailyArray['dailyRepaymentSum'];
        $daily->first_payment_sum = $dailyArray['dayliFirstPaymentSum'];
        $daily->first_payment_debt_after = $dailyArray['dayliFirstPaymentDebtAfter'];
        $daily->first_payment_debt_before = $dailyArray['dayliFirstPaymentDebtBefore'];
        $daily->balance_owed_after = $dailyArray['dayliBalanceOwedAfter'];
        $daily->to_client = $dailyArray['dayliToClient'];
        $daily->type_of_payment = $dailyArray['dailyTypeOfPayment'];

        $daily->save();
    }
}
