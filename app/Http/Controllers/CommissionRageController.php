<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use IlluminateDatabaseEloquentModel;

use App\CommissionsRage;

class CommissionRageController extends Controller
{
    public function destroy($id)
    {
        $client = CommissionsRage::find($id);
        $client->delete();
        return;
    }


}
