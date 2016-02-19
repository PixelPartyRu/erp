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
use App\Repayment;


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
            'kpp'       => array('required', 'size:9'),
            'ogrn'       => array('required','unique', 'size:13'),
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('debtor')
                ->withErrors($validator);
        } else {
            if(count(Debtor::where('inn','=',Input::get('inn'))->get())>0){
                return redirect()->back()->with('danger','Данные дебитора введены неверно')->withInput();
            }else{
                // store
                $debtor = new Debtor;
                $debtor->full_name = Input::get('full_name');
                $debtor->name = Input::get('name');
                $debtor->inn = Input::get('inn');
                $debtor->kpp = Input::get('kpp');
                $debtor->ogrn = Input::get('ogrn');
                if ($this->is_valid_inn((int)$debtor->inn)){//Проверка инн
                    $debtor->save();
                }else{
                    var_dump('Error');
                }

                // redirect
                Session::flash('success', 'Дебитор добавлен');
                return Redirect::to('debtor');
            }
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
	
	public function destroy($id)
    {
        $repayment = Repayment::where('debtor_id', '=', $id);
		$repayment->delete();
		$debtor = Debtor::find($id);
        $debtor->delete();
        Session::flash('message', 'Дебитор удален успешно!');
        //return Redirect::to('debtor');
    }
	
    public function update($id)
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'full_name'  => 'required',
            'name'       => 'required',
            'inn'       => 'required',
            'kpp'       => array('required', 'size:9'),
            'ogrn'       => array('required', 'size:13'),
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
            if ($this->is_valid_inn((int)$debtor->inn)){//Проверка инн
                $debtor->save();
            }else{
                var_dump('Error');
            }

            // redirect
            Session::flash('success', 'Изменения сохранены');
            return Redirect::to('debtor');
        }
    }

    public function is_valid_inn( $inn )
    {
        if ( preg_match('/\D/', $inn) ) return false;
        
        $inn = (string) $inn;
        $len = strlen($inn);
        
        if ( $len === 10 )
        {
            return $inn[9] === (string) (((
                2*$inn[0] + 4*$inn[1] + 10*$inn[2] + 
                3*$inn[3] + 5*$inn[4] +  9*$inn[5] + 
                4*$inn[6] + 6*$inn[7] +  8*$inn[8]
            ) % 11) % 10);
        }
        elseif ( $len === 12 )
        {
            $num10 = (string) (((
                 7*$inn[0] + 2*$inn[1] + 4*$inn[2] +
                10*$inn[3] + 3*$inn[4] + 5*$inn[5] + 
                 9*$inn[6] + 4*$inn[7] + 6*$inn[8] +
                 8*$inn[9]
            ) % 11) % 10);
            
            $num11 = (string) (((
                3*$inn[0] +  7*$inn[1] + 2*$inn[2] +
                4*$inn[3] + 10*$inn[4] + 3*$inn[5] +
                5*$inn[6] +  9*$inn[7] + 4*$inn[8] +
                6*$inn[9] +  8*$inn[10]
            ) % 11) % 10);
            
            return $inn[11] === $num11 && $inn[10] === $num10;
        }
        
        return false;
    }
}

