<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

class ExcelController extends Controller
{
    public function index(){
    	$name = Input::get('name');
    	$now = Carbon::now();
    	$name = $name.'-'.$now->timestamp;
    	$body = Input::get('body');
    	Excel::create($name, function($excel) use($name,$body){	
		    $excel->sheet($name, function($sheet) use($body) {
		        $sheet->fromArray($body, null, 'A1', false,false);
		        $sheet->cells('1', function($cells) {
		        	$cells->setFont(array(
					    'bold'       =>  true
					));
				});
		    });
		})->store('xlsx');

		return $name;
    }
}
