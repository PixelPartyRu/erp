<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use IlluminateDatabaseEloquentModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class DeliveryController extends Controller
{
    public function index()
    {   
       return view('delivery.index');
    }

    public function getCsvFile(){
    	if (Input::file('report')){
    		$result = Excel::load(Input::file('report'), function($reader){
		   		$reader->setDateFormat('Y-m-d');
		   		$reader->toObject();
			},'UTF-8')->get();
		  	$resultNotJson = $result->each(function($sheet) {
			    $sheet->each(function($row) {
			    });
			});
			$resultArray = json_decode($resultNotJson);

			$waybillArray = [];
			$row = 0;
			$concession = 0;
			$stop = 0;
			$i = 11;
			while ($stop === 0){
				if($resultArray[$i][1] === 'накладная'){
					$waybillArray[$row][0] = $resultArray[$i][2]; //накладная
					$waybillArray[$row][1] = $resultArray[$i][3]; //дата накладной
					$waybillArray[$row][2] = $resultArray[$i + 1][2]; //счет фактура
					$waybillArray[$row][3] = $resultArray[$i + 1][3]; //дата счет фактуры
					$waybillArray[$row][4] = $resultArray[$i][4]; //дата деб задолжности
					$waybillArray[$row][5] = $resultArray[$i][5]; //сумма уступки
					$waybillArray[$row][6] = $resultArray[$i][6]; //заметки
					$concession = $concession + $resultArray[$i][5]; //общая сумма
					$row++;
					$i = $i + 2;
				}else{
					$stop = 1;
				}
			}
		   return view('delivery.getCsvFile',['resultArray' => $resultArray,'waybillArray' => $waybillArray,'concession' => $concession]);
    	}else{
    		 return Redirect::to('delivery');
    	}
    }

    public function store(){
        
    }
}


