<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\DailyChargeCommission;
use App\ChargeCommission;
use App\Client;
use App\Debtor;

class ReportRepaymentController extends Controller
{
    public function index(Request $request){
    	if($request->ajax()){
	    	$client_id = Input::get('filter-client');
			$debtor_id = Input::get('filter-debtor');
			$registry = Input::get('filter-registry');
			$choice = Input::get('filter-choice');

			$commissions = DailyChargeCommission::query();
    		$commissions->where('handler',true);

    		if ($client_id != 0){
    			$commissions->whereHas('delivery', function($q) use ($client_id)
				{
				    $q->where('client_id','=',$client_id);

				});
    		}

    		if ($debtor_id != 0){
    			$commissions->whereHas('delivery', function($q) use ($debtor_id)
				{
				    $q->where('debtor_id','=', $debtor_id);

				});
    		}

    		if ($registry != 0){
    			$commissions->whereHas('delivery', function($q) use ($registry)
				{
				    $q->where('registry','=',$registry);

				});
    		}

    		if ($choice != 0){
    			$before = Input::get('filter-before');
				$after = Input::get('filter-after');
    			$arratBetween = [$before, $after];

    			if ($choice == 1){
    				$commissions->whereHas('delivery', function($q) use ($arratBetween)
					{
					    $q->whereBetween('date_of_registry',$arratBetween);

					});
	    		}elseif($choice == 2){
					$commissions->whereBetween('created_at',$arratBetween);
	    		}
    		}

    		$commissions = $commissions->get();

    		return view('reportRepayment.tableRow',['commissions' => $commissions]);
        }else{
            $clients = Client::Where('active',true)->get();
	    	$debtors = Debtor::all();
	    	$debtors = Debtor::all();
	    	$registries = ChargeCommission::Distinct('registry')->lists('registry');
	        return view('reportRepayment.index',['clients' => $clients,'debtors' => $debtors,'registries' => $registries]);
        }  
    }
}
