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

use App\Client;

class ClientController extends Controller
{
    public function index()
    {   
        $clients=Client::all();
        return view('clients.index', ['clients' => $clients]); 
    }

    public function store()
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'full_name'  => 'required',
            'name'       => 'required',
            'inn'       => 'required',
            'kpp'       => 'required',
            'ogrn'       => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('client')
                ->withErrors($validator);
        } else {
            // store
            $client = new Client;
            $client->full_name = Input::get('full_name');
            $client->name = Input::get('name');
            $client->inn = Input::get('inn');
            $client->kpp = Input::get('kpp');
            $client->ogrn = Input::get('ogrn');
            $client->save();

            // redirect
           /* Request::flashOnly('message', 'Клиент добавлен');*/
            return Redirect::to('client');
        }
    }

    public function edit($id)
    {   
        $client = Client::find($id);

        return view('clients.edit', ['client' => $client]); 
    }

    public function show($id){
        $client = Client::find($id);
        $agreements =  $client->agreements;
        return response()->json(['agreements' => $agreements]);
    }

    public function update($id)
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'full_name'  => 'required',
            'name'       => 'required',
            'inn'       => 'required',
            'kpp'       => 'required',
            'ogrn'       => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('client')
                ->withErrors($validator);
        } else {
            // store
            $client = Client::find($id);
            $client->full_name = Input::get('full_name');
            $client->name = Input::get('name');
            $client->inn = Input::get('inn');
            $client->kpp = Input::get('kpp');
            $client->ogrn = Input::get('ogrn');
            $client->save();

            // redirect
            Session::flash('message', 'Изменения сохранены');
            return Redirect::to('client');
        }
    }
    public function destroy($id)
    {
        $client = Client::find($id);
        $client->delete();

        // redirect
        Session::flash('message', 'Successfully deleted the nerd!');
        return Redirect::to('client');
    }

    public function agreement($id){
        $client = Client::find($id);
        return view('clients.agreement',['client' => $client]);
    }

}

