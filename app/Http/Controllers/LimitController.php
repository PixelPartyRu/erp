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

use App\Limit;
use App\Relation;
use App\Client;
use App\Debtor;
class LimitController extends Controller
{
    public function index(Request $request)
    {   
        $sort = Input::get('sort') == null ? 'relation_id' : Input::get('sort');
        $client_id_select = Input::get('client_id_select') == null ? 'all' : Input::get('client_id_select');
        $countCongestion = Input::get('count_congestion') == null ? 'remainder_of_the_debt_first_payment' : Input::get('count_congestion');
        $sortDirection = Input::get('sortDirection') == null ? 'DESC' : Input::get('sortDirection');
        $clients = Client::all();
        $debtors = Debtor::all();
        $limits = Limit::whereHas('relation', function ($query) use ($client_id_select) {
            if($client_id_select!=='all')
                $query->where('client_id', '=', $client_id_select);
        })->get();
        $usedLimit = array();
        foreach ($limits as $key => $limit) {
            $usedLimitItem=0;
            foreach ($limit->relation->deliveries as $value) {
                if($value->status=='Профинансирована'){
                    if($countCongestion=='remainder_of_the_debt_first_payment'){
                        $usedLimitItem+=$value->remainder_of_the_debt_first_payment;
                    }else{
                        $usedLimitItem+=$value->balance_owed;
                    }
                }
            }
            $usedLimit[$key] = $usedLimitItem;
        }

        $relations = Relation::select('client_id')->distinct()->get();   
       if($request->ajax())
            if($sort=='client_id')
                return view('limits.indexAjaxClient', ['clients'=>$clients,'countCongestion'=>$countCongestion]); 
            else 
                if($sort=='debtor_id')
                    return view('limits.indexAjaxDebtor', ['countCongestion'=>$countCongestion,'debtors'=>$debtors]); 
            else
                return view('limits.indexAjax', ['limits' => $limits,'relations'=>$relations,'usedLimit'=>$usedLimit]); 
        else
            return view('limits.index', ['limits' => $limits,'relations'=>$relations,'usedLimit'=>$usedLimit,'clients'=>$clients]); 
    }
    public function relationsByClient($id){
        $relations = Relation::where('client_id','=',$id)->with('debtor')->get();
        return response()->json($relations);
    }
    public function show($id,Request $request)
    {

        if($request->ajax()){
            $limit = Limit::firstOrNew(['relation_id' => $id]);
            $limit->save();
            return response()->json($limit);
        }
        else
            return;
    }
    public function update($id,Request $request){
        if($request->ajax()){
            $inputs = Input::all();
            $limit = Limit::find($id);
            if(strlen($inputs['value'])>0)
                $limit->value=str_replace(array(' ',','),array('','.'),$inputs['value']);
            else
                $limit->value=Input::get('value');
            if($limit->save())
                return response()->json(['status' => 1,'callback' => 'success','message_shot'=>'Успешно!', 'message' => ' Лимит сохранен']);
            else 
                return response()->json(['status' => 0]);
        }
        else{
            $limit = Limit::find($id);
            $limit->value=Input::get('limit_value');
            $limit->save();
            return response()->json($limit);
        }
        
    }
    public function destroy($id)
    {
        $limit = limit::find($id);
        $limit->delete();
    }
}
