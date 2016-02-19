<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Input;

class NightChargeController extends Controller
{
    public function index(){
    	return view('global.chargeCommission');
    }

    public function recalculate(){
    	Artisan::call('Recalculation');
    	return redirect()->back();
    }
}
