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
    public function index(Request $request)
    {   
        if($request->ajax()){
            $handler = Input::get('handler');
            if ($handler == 0){
                $clients = Client::all();
            }elseif($handler == 1){
                $clients = Client::where('active',true)->get();
            }else{
                $clients = Client::where('active',false)->get();
            }
            return view('clients.table', ['clients' => $clients]);
        }else{
            $clients=Client::all();
            return view('clients.index', ['clients' => $clients]); 
        }  
    }

    public function store()
    {
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'full_name'  => 'required',
            'name'       => 'required',
            'inn'       => 'required',
            'kpp'       => 'required|size:9',
            'ogrn'       => 'required|unique:clients|size:13'
        );
        $validator = Validator::make(Input::all(), $rules);
        // process the login
        if ($validator->fails()) {
            return redirect()->back()->with('danger','Данные клиента введены неверно')->withInput();
        } else {
            if(count(Client::where('inn','=',Input::get('inn'))->get())>0){
                return redirect()->back()->with('danger','Клиент с данным ИНН уже имеется в базе')->withInput();
            }else{
                var_dump($this->is_valid_inn((int)Input::get('inn')));
                if($this->is_valid_inn((int)Input::get('inn'))){ //Проверка инн
                    $client = new Client;
                    $client->full_name = Input::get('full_name');
                    $client->name = Input::get('name');
                    $client->inn = Input::get('inn');
                    $client->kpp = Input::get('kpp');
                    $client->ogrn = Input::get('ogrn');
                    $client->save();
                    // redirect
                   //*Request::flashOnly('message', 'Клиент добавлен');*/
				    Session::flash('success', 'Клиент добавлен');
                return Redirect::to('client/'.$client->id.'/agreement');
                }else{
                     return redirect()->back()->with('danger','ИНН введен не верно')->withInput();
                }
            }
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
            'kpp'       => 'required|size:9',
            'ogrn'       => 'required|unique:clients|size:13'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return redirect()->back()->with('danger','Данные клиента введены неверно')->withInput();
        } else {
            // store
            $client = Client::find($id);
            $client->full_name = Input::get('full_name');
            $client->name = Input::get('name');
            $client->inn = Input::get('inn');
            $client->kpp = Input::get('kpp');
            $client->ogrn = Input::get('ogrn');
            if ($this->is_valid_inn((int)$client->inn)){//Проверка инн
                $client->save();
            }else{
                var_dump('Error');
            }

            // redirect
            Session::flash('success', 'Изменения сохранены');
            return Redirect::to('client');
        }
    }
    public function destroy($id)
    {
        Client::destroy($id);
       // $client->delete();

        // redirect
        //Session::flash('message', 'Successfully deleted the nerd!');
        //return Redirect::to('client');
    }

    public function agreement($id){
        $client = Client::find($id);
        return view('clients.agreement',['client' => $client]);
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

