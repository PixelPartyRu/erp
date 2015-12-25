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

use App\Relation;
use App\Client;
use App\Debtor;
class RelationController extends Controller
{
    public function index()
    {   
        $clients = Client::all();
        $debtors = Debtor::all();
        $relations = Relation::all();
        return view('relations.index', ['relations' => $relations,'clients' => $clients, 'debtors' => $debtors]); 
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
            return Redirect::to('debtor')
                ->withErrors($validator);
        } else {
            // store
            $debtor = new Debtor;
            $debtor->full_name = Input::get('full_name');
            $debtor->name = Input::get('name');
            $debtor->inn = Input::get('inn');
            $debtor->kpp = Input::get('kpp');
            $debtor->ogrn = Input::get('ogrn');
            $debtor->save();

            // redirect
            Session::flash('message', 'Дебитор добавлен');
            return Redirect::to('debtor');
        }
    }

}
