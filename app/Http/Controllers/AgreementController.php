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
           // 'penalty'       => 'required',
            'code_1c'       => 'required|unique:agreements',
            //'date_end'       => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            Session::flash('danger', $validator->errors()->first());
            return redirect()->back()->withInput();
        } else {
           //store
            $agreement = new Agreement;
            $agreement->code = Input::get('code');
            if (Input::get('type')){
                $agreement->type = TRUE;
            }else{
                $agreement->type = FALSE;
            }
            if (Input::get('account')){
                $agreement->account = TRUE;
            }else{
                $agreement->account = FALSE;
            }
            if(Input::get('penalty') != NULL){ 
				$agreement->penalty = Input::get('penalty');
			}
            if (Input::get('second_pay')){
                $agreement->second_pay = TRUE;
            }else{
                $agreement->second_pay = FALSE;
            }
            if (Input::get('code_1c')){
                $agreement->code_1c = Input::get('code_1c');
            }
            $agreement->description = ' ';
            if (Input::get('active')){
                $agreement->active = TRUE;
            }else{
                $agreement->active = FALSE;
            }
            $agreement->client_id = Input::get('client_id');

            if (Input::get('date_end') != NULL){
                $agreement->date_end = Input::get('date_end');
            }

			$agreement->created_at = Input::get('created_at');
            $agreement->save();
            // redirect
           /* Request::flashOnly('message', 'Клиент добавлен');*/
            return Redirect::to('client/'.Input::get('client_id').'/agreement');
        }
    }

    public function edit($id)
    {
        $agreement = Agreement::find($id);

        return view('agreements.edit', ['agreement' => $agreement]); 
    }

    public function show($id){
        $agreement = Agreement::find($id);
        return response()->json(['agreement' => $agreement]);
    }

    public function update($id)
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'code'  => 'required',
            //'penalty'       => 'required',
            'code_1c'       => 'required|unique:agreements',
            //'date_end'       => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            Session::flash('danger', $validator->errors()->first());
            return redirect()->back()->withInput();
        } else {
            // store
         	$agreement = Agreement::find($id);
            $agreement->code = Input::get('code');
            if (Input::get('type')){
                $agreement->type = TRUE;
            }else{
                $agreement->type = FALSE;
            }
            if (Input::get('account')){
                $agreement->account = TRUE;
            }else{
                $agreement->account = FALSE;
            }
            //$agreement->penalty = Input::get('penalty');
			if(Input::get('penalty') != NULL){ 
                $agreement->penalty = Input::get('penalty');
            }

            if (Input::get('second_pay')){
                $agreement->second_pay = TRUE;
            }else{
                $agreement->second_pay = FALSE;
            }
            $agreement->code_1c = Input::get('code_1c');
            $agreement->description = ' ';
            if (Input::get('active')){
                $agreement->active = TRUE;
            }else{
                $agreement->active = FALSE;
            }
            //$agreement->client_id = Input::get('client_id');
            if (Input::get('date_end') != NULL){
                $agreement->date_end = Input::get('date_end');
            }
           
            $agreement->save();

            // redirect
            Session::flash('message', 'Изменения сохранены');
            return Redirect::to('client/'.$agreement->client_id.'/agreement');
        }
    }

    public function destroy($id)
    {
        $agreement = Agreement::find($id);
        $agreementId = $agreement->client_id;
        $agreement->delete();

        // redirect
        //Session::flash('message', 'Successfully deleted the nerd!');
        return Redirect::to('client/'.$agreementId.'/agreement');
    }
}
