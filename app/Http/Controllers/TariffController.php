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


use App\Tariff;
use App\Commission;

class TariffController extends Controller
{
    public function index(Request $request)
    {   
        //Input::get('active')
        $sort = Input::get('sort') == null ? 'id' : Input::get('sort');
        $sortDirection = Input::get('sortDirection') == null ? 'DESC' : Input::get('sortDirection');
        $tariffs = Tariff::orderBy($sort,$sortDirection);
        if(Input::has('deactive')){
            if(Input::has('active')){
                $tariffs = $tariffs->get();
            }else{
                $tariffs = $tariffs->where('active','=',false);
                $tariffs = $tariffs->get();
            }
        }else{
            if(Input::has('active')){
                $tariffs = $tariffs->where('active','=',true);
                $tariffs = $tariffs->get();
            }
        }
            
       
       if($request->ajax())
            return view('tariffs.indexAjax', ['tariffs' => $tariffs]); 
        else
		    return view('tariffs.index', ['tariffs' => $tariffs]); 
    }
     public function store()
    {
        $tariff_name = Tariff::where('name', '=', Input::get('name'))->get();
        if (count($tariff_name)>0) {
            return response()->json(['callback' => 'danger','message_shot'=>'Ошибка!', 'message' => ' Тариф с таким названием уже существует']);
        }
        else{
            // validate
            // read more on validation at http://laravel.com/docs/validation
            $rules = array(
                'name'       => 'required',
            );
            $validator = Validator::make(Input::all(), $rules);
            // process the login
            if ($validator->fails()) {
                return response()->json(['callback' => 'danger','message_shot'=>'Ошибка!', 'message' => ' Тариф '.Input::get('name').' не создан']);
            } else {
                // store
                if(Input::get('tariff_id') !== null){
                    $oldtariff = Tariff::find(Input::get('tariff_id'));
                    $tariff = $oldtariff->replicate();
                    $tariff->name = Input::get('name');
                    $tariff->save();
                    foreach ($oldtariff->commissions as  $oldcommission) {
                        $commission = $oldcommission->replicate();
                        $commission->tariff_id = $tariff->id;
                        $commission->save();
                        foreach ($oldcommission->commissionsRages as  $oldcommissionsRage) {
                            $commissionsRage = $oldcommissionsRage->replicate();
                            $commissionsRage->commission_id = $commission->id;
                            $commissionsRage->save();
                        }
                    }
                    return response()->json(['callback' => 'success','message_shot'=>'Успешно!', 'message' => ' Тариф "'.$oldtariff->name.'"скопирован, теперь вы можете приступить к редактированию комиссий','tariff_id'=>$tariff->id]);
                }else{
                    $tariff = new Tariff;
                    $tariff->name = Input::get('name');
                    $tariff->save();
                    return response()->json(['callback' => 'success','message_shot'=>'Успешно!', 'message' => ' Тариф '.Input::get('name').' создан, теперь добавьте к нему комисси ','tariff_id'=>$tariff->id]);
                }
            }  
        }  
    }
    public function destroy($id)
    {
        $tariff = Tariff::find($id);
        $tariff->active = 0;
        $tariff->deactivated_at = date("Y-m-d H:i:s"); 
        $tariff->save();

        // redirect
        Session::flash('message', 'Successfully deleted the nerd!');
        return Redirect::to('tariff');
    }
    public function edit($id)
    {

    }
    public function update($id){
        $inputs = Input::all();
        $tariff = Tariff::find($id);
        $tariff->$inputs['name'] = $inputs['value'];
        if($tariff->save()) 
            return response()->json(['status' => 1]);
        else 
            return response()->json(['status' => 0]);
    }
    public function show($id)
    {
        $tariff = Tariff::find($id);
        if (count($tariff->commissions)>0)
            foreach ($tariff->commissions as $commission) {
                $commissionTypesAdded[ $commission->type] = $commission->name;
            }
        else
            $commissionTypesAdded = array();
        $commissionTypesList=array('finance' => 'Вознаграждение за пользование денежными средствами', 'document' => 'Плата за обработку одного документа','peni' => 'Пеня за просрочку','udz' => 'Вознаграждение за УДЗ');
        $commissionTypes = array_diff($commissionTypesList,$commissionTypesAdded);
        return view('tariffs.show', ['tariff' => $tariff,'commissionTypes' => $commissionTypes]); 
    }
    public function activateTariff($id)
    {
        $tariff = Tariff::find($id);
        $tariff->active = 1;
        $tariff->deactivated_at = null; 
        $tariff->save();

        // redirect
        Session::flash('message', 'Successfully deleted the nerd!');
        return Redirect::to('tariff');
    }
}
