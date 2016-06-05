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
use App\DeliveryToFinance;
use App\Finance;
use App\Bill;
use Session;

class FinanceController extends Controller
{
    public function index()
    {   
    	
		$finances = Finance::all();
    $sum = Finance::sum('sum');
		$dateToday = Carbon::now()->format('Y-m-d');
       	return view('finance.index',['finances' => $finances,'dateToday' => $dateToday,'sum'=>$sum]);
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
  			$stek[$i][1] = $delivery->first_payment_amount;
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
              $keyStek = [];

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
        foreach ($keyStek as $key){
          $deliveryToFinance = new DeliveryToFinance;
          $deliveryToFinance->delivery_id = $key;
          $deliveryToFinance->finance_id = $finance->id;
          $deliveryToFinance->save();
        }
      }
    }

    public function financingSuccess(){
        $financeArray = Input::get('financeArray');
        $financingDate = Input::get('financingDate');
        $fundingDate = new Carbon($financingDate);

        $lastBill = Bill::orderBy('bill_date','desc')->first();
        if (count($lastBill) > 0){
        	$lastBillCarbon = new Carbon($lastBill->bill_date);
            $diffBillsDate = $lastBillCarbon->diffInDays($fundingDate,false);
        }else{
        	$diffBillsDate = 1;
        }
        
        $messageArray = [];
        foreach ($financeArray as $key){
          $finance = Finance::find($key);
          $registryDate = new Carbon($finance->date_of_registry);
          
          $diffDate = $registryDate->diffInDays($fundingDate,false);

          if ($diffBillsDate > 0){
            if ($diffDate >= 0){
              if ($finance->type_of_funding === 'Первый платеж'){
                $deliveryToFinances = $finance->deliveryToFinance;
                //проверка лимита
                $relation = $deliveryToFinances->first()->delivery->relation;
                $usedLimit = 0;
                $usedLimit = $relation->deliveries()->where('state',false)->where('status','Профинансирована')->sum('balance_owed');

                $limit = $relation->limit;
                if ($limit) {
                  $limitValue = $limit->value;
                  $freeLimit = $limitValue - $usedLimit;
                  if ($finance->sum <= $freeLimit){
                    $finance->date_of_funding = $financingDate;
                    $finance->status = 'Подтверждено';
                    if ($finance->save()){
                      foreach($deliveryToFinances as $deliveryToFinance){
                        $delivery = $deliveryToFinance->delivery;
                        $delivery->date_of_funding = $financingDate;
                        $delivery->status = 'Профинансирована';
                        // $delivery->stop_commission = true;
                        $delivery->save();
                      } 
                    }
                    $callback = 'success';
                    $messageShot = 'Успешно!';
                    $message = 'Финансирование для клиента '.$finance->client.' подтверждено';
                    array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot,'type'=>true, 'data'=>$key]);
                  }else{
                    $callback = 'danger';
                    $messageShot = 'Ошибка!';
                    $message = 'Превышен лимит для связи. Клиент: '.$relation->client->name.' и Дебитор: '.$relation->debtor->name;
                    array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]);
                  }
                }else{
                  $callback = 'danger';
                  $messageShot = 'Ошибка!';
                  $message = 'Лимит для связи не найден. Клиент: '.$relation->client->name.' и Дебитор: '.$relation->debtor->name;
                  array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]);
                }
              }else{
                  $finance->date_of_funding = $financingDate;
                  $finance->status = 'Подтверждено';
                  $finance->save();
                  $callback = 'success';
                  $messageShot = 'Успешно!';
                  $message = 'Финансирование для клиента '.$finance->client.' подтверждено';
                  array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot,'type' => false]);
              }
            }else{
                  $callback = 'danger';
                  $messageShot = 'Ошибка!';
                  $message = 'Дата реестра превышает дату финансирования';
                  array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]);
            }
          }else{
            $callback = 'danger';
            $messageShot = 'Ошибка!';
            $message = 'Финансирование поставки в закрытом месяце- запрещено!';
            array_push($messageArray,['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot]);
          }
        }
        
        return $messageArray;
    }

    public function getSum(){
      $financeId = Input::get('financeFormId');
      $finance = Finance::find($financeId);
      return $finance->sum;
    }

     public function getDeliveries(){
        $financeId = Input::get('financeFormId');
        $finance = Finance::find($financeId);
        $financeToDeliveries = $finance->deliveryToFinance;
        return view('finance.deliveryTemplate',['financeToDeliveries' => $financeToDeliveries]);
     }

     public function getFinances(){
        $arrayId = Input::get('data');
        $finances = [];
        $messageArray = [];
        if (count($arrayId) > 0){
          foreach ($arrayId as $id) {
            $finance = Finance::find($id);
            if ($finance->status != 'Подтверждено'){
              array_push($finances,$finance);
            }
          }
          if (count($finances) > 0) {
            $callback = 'success';
            $view = view('finance.modalTableRow',['finances' => $finances])->render();
            $messageArray = ['callback' => $callback,'view' => $view];
          }else{
              $callback = 'danger';
              $messageShot = 'Ошибка!';
              $message = 'Выберите хотя бы одно не подтвержденное финансирование';
              $messageArray = ['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot];
          }
        }else{
          $callback = 'danger';
          $messageShot = 'Ошибка!';
          $message = 'Выберите финансирование';
          $messageArray = ['callback' => $callback,'message'=>$message,'message_shot'=>$messageShot];
        }
        return $messageArray;
     }

     public function filter(){

        $filterStatus = Input::get('filterStatus');
  
        if (Input::get('filterArrayType')){
          $filterArrayType = Input::get('filterArrayType');
        }else{
          $filterArrayType=array();
        }
		
        $q_finance = Finance::query();
		
    		if(count($filterArrayType) != 0){
                $q_finance = $q_finance->whereIn('type_of_funding', $filterArrayType);
            }
    		if($filterStatus != '0'){
                $q_finance = $q_finance->where('status', '=', $filterStatus);                  
            }
    		$q_sum = clone $q_finance;
    		
    		$sum = $q_sum->sum('sum');
    		
    		$finances = $q_finance->get();
        
        return view('finance.tableRow',['finances' => $finances,'sum'=>$sum]);
     }
}
//
