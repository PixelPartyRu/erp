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

use App\Commission;
use App\Tariff;
use App\CommissionsRage;

class CommissionController extends Controller
{
    public function commissionType($type)
    {   
        if (null !== Input::get('id')){
            $commission = Commission::find(Input::get('id'));
            return view('commissions.'.$type,['commission' => $commission]); 
        }
        else
            return view('commissions.'.$type); 
    }
    public function store()
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('tariff')
                ->withErrors($validator);
        } else {
            // store
            $commission = new Commission;
            $commission->name = Input::get('name');
            $commission->tariff_id = Input::get('tariff_id');
            $commission->type = Input::get('commission_select');
            if (null !== Input::get('nds'))
                $commission->nds = Input::get('nds');
            if (null !== Input::get('deduction'))
                $commission->deduction = Input::get('deduction');
            if (null !== Input::get('payer'))
                $commission->payer = Input::get('payer');
            if (null !== Input::get('additional_sum'))
                $commission->additional_sum = Input::get('additional_sum');
            if (null !== Input::get('rate_stitching'))
                $commission->rate_stitching = Input::get('rate_stitching');
            if (null !== Input::get('time_of_settlement'))
                $commission->time_of_settlement = Input::get('time_of_settlement');
            if (null !== Input::get('commission_value'))
                $commission->commission_value = Input::get('commission_value');
            $commission->save();
            if (null !== Input::get('range_commission_value'))
            foreach (Input::get('range_commission_value') as $key =>  $range_commission_value){
                $commissions_rage = new CommissionsRage;
                if ('' !== Input::get('range_commission_min')[$key])
                    $commissions_rage->min = Input::get('range_commission_min')[$key];

                if ('' !== Input::get('range_commission_max')[$key])
                    $commissions_rage->max = Input::get('range_commission_max')[$key];
                $commissions_rage->value = Input::get('range_commission_value')[$key];
                $commissions_rage->commission_id = $commission->id;
                $commissions_rage->save();
            }
            // redirect
            return response()->json($commission->tariff_id);
        }
    }
     public function update($id){
        $commission = Commission::find($id);
        $commission->nds = Input::get('nds');
        $commission->deduction = Input::get('deduction');
        $commission->payer = Input::get('payer');
        $commission->additional_sum = Input::get('additional_sum');
        $commission->rate_stitching = Input::get('rate_stitching');
        $commission->time_of_settlement = Input::get('time_of_settlement');
        if (null !== Input::get('commission_value'))
            $commission->commission_value = Input::get('commission_value');
        $commission->save();
        if (null !== Input::get('range_commission_value'))
        foreach (Input::get('range_commission_value') as $key =>  $range_commission_value){
            if ( "" !== Input::get('range_commission_id')[$key])
                $commissions_rage = CommissionsRage::find(Input::get('range_commission_id')[$key]);
            else
                $commissions_rage = new CommissionsRage;
            $commissions_rage->min = Input::get('range_commission_min')[$key];
            $commissions_rage->max = Input::get('range_commission_max')[$key];
            $commissions_rage->value = Input::get('range_commission_value')[$key];
            $commissions_rage->commission_id = $commission->id;
            $commissions_rage->save();
        }
        // redirect
        return response()->json($commission->tariff_id);
    }
    public function destroy($id)
    {
        $client = CommissionsRage::find($id);
        $client->delete();
    }


}
