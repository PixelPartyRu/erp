<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use IlluminateDatabaseEloquentModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Delivery;
use App\Contract;
use App\Client;
use App\Debtor;

class DeliveryController extends Controller
{
    public function index()
    {   
     	$deliveries = Delivery::all();
     	$clients = Client::all();
     	$debtors = Debtor::all();
     	$dateToday = Carbon::now()->format('Y-m-d');
       	return view('delivery.index',['deliveries' => $deliveries,'clients' => $clients,'debtors' => $debtors, 'dateToday' => $dateToday]);
    }

    public function store(){
    	if (Input::file('report')){
    		$result = Excel::load(Input::file('report'), function($reader){
		   		$reader->setDateFormat('Y-m-d');
		   		$reader->toObject();
			},'UTF-8')->get();
		  	$resultNotJson = $result->each(function($sheet) {
			    $sheet->each(function($row) {
			    });
			});
			$resultArray = json_decode($resultNotJson);
			$clientInn = $resultArray[3][7];
			$clientName = $resultArray[3][2];
			$debtorInn = $resultArray[4][6];
			$debtorName = $resultArray[4][1];
			$contractName = $resultArray[5][2];
			$contractDate = $resultArray[6][2];
			$contract = Contract::where('code','=',$contractName)->first();
			if ($contract){
				$contractCreatedAt = $contract->created_at->format('Y-m-d');
				if ($contractCreatedAt ===  $contractDate){
					$relation = $contract->relation;
					$client = Client::find($relation->client_id);
					$debtor = Debtor::find($relation->debtor_id);
					if ($client->name === $clientName and $client->inn == $clientInn){
						if ($debtor->name === $debtorName and $debtor->inn == $debtorInn){
							$row = 0;
							$stop = 0;
							$i = 11;
							while ($stop === 0){
								if($resultArray[$i][1] === 'накладная'){
									$waybillDateVar = new Carbon($resultArray[$i][3]);//Дата накладной
									$invoiceDateVar = new Carbon($resultArray[$i + 1][3]);//Дата счет фактуры

									$dateOfRecourse = clone $waybillDateVar;
									$dateOfRecourse->addDays($relation->deferment);//Срок оплаты
									$dateNowVar = new Carbon(Carbon::now());//Сегодняшнее число
									$actualDeferment = clone $dateNowVar;
									$dateOfRecourseClone = clone $dateOfRecourse;
									$dateOfRecourseClone->addDays(1);//для включения в осрочку
									$actualDeferment = $dateOfRecourseClone->diffInDays($actualDeferment,false);//Фактическая просрочка
									$dateOfRegress = clone $dateOfRecourse;
									$dateOfRegress->addDays($relation->waiting_period);//Дата регресса
									$theDateOfTerminationOfThePeriodOfRegression = clone $dateOfRegress;
									$theDateOfTerminationOfThePeriodOfRegression->addDays($relation->regress_period);//Дата окончания регресса
									$delivery = new Delivery;
						            $delivery->client_id = $relation->client_id;
						            $delivery->debtor_id = $relation->debtor_id;
						            $delivery->relation_id = $relation->id;
						            $delivery->waybill = $resultArray[$i][2];
						            $delivery->waybill_amount = $resultArray[$i][5];
						            $delivery->first_payment_amount = ($resultArray[$i][5] / 100.00) * $relation->rpp;
						            $delivery->balance_owed = $resultArray[$i][5];//предварительно
						            $delivery->remainder_of_the_debt_first_payment = ($resultArray[$i][5] / 100.00) * $relation->rpp;//предварительно
						            $delivery->date_of_waybill = $waybillDateVar;
						            $delivery->due_date = $relation->deferment;
						            $delivery->date_of_recourse = $dateOfRecourse;//срок оплаты
						            //$delivery->date_of_payment = $dateNowVar->format('Y-m-d');//дата оплаты(ложь)
						            $delivery->date_of_regress = $dateOfRegress;
						            $delivery->the_date_of_termination_of_the_period_of_regression = $theDateOfTerminationOfThePeriodOfRegression; 
						            $delivery->the_date_of_a_registration_supply = $dateNowVar->format('Y-m-d');
									$delivery->the_actual_deferment = $actualDeferment;
						            $delivery->invoice = $resultArray[$i + 1][2];
						            $delivery->date_of_invoice = $invoiceDateVar;
						            $delivery->registry = $resultArray[1][6];
						            $delivery->date_of_registry = $resultArray[1][4];
						            //$delivery->date_of_funding = ;
						            //$delivery->end_date_of_funding = $dateNowVar->format('Y-m-d');;//(ложь)
						            $delivery->notes = $resultArray[$i][6];
						            $delivery->return = "";
						            $delivery->status = 'Зарегестрирована';
						            $delivery->state = false;
						            $delivery->the_presence_of_the_original_document = Input::get('the_presence_of_the_original_document');
						            $delivery->type_of_factoring = $relation->confedential_factoring;
						            $delivery->save();
									$i = $i + 2;
								}else{
									$stop = 1;
								}
							}
							return Redirect::to('delivery');
						}else{
							var_dump('Debtor');
						}
					}else{
						var_dump('Client');
					}
				}else{
					var_dump('Date');
				}
			}else{
				var_dump('Code');
			}
			
			// $resultArray[$i][2]; //накладная
			// $resultArray[$i][3]; //дата накладной
			// $resultArray[$i + 1][2]; //счет фактура
			// $resultArray[$i + 1][3]; //дата счет фактуры
			// $resultArray[$i][4]; //дата деб задолжности
			// $resultArray[$i][5]; //сумма уступки
			// $resultArray[$i][6]; //заметки

    	}else{
    		 return Redirect::to('delivery');
    	}
    }

    public function verification(){
    	$verificationArray = Input::get('verificationArray');
    	$handler = Input::get('handler');

    	if($handler == "verification"){
    		foreach ($verificationArray as $cell){
				$delivery = Delivery::find($cell);
				$delivery->status = 'Верифицирована';
				$delivery->save();
			}
    	}elseif($handler == "notVerification"){
    		foreach ($verificationArray as $cell){
				$delivery = Delivery::find($cell);
				$delivery->status = 'Неверифицирована';
				$delivery->save();
			}
    	}else{
    		foreach ($verificationArray as $cell){
				$delivery = Delivery::find($cell);
				$delivery->status = 'К финансированию';
				$delivery->save();
			}
    	}
		
    }

     public function destroy($id)
    {
        $delievery = Delivery::find($id);
        $delievery->delete();

        // redirect
        //Session::flash('message', 'Successfully deleted the nerd!');
        return Redirect::to('delivery');
    }

     public function getDescription(){
     	$idInput = Input::get('idInput');
     	$contract = Delivery::find($idInput)->relation->contract->description;
     	return $contract;
     }

     public function getFilterData(){
     	$deliveryFilterStatusArray = Input::get('deliveryFilterStatusArray');
     	$deliveryFilterState = Input::get('deliveryFilterState');
     	$deliveryFilterStateArray = [];
     	foreach ($deliveryFilterState as $cell) {
     		if ($cell === 'true'){
     			array_push($deliveryFilterStateArray,TRUE);
     		}else{
     			array_push($deliveryFilterStateArray,FALSE);
     		}
     	}
     	$deliveryFilterRegitry = Input::get('deliveryFilterRegitry');
     	$deliveryFilterClient = Input::get('deliveryFilterClient');
     	$deliveryFilterDebtor = Input::get('deliveryFilterDebtor');

     	$deliveries = Delivery::whereIn('status', $deliveryFilterStatusArray)
                                ->whereIn('state', $deliveryFilterStateArray)
                                ->whereIn('registry', $deliveryFilterRegitry)                   
                                ->whereIn('client_id', $deliveryFilterClient)
                                ->whereIn('debtor_id', $deliveryFilterDebtor)
                                ->get();
                                
                   	            
        return view('delivery.deliveryTable',['deliveries' => $deliveries]);
     }
}


