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
       return view('delivery.index',['deliveries' => $deliveries]);
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
									$delivery = new Delivery;
						            $delivery->client_id = $relation->client_id;
						            $delivery->debtor_id = $relation->debtor_id;
						            $delivery->waybill = $resultArray[$i][2];
						            $delivery->waybill_amount = $resultArray[$i][5];
						            $delivery->first_payment_amount = ($resultArray[$i][5] / 100.00) * $relation->rpp;
						            $delivery->balance_owed = $resultArray[$i][5];//предварительно
						            $delivery->remainder_of_the_debt_first_payment = ($resultArray[$i][5] / 100.00) * $relation->rpp;//предварительно
						            $delivery->date_of_waybill = $resultArray[$i][3];
						            $delivery->due_date = $relation->deferment;
						            $delivery->date_of_recourse = $resultArray[$i][3] + $relation->deferment;//срок оплаты
						            //$delivery->date_of_payment = ;//дата оплаты
						            $delivery->date_of_regress = $resultArray[$i][3] + $relation->deferment + $relation->waiting_period;
						            $delivery->the_date_of_termination_of_the_period_of_regression = $resultArray[$i][3] + $relation->deferment + $relation->waiting_period + $relation->regress_period;
						            $delivery->the_date_of_a_registration_supply = Carbon::now()->format('Y-m-d');
									$delivery->the_actual_deferment = Carbon::now()->format('Y-m-d') - $resultArray[$i][3] + $relation->deferment;
						            $delivery->invoice = $resultArray[$i + 1][2];
						            $delivery->date_of_invoice = $resultArray[$i + 1][3];
						            $delivery->registry = $resultArray[1][6];
						            $delivery->date_of_registry = $resultArray[1][4];

						            //$delivery->date_of_funding = Input::get('kpp');
						            //$delivery->end_date_of_funding = Input::get('inn');
						            $delivery->notes = $resultArray[$i][6];
						            //$delivery->return = Input::get('ogrn');
						            $delivery->status = 'Не верефицирована';
						            //$delivery->state = Input::get('ogrn');
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
}


