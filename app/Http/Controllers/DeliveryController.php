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
use App\Relation;
use App\Client;
use App\Debtor;
use Session;

class DeliveryController extends Controller
{
    public function index()
    {   
     	$deliveries = Delivery::all();
     	$clients = Client::orderBy('name','ASC')->get();
     	$debtors = Debtor::orderBy('name','ASC')->get();
     	$dateToday = Carbon::now()->format('Y-m-d');
       	return view('delivery.index',['deliveries' => $deliveries,'clients' => $clients,'debtors' => $debtors, 'dateToday' => $dateToday]);
    }

    public function store(){
		//Session::start();
	
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
			// var_dump($resultArray[10]);
			$clientInn = $resultArray[3][10];
			$clientName = $resultArray[3][5];
			$debtorInn = $resultArray[4][7];
			$debtorName = $resultArray[4][2];
			$contractCode = strval($resultArray[5][7]);
			$contractDate = $resultArray[6][9];
			$registryVar = $resultArray[1][6];
			$client = Client::where('inn','=',$clientInn)->first();
							  
			$debtor = Debtor::where('inn','=',$debtorInn)->first();
							  
			$registryDelivery = Delivery::where('registry','=',$registryVar)->first();
			
			if ($registryDelivery === null){
				if($client){
					if($debtor){
						$clientId = $client->id;
						$debtorId = $debtor->id;
						$relation = Relation::where('client_id',$client->id)
											  ->where('debtor_id',$debtor->id)
											  ->first();
						if ($relation){
							$contract = $relation->contract;
							if ($contract){

								$contractCreatedAt = $contract->created_at->format('Y-m-d');
								$contractCode = $contract->code;
								if($contractCode === $contractCode and $contractCreatedAt === $contractDate){
									//Code
									$row = 0;
									$stop = 0;
									$i = 10;
									while ($stop === 0){
										if($resultArray[$i][1] === 'накладная'){
											$waybillDateVar = new Carbon($resultArray[$i][3]);//Дата накладной
											$waybillVar = strval($resultArray[$i][2]);//Накладная
											$invoiceDateVar = new Carbon($resultArray[$i + 1][3]);//Дата счет фактуры
											$waybillExist = $relation->deliveries->where('waybill',$waybillVar)
																				 ->where('date_of_waybill',$waybillDateVar->format('Y-m-d'))
																				 ->first();
											if ($waybillExist === null){
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
									            $delivery->waybill = $waybillVar;
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
									            $delivery->registry = $registryVar;
									            $delivery->date_of_registry = $resultArray[1][3];
									            //$delivery->date_of_funding = ;
									            //$delivery->end_date_of_funding = $dateNowVar->format('Y-m-d');;//(ложь)
									            $delivery->notes = $resultArray[$i][6];
									            $delivery->return = "";
									            $delivery->status = 'Зарегистрирована';
									            $delivery->state = false;
									            $delivery->the_presence_of_the_original_document = Input::get('the_presence_of_the_original_document');
									            if($relation->confedential_factoring)
									            	$delivery->type_of_factoring = $relation->confedential_factoring;
									            else{
									            	$delivery->type_of_factoring = false;
									            }

									            if (!$delivery->save()){
									            	var_dump('save');
									            };
									        }else{
									        	//накладная с таким номером существует
									        }
											$i = $i + 2;
										}else{
											$stop = 1;
										}
									}
									return Redirect::to('delivery');
								}else{
									var_dump('Номер или дата договора не совпадают');
								}
							}else{
								var_dump('Контракт не существует');
							}
						}else{
							var_dump('Связь между клиентом и дебитором не найдена');
						}
					}else{
						var_dump('Дебитор с таким ИНН не найден');
					}
				}else{
					var_dump('Клиент с таким ИНН не найден');
				}
			}else{
				var_dump('Реестр с таким номером уже существует');
			}

    	}else{
    		//return Redirect::to('delivery');
    		var_dump('Файл не был загружен');
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
				$delivery->status = 'Не верифицирована';
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

    public function deliveryDelete(){
    	$array = Input::get('deleteArray');
    	$handlerDelete = 0;
    	$handlerNotDelete = 0;
    	foreach ($array as $val) {
    		$delievery = Delivery::find($val);
    		if ($delievery->status != 'Зарегистрирована'){
    			$handlerNotDelete = 1;
    		}else{
    			$handlerDelete = 1;
    			$delievery->delete();
    		}
    	}
    	return [$handlerDelete,$handlerNotDelete];

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

     	$deliveryFilterDateHandler = Input::get('deliveryFilterDateHandler');
     	$deliveryFilterDateChoice = Input::get('deliveryFilterDateChoice');
     	$deliveryFilterDateStart = Input::get('deliveryFilterDateStart');
     	$deliveryFilterDateFinish = Input::get('deliveryFilterDateFinish');
     	$arratBetween = [$deliveryFilterDateStart, $deliveryFilterDateFinish];
		
		$q_delvery = Delivery::query();
     	$q_delvery->whereIn('status', $deliveryFilterStatusArray)
	              ->whereIn('state', $deliveryFilterStateArray)
				  ->whereIn('registry', $deliveryFilterRegitry)  
	              ->whereIn('client_id', $deliveryFilterClient)
	              ->whereIn('debtor_id', $deliveryFilterDebtor);
				  
		if ($deliveryFilterDateHandler == 'true'){
     		if($deliveryFilterDateChoice == '1'){
                  $q_delvery->whereBetween('date_of_registry',$arratBetween);                       
     		}
     		elseif($deliveryFilterDateChoice == '2'){
				$q_delvery->whereBetween('date_of_waybill',$arratBetween);
     		}else{
	              $q_delvery->whereBetween('date_of_funding',$arratBetween);
     		}
     	}
		if(Input::get('count') == 'true'){
			$count = $q_delvery->count();                                 	            
			return $count;
		
		}else{
			$deliveries = $q_delvery->get();                      	            
			return view('delivery.deliveryTable',['deliveries' => $deliveries]);
		}
     }
}


