<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use App\ChargeCommissionView;
use App\Repayment;
use Carbon\Carbon;


class InvoicingController extends Controller
{
    public function index(Request $request)
    {   
        Carbon::setLocale('ru');
        $dt = Carbon::now();
        if(Input::get('month') != NULL){
        	$filterDate=strtotime(Input::get('year').'.'.Input::get('month'));
        	$days_in_month = cal_days_in_month(CAL_GREGORIAN, Input::get('month'), Input::get('year'));
        	$bills = ChargeCommissionView::whereYear('date_of_funding', '=',Input::get('year'))->whereMonth('date_of_funding', '=',Input::get('month'))->get() ;
        }
    	$clients =ChargeCommissionView::select('client_id')->distinct()->get();
    	if($request->ajax())
            return view('invoicing.indexAjax', ['bills' => $bills]);
        else
		    return view('invoicing.index', ['clients'=>$clients,'dt'=>$dt]); 
    }
}
