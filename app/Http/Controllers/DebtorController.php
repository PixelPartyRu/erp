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

use App\Debtor;

class DebtorController extends Controller
{
    public function index()
    {   
        $debtors=Debtor::all();
        return view('debtors.index', ['debtors' => $debtors]); 
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

    public function show($id)
    {
        $debtor = Debtor::find($id);

        return view('debtors.show', ['debtor' => $debtor]); 
    }
    public function edit($id)
    {
        $debtor = Debtor::find($id);

        return view('debtors.edit', ['debtor' => $debtor]); 
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
            return Redirect::to('debtor')
                ->withErrors($validator);
        } else {
            // store
            $debtor = Debtor::find($id);
            $debtor->full_name = Input::get('full_name');
            $debtor->name = Input::get('name');
            $debtor->inn = Input::get('inn');
            $debtor->kpp = Input::get('kpp');
            $debtor->ogrn = Input::get('ogrn');
            $debtor->save();

            // redirect
            Session::flash('message', 'Изменения сохранены');
            return Redirect::to('debtor');
        }
    }

}

