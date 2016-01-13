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

class TariffController extends Controller
{
    public function index()
    {   
        $tariffs=Tariff::orderBy('name')->get();
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
                    $oldtariff->load('commissions');
                    $tariff = $oldtariff->replicate();
                    $tariff->name = Input::get('name');
                    $tariff->push();


   

                    return response()->json(['callback' => 'success','message_shot'=>'Тест!', 'message' => ' Тариф скопирован ','tariff_id'=>$tariff->id]);
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
        return view('tariffs.show', ['tariff' => $tariff]); 
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
