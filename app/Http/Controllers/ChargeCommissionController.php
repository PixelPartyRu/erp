<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use IlluminateDatabaseEloquentModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Artisan;

use Carbon\Carbon;
use App\ChargeCommission;
use App\CommissionsRage;
use App\Finance;

class ChargeCommissionController extends Controller
{
    public function index()
    {		
    	$commissions = ChargeCommission::where('without_nds','!=',0)->get();
    	//$commissions = ChargeCommission::all();
    	return view('chargeCommission.index',['commissions' => $commissions]);
    }

    public function store(){
    	$financeArray = Input::get('financeArray');

    	foreach ($financeArray as $financeId){
    		
    		$nds = 18;
    		$daysInYear = date('L')?366:365;
    		$finance = Finance::find($financeId);
    		$deliveries = $finance->deliveries;

    		if (count($deliveries) != 0){
    			foreach ($deliveries as $delivery){
	    			$commission = new ChargeCommission;
	    			$relation = $delivery->relation;//связь
	    			$tariff = $relation->tariff;//тарифы
		    		$commission->client = $delivery->client_id;
		    		$commission->debtor = $delivery->debtor_id;
		    		$commission->registry = $delivery->registry;
		    		$commission->waybill = $delivery->waybill;
		    		$commission->date_of_waybill = $delivery->date_of_waybill; 
		    		$commission->waybill_status = $delivery->state;
		    		$commission->date_of_funding = $delivery->date_of_funding;
		    		$commission->delivery_id = $delivery->id;

		    		//Дней со дня финансирования
		    		$dateNowVar = new Carbon(Carbon::now());//Сегодняшнее число
		    		$dateNowVar->addDays(1);
		    		$dateOfFunding = new Carbon($delivery->date_of_funding);	
		    		$dateOfFundingDiff = $dateOfFunding->diffInDays($dateNowVar,false);

		    		//Фиксированный сбор
		    		$fixed_charge_w_nds = 0;
		    		$fixed_charge_nds = 0;
		    		$commission->fixed_charge = $fixed_charge_w_nds;//--------------------

		    		//Процент
		    		$percent_w_nds = 0;
		    		$percent_nds = 0;
		    		$commission->percent = $percent_w_nds;//----------------------------

		   //  		//udz
		    		$udz_w_nds = 0;
		    		$udz_nds = 0;
		    		$commission->udz = $udz_w_nds;//-------------------------

		    		//Пеня за просрочку
		    		$penalty_w_nds = 0;
		    		$penalty_nds = 0;
		    		$commission->deferment_penalty = $penalty_w_nds;//----------------------------------
			    	
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
		    		//return $commission;
				}
    		}
    	}
    	
    }

    public function recalculationTest(){

    	Artisan::call('Recalculation', ['dateTest' => Input::get('dateTest')]);
    	return Redirect::to('chargeCommission');

    }
}
