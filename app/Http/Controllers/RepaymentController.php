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
use App\ChargeCommission;
use Carbon\Carbon;
use App\CommissionsRage;
use App\Finance;
use App\RepaymentInvoice;
use App\DailyChargeCommission;
use App\Bill;

class RepaymentController extends Controller
{
    public function index(){
        $repayments = Repayment::OrderBy('date')->get();
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
        $repaymentDate = new Carbon($repayment->date);
        $lastBill = Bill::orderBy('bill_date','desc')->first();
        $lastBillCarbon = new Carbon($lastBill->bill_date);
        
        $diffBillsDate = $lastBillCarbon->diffInDays($repaymentDate,false);

        if ($diffBillsDate > 0){
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
        }else{
            $callback = 'danger';
            $messageShot = 'Ошибка п/п!';
            $message = 'Платежное поручение датировано датой закрытого месяца';
            $outputArray = ['error' => true, 'data' => ['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]];
        }
        return $outputArray;
    }

    public function getDelivery(){
        $repayment = Repayment::find(Input::get('repaymentId'));
        $handler = Input::get('dataVar');
        if ($handler === 'delivery'){
            $deliveries = $this->getDeliveries($repayment,false,false);
            return view('repayment.repaymentModalTableDelivery',['deliveries' => $deliveries, 'type' => 'delivery']);
        }elseif($handler === 'commission'){
            $deliveries = $this->getDeliveriesCommission($repayment);
            return view('repayment.tableCommission',['deliveries' => $deliveries,'repayment' => $repayment,'type' => 'commission']);    
        }else{
            $deliveries = $this->getDeliveries($repayment,true,true);
            return view('repayment.repaymentModalTableDelivery',['deliveries' => $deliveries,'type' => 'toClient']);
        }
    }

    public function updateBalance(){
        $id = Input::get('repaymentId');
        $repayment = Repayment::find($id);
        return $repayment->balance;
    }

    public function getDeliveries($repayment,$state,$return){
        if ($repayment->type === 1){
            $deliveries = $repayment->client->deliveries()
                                    ->where('status','=','Профинансирована')
                                    ->where('state','=',$state)
                                    ->orderBy('date_of_waybill');
        }else{
            $clientVar = $repayment->client->id;
            $debtorVar = $repayment->debtor->id;
            $relation = Relation::where('client_id','=',$clientVar)
                                  ->where('debtor_id','=',$debtorVar)
                                  ->first();
            $deliveries = $relation->deliveries()
                                    ->where('status','=','Профинансирована')
                                    ->where('state','=',$state)
                                    ->orderBy('date_of_waybill');
                                  
        }
        if ($return == true){
            $deliveries = $deliveries->where('return','!=','Д');
        }
        return $deliveries->get();  
    }

    public function getDeliveriesCommission($repayment){
    	if ($repayment->type === 1){
    		$korresp = $repayment->client;
    	}else{
    		$clientVar = $repayment->client->id;
            $debtorVar = $repayment->debtor->id;
            $korresp = Relation::where('client_id','=',$clientVar)
                                  ->where('debtor_id','=',$debtorVar)
                                  ->first();
    	}
    	$deliveries = $korresp->deliveries()->whereHas('chargeCommission', function ($query) {
						    $query->where('waybill_status', false);
						})->get();
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
        $repayments = Repayment::OrderBy('date')->get();
        $view = view('repayment.tableRepaymentRow',['repayments' => $repayments])->render();
        return ['callback' => $callback, 'view' => $view];
    }

    public function deliveryRepayment($deliveries,$repayment){
        $dateNow = new Carbon(date('Y-m-d'));
        $balance = $repayment->balance;
        $secondFinanceArray = [];
        $messageArray = [];

        foreach ($deliveries as $delivery){
            $dailyArray = [];
            $callback = 'success';
            $messageShot = 'Успешно! ';

            $id = $delivery['delivery'];
            $sum = floatval($delivery['sum']);
            $delivery = Delivery::find($id);

            //проверка на позднее погашение
            $overRepaymentDate = DailyChargeCommission::where('handler',true)
                                    ->where('delivery_id','=',$delivery->id)
                                    ->whereDate('created_at','>',$repayment->date)
                                    ->get();
            if (count($overRepaymentDate) == 0){
                                    
                $sumEqBalance = true;
                if ($sum != $delivery->balance_owed){
    				$sumEqBalance = false;
                }//проверка равенства остатка накладной и поступившего платежа

                $this->deleteOverCommission($delivery,$repayment);

                $first = $delivery->remainder_of_the_debt_first_payment;
                $dailyRepaymentSum = $sum;
                $dayliFirstPaymentDebtBefore = $delivery->remainder_of_the_debt_first_payment;
                $dayliBalanceOwedAfter = 0;
                $dayliToClient = 0;
                $dayliFirstPaymentSum = 0;
                $dayliFirstPaymentDebtAfter = 0;
                $dailyTypeOfPayment = null;
                $dailyRepayment = $repayment->id;

                //погашение
                $delivery->balance_owed = $delivery->balance_owed - $sum;
                $dayliBalanceOwedAfter = $delivery->balance_owed;
                $balance = $balance - $sum;
                $dailyFixed = 0;
                $dailyFixedNds = 0;
                $dailyPercent = 0;
                $dailyPercentNds = 0;
                $dailyUdz = 0;
                $dailyUdzNds = 0;
                $dailyDeferment = 0;
                $dailyDefermentNds = 0;

                if ($sum > $first){//полное погашение поставки
                    $dayliFirstPaymentSum = $first;
                    $sum -= $first;
                    $delivery->remainder_of_the_debt_first_payment = 0;
                    $delivery->end_date_of_funding = $repayment->date;

                    //коммиссии
                    $commission = $delivery->chargeCommission;
                    if ($commission){
                      	$debt = $commission->debt;

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
                                $dailyFixedNds = $sum;
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
                                    	$dailyPercentNds = $sum;
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
                        $commission->without_nds = $commission->fixed_charge + $commission->percent + $commission->udz + $commission->deferment_penalty;
                        $commission->nds = $commission->fixed_charge_nds + $commission->percent_nds + $commission->udz_nds + $commission->deferment_penalty_nds;
                        $commission->with_nds = $commission->without_nds + $commission->nds;
                        $commission->debt = $commission->with_nds;
                        $commission->save();

                        if ($commission->debt <= 0){//коммиссии полностью погашены debt == 0
                            $this->repaymentFullCommission($commission,$repayment);
                        	if ($sumEqBalance == true){ //to client
                        		$delivery->state = true;
    	               			$delivery->date_of_payment = $repayment->date;
                        		if ($sum > 0){ 
                        			$toClientSum = $sum + $delivery->second_pay;                  			
    								array_push($secondFinanceArray,[
    			                        $delivery->id,
    			                        $delivery->registry,
    			                        $toClientSum,
    			                        $delivery->date_of_registry,
    			                        $delivery->client->name
    			                    ]);
    			                    $dayliToClient = $toClientSum;
    			                    $message = 'Накладная и коммиссии '.$delivery->waybill.' погашены. Второй платеж сформирован.';	
                        		}else{
                        			$message = 'Накладная и коммиссии '.$delivery->waybill.' погашены. Остаток для второго платежа равен нулю.';	
                        		}
                                $commission->waybill_status = true;
                                $commission->save();
                        	}else{//second pay
    							$delivery->second_pay += $sum;
    							$message = 'Накладная и коммиссии по накладной '.$delivery->waybill.'погашены. Остаток частично покрывает второй платеж.';
                        	}
                        }else{//коммиссии пошашены частично
                        	if ($sumEqBalance == true){ //to client
                        		$delivery->state = true;
    	               			$delivery->date_of_payment = $repayment->date;
                        		$message = 'Накладная '.$delivery->waybill.' погашена. Коммиссии погашены частично';
                        	}else{
    	                    	$message = 'Коммиссии по накладной '.$delivery->waybill.' погашены частично';
                        	}	
                        }
                    }else{
                        //коммиссии не найдено
                    }
                }else{//частичное погашение поставки sum < first
                    $delivery->remainder_of_the_debt_first_payment = $first - $sum;
                    $dayliFirstPaymentSum = $sum;
                    $dayliFirstPaymentDebtAfter = $delivery->remainder_of_the_debt_first_payment;
                    $message = 'Первый платеж по накладной '.$delivery->waybill.' погашен частично';
                }

                $sum = 0;
                $delivery->save();
                $this->recalculateDailyCharge($repayment,$delivery->id);//recalculation
                array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]);
                //конец погашения

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
                    'dayliFirstPaymentDebtAfter' => $dayliFirstPaymentDebtAfter

                ];
                $this->createDailyCharge($dailyArray,$delivery->id,$repayment->id,true);
            }else{
                $callback = 'danger';
                $messageShot = 'Ошибка! ';
                $message = 'Погашение по пп № '.$repayment->number.' не может быть выполнено, так как по данной накладной было проведено погашение более поздней датой.';
                array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]);
            }
        } 
        //second pay
        if (count($secondFinanceArray) > 0){
            $this->secondPay($secondFinanceArray);//second pay
        }

        return $messageArray;
    }

    public function secondPay($secondFinanceArray){
        
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

        // $commission->fixed_charge_return = true;
        // $commission->percent_return = true;
        // $commission->udz_return = true;
        // $commission->deferment_penalty_return = true;
        $commission->date_of_repayment = $repayment->date;
        $commission->with_nds = 0;
        $commission->without_nds = 0;
        
        $commission->nds = 0;
        $commission->save();
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

            $dayliBalanceOwedAfter = $delivery->balance_owed;

            //$this->deleteOverCommission($delivery,$repayment);

            $commission = $delivery->chargeCommission()->where('waybill_status',false)->first();
            if ($commission){
                $balance-=$sum;
                if ($type === 'commission'){
                    if ($sum < $commission->fixed_charge){//частичное погашение коммисии
                        $dailyFixed = $sum;
                        $commission->fixed_charge = $commission->fixed_charge - $sum;
                        $sum=0;
                        $message = 'Коммиссии по накладной '.$delivery->waybill.' погашено частичны';
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
                    }
                }
                $commission->without_nds = $commission->fixed_charge + $commission->percent + $commission->udz + $commission->deferment_penalty;
                $commission->nds = $commission->fixed_charge_nds + $commission->percent_nds + $commission->udz_nds + $commission->deferment_penalty_nds;
                $commission->with_nds = $commission->without_nds + $commission->nds;
                $commission->debt = $commission->with_nds;
                if ($commission->debt <= 0){
                    $commission->date_of_repayment = $repayment->date;
                    $commission->waybill_status = true; 
                }
                $commission->save();

                array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]);

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
                    'dayliFirstPaymentDebtAfter' => $dayliFirstPaymentDebtAfter
                ];
                $this->createDailyCharge($dailyArray,$delivery->id,$repayment->id,true);
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
        $repayments = Repayment::OrderBy('date')->get();
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

        $repayments = Repayment::OrderBy('date')->get();
        $view = view('repayment.tableRepaymentRow',['repayments' => $repayments])->render();

        return ['view' => $view,'data' => ['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]];
    }

    public function recalculateDailyCharge($repayment,$deliveryId){
        $allDailyArray =[];
        $nds = 18;
        $dateNow = new Carbon(date('Y-m-d'));
        $dateOfRepayment = new Carbon($repayment->date);

        $delivery = Delivery::find($deliveryId);
        $relation = $delivery->relation;//связь
        $tariff = $relation->tariff;//тарифы

        $dateOfRepaymentClone = clone $dateOfRepayment;
        $dateOfFunding = new Carbon($delivery->date_of_funding);  

        $percent_commission = $tariff->commissions->where('type','finance')->first();
        $udz_commission = $tariff->commissions->where('type','udz')->first();
        $penalty_commission = $tariff->commissions->where('type','peni')->first();

        $dateOfRecourse = new Carbon($delivery->date_of_recourse);

        $dateOfRepaymentDiff = $dateOfRepayment->diffInDays($dateNow,false);

        for($i=0;$i<$dateOfRepaymentDiff;$i++){
            $dailyArray = [];
            $dateNowVar = $dateOfRepaymentClone->addDays(1);

            $dateOfFundingDiffTest = $dateOfFunding->diffInDays($dateNowVar,false);
            $daysInYear = date("L", mktime(0,0,0, 7,7, $dateNowVar->year))?366:365; 
            $actualDeferment = $dateOfRecourse->diffInDays($dateNowVar,false);//Фактическая просрочка

            $dailyFixed = 0;
            $dailyFixedNds = 0;

            $dailyPercent = 0;
            $dailyPercentNds = 0;
  //        //Процент
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
                }else{
                    $dailyPercent = (($delivery->remainder_of_the_debt_first_payment / 100.00) * $percent);
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
                    //Ндс
                    if ($udz_commission->nds == true){
                       $dailyUdzNds = ($dailyUdz / 100.00) * $nds;
                    }
                }             
            }else{
               // var_dump('Коммиссии не найдено');
            }
     // //         //Пеня за просрочку
            
            $dailyDeferment = 0;
            $dailyDefermentNds = 0;
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
                'dailyWithNds' => $daily_with_nds,
                'dateNow' => $dateNowVar->format('Y-m-d')
            ];
            $this->createDailyCharge($dailyArray,$delivery->id,$repayment->id,false); 
        }//цикл
    }

    public function createDailyCharge($dailyArray,$deliveryId,$repaymentId,$handler){

        $daily = new DailyChargeCommission;
        $delivery = Delivery::find($deliveryId);
        $repayment = Repayment::find($repaymentId);
        $daily->delivery_id = $delivery->id;
        $daily->charge_commission_id = $delivery->chargeCommission->id;

        $daily->fixed_charge = $dailyArray['dailyFixed'];
        $daily->percent = $dailyArray['dailyPercent'];
        $daily->udz = $dailyArray['dailyUdz'];      
        $daily->deferment_penalty = $dailyArray['dailyDeferment'];

        $daily->nds = $dailyArray['dailyNds'];
        $daily->without_nds = $dailyArray['dailyWithoutNds'];
        $daily->with_nds = $dailyArray['dailyWithNds'];
        $daily->handler = $handler;

        $daily->fixed_charge_nds = $dailyArray['dailyFixedNds'];
        $daily->percent_nds = $dailyArray['dailyPercentNds'];
        $daily->udz_nds = $dailyArray['dailyUdzNds'];
        $daily->deferment_penalty_nds = $dailyArray['dailyDefermentNds'];

        if ($handler == true){
            $daily->created_at = $repayment->date;

            $daily->repayment_id = $repaymentId;
            $daily->repayment_sum = $dailyArray['dailyRepaymentSum'];
            $daily->first_payment_sum = $dailyArray['dayliFirstPaymentSum'];
            $daily->first_payment_debt_after = $dailyArray['dayliFirstPaymentDebtAfter'];
            $daily->first_payment_debt_before = $dailyArray['dayliFirstPaymentDebtBefore'];
            $daily->balance_owed_after = $dailyArray['dayliBalanceOwedAfter'];
            $daily->to_client = $dailyArray['dayliToClient'];
            $daily->type_of_payment = $dailyArray['dailyTypeOfPayment'];
        }else{
            $daily->created_at = $dailyArray['dateNow'];
        }

        $daily->save();
    }

    public function ClearTable(){
        DailyChargeCommission::Truncate();
        //ChargeCommission::Truncate();
        Bill::Truncate();
        //Repayment::Truncate();
        DeliveryToFinance::Truncate();
        //Finance::Truncate();
        $repayments = Repayment::OrderBy('date')->get();
        foreach ($repayments as $repayment) {
            $repayment->balance = $repayment->sum;
            $repayment->save();
        }
        return ['callback' => 'success','message'=>'Успешно','message_shot'=>'Таблицы очищены'];
    }

    public function deleteOverCommission($delivery,$repayment){
	 	DailyChargeCommission::where('handler',false)
                            ->where('delivery_id','=',$delivery->id)
                            ->whereDate('created_at','>',$repayment->date)
                            ->delete();

        $dayliChargeCommission = DailyChargeCommission::where('handler',false)
                                ->where('delivery_id','=',$delivery->id)
                                ->get();
        $dayliPaymentCommission = DailyChargeCommission::where('handler',true)
                                ->where('delivery_id','=',$delivery->id)
                                ->get();

        $commission = $delivery->chargeCommission()->where('waybill_status',false)->first();
        if ($commission){
            $commission->fixed_charge = $dayliChargeCommission->sum('fixed_charge') - $dayliPaymentCommission->sum('fixed_charge');
            $commission->fixed_charge_nds = $dayliChargeCommission->sum('fixed_charge_nds') - $dayliPaymentCommission->sum('fixed_charge_nds');
            $commission->percent = $dayliChargeCommission->sum('percent') - $dayliPaymentCommission->sum('percent');
            $commission->percent_nds = $dayliChargeCommission->sum('percent_nds') - $dayliPaymentCommission->sum('percent_nds');
            $commission->udz = $dayliChargeCommission->sum('udz') - $dayliPaymentCommission->sum('udz');
            $commission->udz_nds = $dayliChargeCommission->sum('udz_nds') - $dayliPaymentCommission->sum('udz_nds');
            $commission->deferment_penalty = $dayliChargeCommission->sum('deferment_penalty') - $dayliPaymentCommission->sum('deferment_penalty');
            $commission->deferment_penalty_nds = $dayliChargeCommission->sum('deferment_penalty_nds') - $dayliPaymentCommission->sum('deferment_penalty_nds');
            $withiut_nds = $commission->fixed_charge + $commission->percent + $commission->udz + $commission->deferment_penalty;
            $nds = $commission->fixed_charge_nds + $commission->percent_nds + $commission->udz_nds + $commission->deferment_penalty_nds;
            $with_nds = $withiut_nds + $nds;
            $commission->without_nds = $withiut_nds;
            $commission->nds = $nds;
            $commission->with_nds = $with_nds;
            $commission->debt = $with_nds;
            $commission->save();
        }
	}

    public function getDebtor(){
        $clientId = Input::get('clientId');
        $client = Client::find($clientId);
        $debtor = [];
        foreach ($client->relations as $relation){
            array_push($debtor,$relation->debtor()->select('name','id')->first());
        }
        return $debtor;
    }
}	
