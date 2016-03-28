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
use DB;

class RelationController extends Controller
{
    public function index()
    {   
        $clients = Client::orderBy('name','ASC')->get();
        $debtors = Debtor::orderBy('name','ASC')->get();
        $relations = Relation::all();
        $tariffs = Tariff::all();


		return view('relations.index', ['relations' => $relations,'clients' => $clients,'tariffs' => $tariffs, 'debtors' => $debtors]); 
    }
    public function store()
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'client_id'  => 'required|not_in:0',
            'debtor_id'       => 'required|not_in:0',
            'agreement_id'       => 'required|not_in:0',
            'tariff_id'       => 'required|not_in:0',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            $messages = $validator->messages();
            if(count($messages)>1)
                $message='Поля ';
            else
                $message='Поле ';
            if($messages->has('client_id'))
                $message.=' клиент,';
            if($messages->has('debtor_id'))
                $message.=' дебитор,';
            if($messages->has('agreement_id'))
                $message.=' договор,';
            if($messages->has('tariff_id'))
                $message.=' тариф';
            if(count($messages)>1)
                $message.=' не выбранны';
            else
                $message.=' не выбранно';
            return redirect()->back()->with('danger',$message)->withInput();
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
            $contract->created_at = Input::get('contract_created_at');
            if(Input::get('contract_date_end')!=NULL)
                $contract->date_end = Input::get('contract_date_end');
            $contract->save();
            $relation = new Relation;
            $relation->client_id = Input::get('client_id');
            $relation->debtor_id = Input::get('debtor_id');
            $relation->active = Input::get('active');
            $relation->created_at = Input::get('created_at');
            $relation->confedential_factoring = Input::get('confedential_factoring');
            $relation->rpp = Input::get('rpp');
            $relation->agreement_id = Input::get('agreement_id');
            $relation->deferment_start = Input::get('size');
            $relation->deferment = Input::get('deferment');
            $relation->deferment_type = Input::get('deferment_type');
            $relation->waiting_period = Input::get('waiting_period');
            $relation->waiting_period_type = Input::get('waiting_period_type');
            $relation->regress_period = Input::get('regress_period');
            $relation->regress_period_type = Input::get('regress_period_type');
            $relation->original_document_id =  $original_document->id;
            $relation->contract_id = $contract->id;
            $relation->tariff_id = Input::get('tariff_id');
            if($relation->save())
            Session::flash('success', 'Связь добавлена');
            return Redirect::to('relation');
        }
    }

     public function edit($id){
		$relation = Relation::find($id); 
        $client = Client::find($relation->client->id);
        $debtor = Debtor::find($relation->debtor->id);
        $tariffs = Tariff::all();
        return view('relations.edit', ['relation' => $relation,'client' => $client,'tariffs' => $tariffs, 'debtor' => $debtor]);
     }
		
	public function getFilterData(){
		
		
		$client_inn = Input::get('ClientInn');
		$debtor_inn = Input::get('DebtorInn');
		$active = Input::get('Active');
		$noActive = Input::get('NoActive');
		if(!empty($debtor_inn) || !empty($client_inn) || !empty($active)){
			$relations = Relation::query();
				if($active == 1){				
					$relations->where('active', '=', 1);
				}
				if($active == 2){
					$relations->where('active', '=', 0);
				}
			if(!empty($client_inn)){
			/*	$relations->whereHas('client', function($query) use ($client_inn)
				{
					$query->where('inn', '=', $client_inn);
				});
				*/
				$relations->where('client_id', '=', $client_inn);
			}
			if(!empty($debtor_inn)){			
/*				$relations->whereHas('debtor',function($query) use ($debtor_inn)
				{
					$query->where('inn', '=', $debtor_inn); 
				});*/
				$relations->where('debtor_id', '=', $debtor_inn);
			}
			$relations = $relations->get();
		}else{
			$relations = Relation::all();
		}
		
		return view('relations.table', ['relations' => $relations]);
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
            if(Input::get('contract_date_end')!=NULL)
                $relation->contract->date_end = Input::get('contract_date_end');
            $relation->contract->save();
			
           // $relation->client_id = Input::get('client_id');
          //  $relation->debtor_id = Input::get('debtor_id');
			if(Input::get('active')){
				$relation->active = true;
			}else{
				$relation->active = false;
			}
            $relation->confedential_factoring = Input::get('confedential_factoring');
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
            Session::flash('success', 'Связь сохранена');
            return Redirect::to('relation');
        }
    }

}
