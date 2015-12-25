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

use App\Agreement;

class AgreementController extends Controller
{
    public function store()
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'code'  => 'required',
            'type'       => 'required',
            'account'       => 'required',
            'penalty'       => 'required',
            'second_pay'       => 'required',
            'code_1c'       => 'required',
            'active'       => 'required',
            'date_end'       => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('client/'.Input::get('client_id').'/edit')
                ->withErrors($validator);
        } else {
            // store
            $agreement = new Agreement;
            $agreement->code = Input::get('code');
            $agreement->type = Input::get('type');
            $agreement->account = Input::get('account');
            $agreement->penalty = Input::get('penalty');
            $agreement->second_pay = Input::get('second_pay');
            $agreement->code_1c = Input::get('code_1c');
            $agreement->description = Input::get('description');
            $agreement->active = Input::get('active');
            $agreement->client_id = Input::get('client_id');
            $agreement->date_end= Input::get('date_end');
            $agreement->save();

            // redirect
           /* Request::flashOnly('message', 'Клиент добавлен');*/
            return Redirect::to('client/'.Input::get('client_id').'/edit');
        }
    }

    public function edit($id)
    {
        $agreement = Agreement::find($id);

        return view('agreements.edit'); 
    }

    // public function update($id)
    // {
    //     // validate
    //     // read more on validation at http://laravel.com/docs/validation
    //     $rules = array(
    //         'code'  => 'required',
    //         'type'       => 'required',
    //         'account'       => 'required',
    //         'penalty'       => 'required',
    //         'second_pay'       => 'required',
    //         'code_1c'       => 'required',
    //         'active'       => 'required',
    //         'date_end'       => 'required',
    //     );
    //     $validator = Validator::make(Input::all(), $rules);

    //     // process the login
    //     if ($validator->fails()) {
    //         return Redirect::to('client')
    //             ->withErrors($validator);
    //     } else {
    //         // store
    //      	$agreement = new Agreement;
    //         $agreement->code = Input::get('code');
    //         $agreement->type = Input::get('type');
    //         $agreement->account = Input::get('account');
    //         $agreement->penalty = Input::get('penalty');
    //         $agreement->second_pay = Input::get('second_pay');
    //         $agreement->code_1c = Input::get('code_1c');
    //         $agreement->description = Input::get('description');
    //         $agreement->active = Input::get('active');
    //         $agreement->client_id = Input::get('client_id');
    //         $agreement->date_end= Input::get('date_end');
    //         $agreement->save();

    //         // redirect
    //         Session::flash('message', 'Изменения сохранены');
    //         return Redirect::to('client');
    //     }
    // }

    // public function destroy($id)
    // {
    //     $agreement = new Agreement::find($id);
    //     $agreement->delete();

    //     // redirect
    //     Session::flash('message', 'Successfully deleted the nerd!');
    //     return Redirect::to('client');
    // }
}
