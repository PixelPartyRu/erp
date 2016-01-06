<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use IlluminateDatabaseEloquentModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Delivery;
use App\Finance;
class FinanceController extends Controller
{
    public function index()
    {   
    	$finances = Finance::all();
    	$dateToday = Carbon::now()->format('Y-m-d');
       	return view('finance.index',['finances' => $finances,'dateToday' => $dateToday]);
    }

    public function store()
    {   
      	$idDeliveryArray = Input::get('arrayFinance');
      	$stek = [];
      	$sum = 0;
      	$waybillCount = 0;
      	$i = 0;
       	foreach ($idDeliveryArray as $id){
       		$delivery = Delivery::find($id);
			$stek[$i][0] = $delivery->client->name;
			$stek[$i][1] = $delivery->waybill_amount;
			$stek[$i][2] = $delivery->registry;
			$stek[$i][3] = $delivery->date_of_registry;
			$stek[$i][4] = $delivery->id;
			$i++;
       	}

       	$size = count($stek);
       	if ($size > 1){
	       	usort($stek,function($a, $b){
	       		return $a[2] - $b[2];
	       	});
	    }

	    $keyStek = [];
       	$client = $stek[0][0];
   		$sum = (float)$stek[0][1];
   		$number_of_waybill = 1;
   		$registry = $stek[0][2];
   		$date_of_registry = $stek[0][3];
   		array_push($keyStek,$stek[0][4]);
   		if ($size > 1){
	       	for ($i=1; $i<$size; $i++){
	       		if($registry === $stek[$i][2]){
		       		$sum = $sum +(float)$stek[$i][1];
		       		$number_of_waybill++; 
		       		array_push($keyStek,$stek[$i][4]);

		       		if ($i === $size - 1){
		       			$this->saveFinance($client,$sum,$number_of_waybill,$registry,$date_of_registry,$keyStek);
		       		}
	       		}else{
		       		$this->saveFinance($client,$sum,$number_of_waybill,$registry,$date_of_registry,$keyStek);

		       		$size = count($stek);
			       	$client = $stek[$i][0];
			   		$sum = (float)$stek[$i][1];
			   		$number_of_waybill = 1;
			   		$registry = $stek[$i][2];
			   		$date_of_registry = $stek[$i][3];
			   		array_push($keyStek,$stek[$i][4]);

			   		if ($i === $size - 1){
		       			$this->saveFinance($client,$sum,$number_of_waybill,$registry,$date_of_registry,$keyStek);
		       		}
		       	}	    
		    }
	    }else{
	    	$this->saveFinance($client,$sum,$number_of_waybill,$registry,$date_of_registry,$keyStek);
       	}
	}

    protected function saveFinance($client,$sum,$number_of_waybill,$registry,$date_of_registry,$keyStek){
    	$finance = new Finance;
   		$finance->client = $client;
   		$finance->sum = $sum;
   		$finance->number_of_waybill = $number_of_waybill;
   		$finance->type_of_funding = "Первый платеж";
   		//$finance->date_of_funding
   		$finance->registry = $registry;
   		$finance->date_of_registry = $date_of_registry;
   		$finance->status = "К финансированию";
   		if ($finance->save()){
   			$this->saveKey($keyStek);
   			$keyStek = [];
   		};
    }

    protected function saveKey($keyStek){
    	if (Finance::max('id')){
   			$financeMaxId = Finance::max('id');
   		}else{
   			$financeMaxId = 1;
   		}
   		
   		foreach ($keyStek as $key){
   			$deliveryFinanceId = Delivery::find($key);
   			$deliveryFinanceId->finance_id = $financeMaxId;
   			$deliveryFinanceId->save();
   		}
    }

    public function financingSuccess(){
        $financeArray = Input::get('financeArray');
        $financingDate = Input::get('financingDate');
        foreach ($financeArray as $key){
            $finance = Finance::find($key);
            $finance->date_of_funding = $financingDate;
            $finance->status = 'Профинансировано';
            $finance->save();

            $deliveries = $finance->deliveries;
            foreach($deliveries as $delivery){
              $delivery->date_of_funding = $financingDate;
              $delivery->status = 'Профинансировано';
              $delivery->save();
            }
        }
    }
}
//
