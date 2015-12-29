<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
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
	   Excel::load(Input::file('report'), function($reader){
	   		$reader->formatDates(true, 'Y-m-d');
	   		$reader->each(function($sheet) {

			    $sheet->each(function($row) {
			    	var_dump($row);
			    });

			});
		}, 'UTF-8');
	   //return view('delivery.getCsvFile');
    }
}
