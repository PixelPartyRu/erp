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
     	$registries = Delivery::orderBy('registry')->lists('registry', 'registry')->toArray();
       	return view('delivery.index',['registries' => $registries,'deliveries' => $deliveries,'clients' => $clients,'debtors' => $debtors, 'dateToday' => $dateToday]);
    }

    public function store(){
		//Session::start();
		//dd(Input::file('report'));
    	if (Input::file('report')){
    		$result = Excel::load(Input::file('report'), function($reader){
		   		$reader->setDateFormat('Y-m-d');
		   		$reader->toObject();
			},'UTF-8')->get();
			
		  	$resultNotJson = $result->each(function($sheet) {
			    $sheet->each(function($row) {
			    });
			});
			$resultArrayNull = json_decode($resultNotJson);
			$resultArrayVar = [];
			$resultArray = [];

			$deliveryCheck = 0;
			$invoicesCheck = 0;

			for ($i=0; $i < count($resultArrayNull); $i++){
				for ($j=0; $j < count($resultArrayNull[$i]); $j++){
					if (!empty($resultArrayNull[$i][$j])){
						if ($resultArrayNull[$i][$j] == 'Накладная' || $resultArrayNull[$i][$j] == 'накладная'){
							$deliveryCheck++;
						}
						if ($resultArrayNull[$i][$j] == 'Счет-фактура' || $resultArrayNull[$i][$j] == 'счет-фактура'){
							$invoicesCheck++;
						}
						array_push($resultArrayVar, $resultArrayNull[$i][$j]);
					}
				}
				if (count($resultArrayVar) > 0){
					array_push($resultArray,$resultArrayVar);
				}
				$resultArrayVar = [];
			}
			//dd($invoicesCheck);
			if ($deliveryCheck > 0 && $invoicesCheck > 0 && $deliveryCheck == $invoicesCheck){
				if (count($resultArray[0] === 4) && count($resultArray[1] === 4) && count($resultArray[2] === 4) && count($resultArray[3] === 2) && count($resultArray[4] === 4)){
					$clientInn = $resultArray[1][3];
					$clientName = $resultArray[1][1];
					$debtorInn = $resultArray[2][3];
					$debtorName = $resultArray[2][1];
					$contractCode = strval($resultArray[3][1]);
					$contractDate = new Carbon ($resultArray[4][2]);
					$registryVar = $resultArray[0][3];
					$registryDate = new Carbon ($resultArray[0][1]);
					$client = Client::where('inn','=',$clientInn)->first();			  
					$debtor = Debtor::where('inn','=',$debtorInn)->first();
								  
					$registryDelivery = Delivery::where('registry','=',$registryVar)
												->where('client_id','=',$client->id)
												->first();

					if ($registryDelivery === null){
						if($client){
							if($debtor){
								$clientId = $client->id;
								$debtorId = $debtor->id;
								$relation = Relation::where('client_id',$client->id)
													  ->where('debtor_id',$debtor->id)
													  ->whereHas('contract', function($q) use ($contractCode,$contractDate){
														    $q->where('code', '=', $contractCode);
														    $q->whereDate('created_at','=',$contractDate);
														})
													  ->first();
								if ($relation){
									$row = 0;
									$stop = 0;
									$i = 7;
									//dd($resultArray);
									while ($stop === 0){
										if(isset($resultArray[$i][1]) && ($resultArray[$i][1] == 'Накладная' || $resultArray[$i][1] == 'накладная')){
											$waybillDateVar = new Carbon($resultArray[$i][3]);//Дата накладной
											$waybillVar = strval($resultArray[$i][2]);//Накладная
											if (count($resultArray[$i + 1]) == 3){
												$invoiceDateVar = new Carbon($resultArray[$i + 1][2]);//Дата счет фактуры
										 		$invoiceVar = $resultArray[$i + 1][1];
											}elseif(count($resultArray[$i + 1]) == 2){
												if (strtotime($resultArray[$i + 1][1])){
													$invoiceDateVar = new Carbon($resultArray[$i + 1][1]);//Дата счет фактуры
										 			$invoiceVar = null;
												}else{
													$invoiceDateVar = null;//Дата счет фактуры
										 			$invoiceVar = $resultArray[$i + 1][1];
												}
											}else{
												$invoiceVar = null;
												$invoiceDateVar = null;
											}
											
											$sum = $resultArray[$i][5];
											$debtDate = $resultArray[$i][4];//не используется
											if (isset($resultArray[$i][6])){
												$notes = $resultArray[$i][6];
											}else{
												$notes = null;
											}
										 	
											$waybillExist = $relation->deliveries->where('waybill',$waybillVar)
																				 ->where('date_of_waybill',$waybillDateVar->format('Y-m-d'))
																				 ->first();
											if ($waybillExist === null){
												$dateOfRecourse = clone $waybillDateVar;
												$dateOfRecourse->addDays($relation->deferment);//Срок оплаты
												$dateNowVar = new Carbon(date('Y-m-d'));//Сегодняшнее число
												$actualDeferment = clone $dateNowVar;
												$dateOfRecourseClone = clone $dateOfRecourse;
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
									            $delivery->waybill_amount = $sum;
									            $rpp = $relation->rpp;
												$delivery->first_payment_amount = ($sum / 100.00) * $rpp;
									            $delivery->date_of_waybill = $waybillDateVar;
									            $delivery->due_date = $relation->deferment;
									            $delivery->date_of_recourse = $dateOfRecourse;//срок оплаты
									            //$delivery->date_of_payment = $dateNowVar->format('Y-m-d');//дата оплаты(ложь)
									            $delivery->date_of_regress = $dateOfRegress;
									            $delivery->the_date_of_termination_of_the_period_of_regression = $theDateOfTerminationOfThePeriodOfRegression; 
									            $delivery->the_date_of_a_registration_supply = $dateNowVar->format('Y-m-d');
												$delivery->the_actual_deferment = $actualDeferment;
									            $delivery->invoice = $invoiceVar;
									            $delivery->date_of_invoice = $invoiceDateVar;
									            $delivery->registry = $registryVar;
									            $delivery->date_of_registry = $registryDate;
									            //$delivery->date_of_funding = ;
									            //$delivery->end_date_of_funding = $dateNowVar->format('Y-m-d');;//(ложь)
									            $delivery->notes = $notes;
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
									            	Session::flash('success', 'Реестр успешно загружен');
									            };
									        }else{
									        	//накладная с таким номером существует
									        }
											$i = $i + 2;
										}else{
											$stop = 1;
										}
									}
								}else{
									Session::flash('danger', 'Связь между клиентом и дебитором, либо договор не найдены');
								}
							}else{
								Session::flash('danger', 'Дебитор с таким ИНН не найден');
							}
						}else{
							Session::flash('danger', 'Клиент с таким ИНН не найден');
						}
					}else{
						Session::flash('danger', 'Реестр с таким номером уже существует');
					}
				}else{
					Session::flash('danger', 'Заполните все поля реестра');
				}
			}else{
				Session::flash('danger', 'Проверьте наличие ключей \'Накладная\' и \'Счет-фактура\'');
			}
    	}else{
    		Session::flash('danger', 'Файл не был загружен');
    	}
		return Redirect::to('delivery');
    }

    public function verification(){
    	$verificationArray = Input::get('verificationArray');
    	$handler = Input::get('handler');
    	$messageArray = [];
    	$data = 0;
    	foreach ($verificationArray as $cell){
    		$delivery = Delivery::find($cell);
    		$status = $delivery->status;
    		if($handler == "verification"){
    			if (($status == 'Зарегистрирована') || ($status == 'Не верифицирована') || ($status == 'Отклонена')){
    				$delivery->status = 'Верифицирована';
					$delivery->save();
					$callback = 'success';
					$messageShot = 'Успешно!';
					$message = 'Накладная '.$delivery->waybill.' верифицирована';
    			}else{
    				$callback = 'danger';
					$messageShot = 'Ошибка!';
					$message = 'Накладная '.$delivery->waybill.' не может быть верифецирована';
    			}
    		}elseif($handler == "notVerification"){
    			if ($status == 'Зарегистрирована'){
    				$delivery->status = 'Не верифицирована';
					$delivery->save();
					$callback = 'success';
					$messageShot = 'Успешно!';
					$message = 'Накладная '.$delivery->waybill.' получила статус "не верифицирована"';
    			}else{
    				$callback = 'danger';
					$messageShot = 'Ошибка!';
					$message = 'Накладная '.$delivery->waybill.' не может получить статус "не верифицирована"';
    			}
    		}else{
    			$relation = $delivery->relation;
	            $usedLimit = 0;

				$usedLimit = $relation->deliveries()->where('state',false)->where('status','Профинансирована')->sum('balance_owed');
	            $limit = $relation->limit;
	            if ($limit) {
	                $limitValue = $limit->value;
	                $freeLimit = $limitValue - $usedLimit;
	                if ($delivery->balance_owed <= $freeLimit){
		    			if ($status == 'Верифицирована'){
		    				$delivery->status = 'К финансированию';
		    				$sum = $delivery->waybill_amount;
		    				$delivery->remainder_of_the_debt_first_payment = $delivery->first_payment_amount;
							$delivery->balance_owed = $sum;
							$delivery->save();
							$data = $delivery->id;
							$callback = 'success';
							$messageShot = 'Успешно!';
							$message = 'Накладная '.$delivery->waybill.' отправлена на финансирование';
		    			}else{
		    				$callback = 'danger';
							$messageShot = 'Ошибка!';
							$message = 'Накладная '.$delivery->waybill.' не может быть профинансирована';		
						}
		    		}else{
			    		$callback = 'danger';
						$messageShot = 'Ошибка!';
						$message = 'По накладной '.$delivery->waybill.' превышен лимит';
			    	}
		    	}else{
		    		$callback = 'danger';
					$messageShot = 'Ошибка!';
					$message = 'Отсутствует лимит по накладной '.$delivery->waybill;
		    	}
    		}
    		array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot,'data'=>$data]);
    	}

    	return $messageArray;
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
    	$messageArray = [];
    	foreach ($array as $val) {
    		$delievery = Delivery::find($val);
    		if ($delievery->status != 'Зарегистрирована'){
    			$callback = 'danger';
    			$messageShot = 'Ошибка!';
				$message = 'Накладная '.$delievery->waybill.' не может быть удалена.';
				array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot,'type' => false]);
    		}else{
    			$callback = 'success';
    			$messageShot = 'Успешно!';
				$message = 'Накладная '.$delievery->waybill.' успешно удалена.';
				array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot,'type' => false]);
    			$delievery->delete();
    		}
    	}
    	return $messageArray;


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

     public function getPopapDelivery(){
     	$data = Input::get('data');
     	$deliveries = [];
     	$messageArray = [];
   		$relationId = Delivery::find($data[0])->relation_id;
   		$stop = false;
     	foreach ($data as $id) {
     		$delivery = Delivery::find($id);
     		if ($delivery->relation_id != $relationId){
     			$stop = true;
     		}
     		array_push($deliveries,$delivery);
     	}
     	if ($stop == false){
     		$callback = 'success';
     		$messageShot = '';
			$message = '';
			$data = view('delivery.verificationModalRow',['deliveries' => $deliveries])->render();
     	}else{
     		$callback = 'danger';
     		$messageShot = 'Ошибка! ';
			$message = 'Выбраны поставки по разным связям!';
			$data = '';
     	}

     	$messageArray = ['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot,'data'=>$data];
     	return $messageArray;		
     }
}


