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
            
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('relation')
                ->withErrors($validator);
        } else {
            // store
            $relation = new Relation;
            $relation->client_id = Input::get('client_id');
            $relation->debtor_id = Input::get('debtor_id');
            $relation->active = Input::get('active');
            $relation->created_at = Input::get('created_at');
            $relation->rpp = Input::get('rpp');
            $relation->agreement_id = Input::get('agreement_id');
            $relation->deferment = Input::get('deferment');
            $relation->deferment_type = Input::get('deferment_type');
            $relation->waiting_period = Input::get('waiting_period');
            $relation->waiting_period_type = Input::get('waiting_period_type');
            $relation->regress_period = Input::get('regress_period');
            $relation->regress_period_type = Input::get('regress_period_type');
            $relation->save();

            // redirect
            Session::flash('message', 'Связь добавлена добавлен');
            return Redirect::to('debtor');
        }
    }

}
