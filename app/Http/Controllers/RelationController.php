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
use App\OriginalDocument;
use App\Contract;
use App\Tariff;

class RelationController extends Controller
{
    public function index()
    {   
        $clients = Client::all();
        $debtors = Debtor::all();
        $relations = Relation::all();
        $tariffs = Tariff::all();
        return view('relations.index', ['relations' => $relations,'clients' => $clients,'tariffs' => $tariffs, 'debtors' => $debtors]); 
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
            if(!empty(Input::get('original_documents_value'))){
                echo "string";
                $original_document_value = Input::get('original_documents_value');
            }else {
                echo "string1";
                $original_document_value = 0;
            }
           
            $original_document = new OriginalDocument;
            $original_document->type = Input::get('original_documents_select');
            $original_document->name = Input::get('original_documents_select');
            $original_document->value = $original_document_value;
            $original_document->save();
            $contract = new Contract;
            $contract->code = Input::get('contract_code');
            $contract->name = Input::get('contract_name');
            $contract->code_1c = Input::get('contract_code_1c');
            $contract->gd_debitor_1c = Input::get('contract_gd_debitor_1c');
            $contract->description = Input::get('contract_description');
            $contract->created_at = Input::get('contract_contract_created_at');
            $contract->date_end = Input::get('contract_contract_date_end');
            $contract->save();
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
            $relation->original_document_id = OriginalDocument::all()->pluck('id')->last();
            $relation->contract_id = Contract::all()->pluck('id')->last();
            $relation->save();

            // redirect
            Session::flash('message', 'Связь добавлена добавлен');
            return Redirect::to('relation');
        }
    }

     public function edit($id){
        $clients = Client::all();
        $debtors = Debtor::all();
        $relation = Relation::find($id);
        $tariffs = Tariff::all();
        return view('relations.edit', ['relation' => $relation,'clients' => $clients,'tariffs' => $tariffs, 'debtors' => $debtors]);
     }

    public function update($id){
        $rules = array(
            
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('relation')
                ->withErrors($validator);
        } else {
            if(!empty(Input::get('original_documents_value'))){
                //echo "string";
                $original_document_value = Input::get('original_documents_value');
            }else {
                //echo "string1";
                $original_document_value = 0;
            }
            
            $relation = Relation::find($id);
            $relation->originalDocument->type = Input::get('original_documents_select');
            $relation->originalDocument->name = Input::get('original_documents_select');
            $relation->originalDocument->value = $original_document_value;
            $relation->originalDocument->save();
            $relation->contract->code = Input::get('contract_code');
            $relation->contract->name = Input::get('contract_name');
            $relation->contract->code_1c = Input::get('contract_code_1c');
            $relation->contract->gd_debitor_1c = Input::get('contract_gd_debitor_1c');
            $relation->contract->description = Input::get('contract_description');
            $relation->contract->created_at = Input::get('contract_created_at');
            $relation->contract->date_end = Input::get('contract_date_end');
            $relation->contract->save();
            $relation->client_id = Input::get('client_id');
            $relation->debtor_id = Input::get('debtor_id');
            $relation->active = Input::get('active');
            $relation->created_at = Input::get('created_at');
            $relation->rpp = Input::get('rpp');
            $relation->agreement_id = $relation->agreement_id;
            $relation->deferment = Input::get('deferment');
            $relation->deferment_type = Input::get('deferment_type');
            $relation->waiting_period = Input::get('waiting_period');
            $relation->waiting_period_type = Input::get('waiting_period_type');
            $relation->regress_period = Input::get('regress_period');
            $relation->regress_period_type = Input::get('regress_period_type');
            $relation->original_document_id = $relation->originalDocument->id;
            $relation->contract_id = $relation->contract->id;
            $relation->save();

            // redirect
            Session::flash('message', 'Связь добавлена добавлен');
            return Redirect::to('relation');
        }
    }

}
