<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use IlluminateDatabaseEloquentModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

use App\DailyChargeCommission;
use App\ChargeCommission;
use App\Delivery;
use App\Repayment;
use App\Client;
use App\Bill;
use Carbon\Carbon;


class InvoicingController extends Controller
{
    public function index(Request $request)
    {   
        Carbon::setLocale('ru');
        $clients_filter=Client::whereHas('deliveries', function ($query) {
        $query->where('status', '=', 'Профинансирована');
    })->get();
         
        if($request->ajax()){
            $bills = Bill::where('id','>',0);
            if(Input::get('year')!=Null){
                $bills = $bills->whereYear('bill_date', '=', Input::get('year'));
            }
            if(Input::get('month')!=Null){
                $bills = $bills->whereMonth('bill_date', '=', Input::get('month'));
            }
            if(Input::get('client_id')!='all'){
                $bills = $bills->where('client_id','=',Input::get('client_id'));
            }
            $sum=Array();
            $sum['without_nds']=$bills->sum('without_nds');
            $sum['nds']=$bills->sum('nds');
            $sum['with_nds']=$bills->sum('with_nds');
            $bills = $bills->get();
            $clients = Client::All();
            $debts_full = array();
            $monthRepayment = array();
            $bill_date_first_day = Carbon::createFromDate(Input::get('year'), Input::get('month'), 1);
            foreach($clients as $client){
                foreach ($client->agreements as $agreement) {
                    $debt = 0;
                	foreach($agreement->relations as $relation){
                        if($agreement->account == FALSE){
                    		foreach ($relation->deliveries as $delivery) {
                                if($delivery->status=='Профинансирована')
                                {	//echo $client->name.": долг перед месяцем:";
                                    $pred_with_nds = $delivery->dailyChargeCommission()
                                        ->where('handler',false)
                                        ->whereDate('created_at', '<', $bill_date_first_day)
                                        ->sum('with_nds');
                                    // var_dump($pred_with_nds);
                                    // echo $client->name.": погашения:";
                                    $repayments = $delivery->dailyChargeCommission()
                                        ->where('handler',true)
                                        ->sum('with_nds');
                                    // var_dump($repayments);echo $client->name.": начисленные комиссии:";
                                    $with_nds_delivery = $delivery->dailyChargeCommission()
                                        ->where('handler',false)
                                        ->whereYear('created_at', '=', Input::get('year'))
                                        ->whereMonth('created_at', '=', Input::get('month'))
                                        ->sum('with_nds'); 
                                    // var_dump($with_nds_delivery);
                                    if($repayments>$pred_with_nds){
                                        if($repayments>=($with_nds_delivery+$pred_with_nds)){
                                            $debt+=0;
                                        }else{
                                            $debt+=$with_nds_delivery-($repayments-$pred_with_nds);
                                        }
                                    }else{
                                        $debt+=$with_nds_delivery;
                                    }
                                    // echo $client->name.": текущий долг:";
                                    // var_dump($debt);
                                    // echo "\n";
                                               
                                }
                            }
                        }else{
                            $debt=0;
                        }
                	}
                	// var_dump($debt);
                    $monthRepayment[$agreement->id] = $debt;
                }
            }
            // var_dump($monthRepayment);
            $stop=Delivery::where('stop_commission','=',true)->get();
            return view('invoicing.indexAjax', ['stop'=> $stop,'bills' => $bills,'debts_full'=>$debts_full,'monthRepayment'=>$monthRepayment,'sum'=>$sum]);
        }
        else{
            $dt = Carbon::now()->startOfMonth();
            $dates=DailyChargeCommission::select('created_at')->whereDate('created_at', '<', $dt)->orderBy('created_at','desc')->groupBy('created_at')->get();
            $year='';
            $month='';
            $i_month = 0;
            $i_year=0;
            $dates_for_filter=array();
            foreach ($dates as $date) {
                $dates_for_filter[$date->created_at->year][$date->created_at->month] = $date->created_at->month;
            }
            return view('invoicing.index', ['clients'=>$clients_filter,'dt'=>$dates_for_filter]); 
        }
    }
    public function store(){
       	
        $rules = array(
            'year'  => 'required',
            'month'       => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);
        // process the login
        if ($validator->fails()) {
            
            return Redirect::to('invoicing')
                ->withErrors($validator);
        } 
        else 
        {
            $clients=Client::All();
            $bill_date=Input::get('year').'-'.Input::get('month').'-'.cal_days_in_month(CAL_GREGORIAN, Input::get('month'), Input::get('year'));
            $bill_date_first_day = Carbon::createFromDate(Input::get('year'), Input::get('month'), 1);
            $bill_date_last_day =  clone $bill_date_first_day;
            $bill_date_last_day = $bill_date_last_day->addMonth();
            foreach($clients as $client){
                foreach ($client->agreements as $agreement) {
                    $nds = 0;
                    $commission_sum = 0;
                    $without_nds = 0;
                    $with_nds = 0;
                    $debt = 0;
                    $pred_with_nds = 0;
                    $with_nds_delivery =0;
                    $repayments = 0;
                    foreach($agreement->relations as $relation){
                        if($agreement->account == FALSE){
                            foreach ($relation->deliveries as $delivery) {
                                if($delivery->status=='Профинансирована'){
                                    $pred_with_nds = $delivery->dailyChargeCommission()
                                        ->where('handler',false)
                                        ->whereDate('created_at', '<', $bill_date_first_day)
                                        ->sum('with_nds');
                                    $repayments = $delivery->dailyChargeCommission()
                                        ->where('handler',true)
                                        ->whereDate('created_at', '<', $bill_date_last_day)
                                        ->sum('with_nds');
                                    $with_nds_delivery = $delivery->dailyChargeCommission()
                                        ->where('handler',false)
                                        ->whereYear('created_at', '=', Input::get('year'))
                                        ->whereMonth('created_at', '=', Input::get('month'))
                                        ->sum('with_nds');  
                                    if($repayments>$pred_with_nds){
                                        if($repayments>=($with_nds_delivery+$pred_with_nds)){
                                            $debt+=0;
                                        }else{
                                            $debt+=$with_nds_delivery-($repayments-$pred_with_nds);
                                        }
                                    }else{
                                        $debt+=$with_nds_delivery;
                                    }
                                    $with_nds+=$with_nds_delivery;
                                    $nds+= $delivery->dailyChargeCommission()
                                        ->where('handler',false)
                                        ->whereYear('created_at', '=', Input::get('year'))
                                        ->whereMonth('created_at', '=', Input::get('month'))
                                        ->sum('nds');
                                    $without_nds+=$delivery->dailyChargeCommission()
                                        ->where('handler',false)
                                        ->whereYear('created_at', '=', Input::get('year'))
                                        ->whereMonth('created_at', '=', Input::get('month'))
                                        ->sum('without_nds');
                                    $bill_date=Input::get('year').'-'.Input::get('month').'-'.cal_days_in_month(CAL_GREGORIAN, Input::get('month'), Input::get('year'));
                                }
                            }
                        }
                        else{
                            foreach ($relation->deliveries as $delivery) {
                                if($delivery->date_of_payment!=NULL AND $delivery->date_of_payment->year == Input::get('year') AND $delivery->date_of_payment->month == Input::get('month') AND $delivery->status=='Профинансирована'){
                                    foreach ($delivery->dailyChargeCommission->where('handler',false) as $commission){
                                        $nds+= $delivery->dailyChargeCommission()
                                        ->where('handler',false)
                                        ->sum('nds');
                                        $without_nds+=$delivery->dailyChargeCommission()
                                        ->where('handler',false)
                                        ->sum('without_nds');
                                        $with_nds+=$delivery->dailyChargeCommission()
                                        ->where('handler',false)
                                        ->sum('with_nds');
                                        $bill_date=$delivery->date_of_payment;
                                        $debt=0;
                                    }
                                }
                            }
                        }
                    }
                    if($with_nds!=0){
                        $bill = new Bill;
                        $bill->bill_date = $bill_date;
                        $bill->agreement_id = $agreement->id;
                        $bill->nds = $nds;
                        $bill->with_nds = $with_nds;
                        $bill->without_nds = $without_nds;
                        $bill->client_id = $client->id;
                        $bill->debt= $debt;
                        $bill->save();
                    }
                }
            }
        }
    }
}
