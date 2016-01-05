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
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'name'       => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('tariff')
                ->withErrors($validator);
        } else {
            // store
            $tariff = new Tariff;
            $tariff->name = Input::get('name');
            $tariff->save();

            // redirect
           /* Request::flashOnly('message', 'Клиент добавлен');*/
            return Redirect::to('tariff');
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
     public function show($id)
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
