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
use Carbon\Carbon;
use App\Finance;

class RepaymentController extends Controller
{
    public function index(){
        $repayments = Repayment::all();
        $clients = Client::all();
        $debtors = Debtor::all();
    	return view('repayment.index',['repayments' => $repayments, 'clients' => $clients, 'debtors' => $debtors]);
    }

    public function store(){
             // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'paymentNumber'  => 'required',
            'paymentSum'       => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            
            return Redirect::to('repayment')
                ->withErrors($validator);
        } else {
                $repayment = new Repayment;
                $repayment->number = Input::get('paymentNumber');
                $repayment->date = new Carbon(Input::get('paymentDate'));

                if(Input::get('info')){
                    $repayment->info = Input::get('info');
                }
                $repayment->sum = Input::get('paymentSum');
                $repayment->balance = Input::get('paymentSum');
                $repayment->purpose_of_payment = Input::get('textarea');

                $repayment->inn = Input::get('inn');
               
                if (Input::get('radioCreate') == '1'){
                     $repayment->client_id = Input::get('clientPayerCreate');
                     $repayment->type = 1;
                }else{
                    $clientId = Input::get('clientPayerCreate');
                    $debtorId = Input::get('debtorPayerCreate');
                    $clientInn = Client::where('inn','=',Input::get('inn'))->get();
                    $debtorInn = Debtor::where('inn','=',Input::get('inn'))->get();
                    if ($clientInn != null){
                        $repayment->type = -1;
                    }else{
                        $repayment->type = 0;
                    }
                    $repayment->client_id = $clientId;
                    $repayment->debtor_id = $debtorId;
                    
                }
                //return $repayment;
                $repayment->save();
                // redirect
               /* Request::flashOnly('message', 'Клиент добавлен');*/
             return Redirect::to('repayment');           
        }
    }

    public function createStore(){
        $rules = array(
            'paymentNumber'  => 'required',
            'paymentSum'       => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            
            return Redirect::to('repayment')
                ->withErrors($validator);
        } else {
                $repayment = new Repayment;
                $repayment->number = Input::get('paymentNumber');
                $repayment->date = new Carbon(Input::get('paymentDate'));

                if(Input::get('info')){
                    $repayment->info = Input::get('info');
                }
                $repayment->sum = Input::get('paymentSum');
                $repayment->balance = Input::get('paymentSum');
                $repayment->purpose_of_payment = Input::get('textarea');

                $client = Client::find(Input::get('clientPayerCreate'));
                $debtor = Debtor::find(Input::get('debtorPayerCreate'));
                if (Input::get('radioCreate') === '1'){
                     $repayment->client_id = Input::get('clientPayerCreate');
                     $repayment->debtor_id = Input::get('debtorPayerCreate');

                     if (Input::get('debtorPayerCreateRadio') === '1'){  
                        $repayment->inn = $client->inn;
                        $repayment->type = -1;
                     }else{
                        $repayment->inn = $debtor->inn;
                        $repayment->type = 0;
                     }
                }else{
                    $repayment->client_id = Input::get('clientPayerCreate');
                    $repayment->inn = $client->inn;
                    $repayment->type = 1;
                }
                $repayment->save();
                // redirect
               /* Request::flashOnly('message', 'Клиент добавлен');*/
            return Redirect::to('repayment');           
        }
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
        $repayment = Repayment::find(Input::get('id'));

        if ($repayment->type == 1){
            $client = $repayment->client;
			$cor = $repayment->client;
        }elseif($repayment->type === 0){
            $client = $repayment->debtor;
			$cor = $repayment->client;
        }else{
            // $relation = Relation::where('client_id',$repayment->client_id)
            //                       ->where('debtor_id',$repayment->debtor_id)
            //                       ->first();
            $client = $repayment->client;
			$cor = $repayment->debtor;
        }
        return view('repayment.repaymentModalContent',['repayment' => $repayment, 'client' => $client,'cor'=>$cor]);
    }

    public function getDelivery(){
        $repayment = Repayment::find(Input::get('repaymentId'));
        $handler = Input::get('dataVar');
        if ($handler === 'delivery'){

            $deliveries = $this->getDeliveries($repayment);
            return view('repayment.repaymentModalTableDelivery',['deliveries' => $deliveries]);
        }elseif($handler === 'commission'){
            return 'Здесь будут погашения вознаграждений';
        }else{
            // $bool = true;
            // $deliveries = $this->getDeliveries($repayment);
            // return view('repayment.repaymentModalTableDelivery',['deliveries' => $deliveries, 'bool' => $bool]);
            return 'Здесь будут погашенные накладные';
        }
    }

    public function getDeliveries($repayment){
        if ($repayment->type === 1){
            $deliveries = $repayment->client->deliveries()
                                    ->where('status','=','Профинансирована')
                                    ->where('state','=',false)
                                    ->get();
        }elseif($repayment->type === 0){
            $client = $repayment->debtor;
            $deliveries = $repayment->debtor->deliveries()
                                    ->where('status','=','Профинансирована')
                                    ->where('state','=',false)
                                    ->get();
        }else{
            $clientVar = $repayment->client->id;
            $debtorVar = $repayment->debtor->id;
            $relation = Relation::where('client_id','=',$clientVar)
                                  ->where('debtor_id','=',$debtorVar)
                                  ->first();
            $deliveries = $relation->deliveries()
                                    ->where('status','=','Профинансирована')
                                    ->where('state','=',false)
                                    ->get();
        }
        return $deliveries;
    }

    protected function getThisDeliveries(){}

    function getDeliverySum(){
        $sum = 0;
        $repayment = Repayment::find(Input::get('repaymentId'));
        $balance = $repayment->balance;
        $array = Input::get('data');

        foreach ($array as $id) {
            $delivery = Delivery::find($id);
            $sum = $sum + $delivery->balance_owed;
        }

        if ($balance > $sum){
            return 1;
        }else{
            return 0;
        }
        
    }

    public function repayment(){
        $repayment = Repayment::find(Input::get('repaymentId'));
        $balance = $repayment->balance;
        $deliveries = Input::get('delivery');
        foreach ($deliveries as $id){
            $delivery = Delivery::find($id);
            $first = $delivery->remainder_of_the_debt_first_payment;
            if ($balance >= $first){
                $delivery->balance_owed = $delivery->balance_owed - $first;
                $balance = $balance - $first;
                $delivery->remainder_of_the_debt_first_payment = 0;
                $delivery->state = true;
                $delivery->end_date_of_funding = Carbon::now();
                
                $commission = $delivery->chargeCommission;
                if ($commission){
                    $debt = $commission->debt;
                    $balance -= $debt;
                    if ($delivery->balance_owed > $debt){
                        $delivery->balance_owed -= $debt;
                    }else{
                        $delivery->balance_owed = 0;
                    }
                    $commission->debt = 0;
                    $commission->date_of_repayment = Carbon::now();
                    $commission->waybill_status = true;
                    $commission->save();
                    //Second pay
                    if ($delivery->balance_owed > 0){
                        $finance = new Finance;
                        $finance->client = $delivery->client->name;
                        $finance->sum = $delivery->balance_owed;
                        $finance->number_of_waybill = 1;
                        $finance->type_of_funding = "Второй платеж";
                        //$finance->date_of_funding
                        $finance->registry = $delivery->registry;
                        $finance->date_of_registry = $delivery->date_of_registry;
                        $finance->status = "К финансированию";
                        
                        if ($finance->save()){
                            $delivery->finance_id = $finance->id;
                        } 
                    }
                }else{
                    //коммиссии не найдено
                }
            }else{//частично
                $delivery->balance_owed = $delivery->balance_owed - $balance;
                $delivery->remainder_of_the_debt_first_payment = $first - $balance;
                $balance = 0;
            }

            $delivery->save();
        }
        $repayment->balance = $balance;
        $repayment->save();
    }

    public function getDeliveryFirstPayment(){
        $id = Input::get('dataId');
        $delivery = Delivery::find($id);
        return $delivery->remainder_of_the_debt_first_payment;
    }
}
