<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use App\ChargeCommission;

class NightRecalculation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $nowDate = new Carbon(date('Y-m-d'));
        $chargeCommission = ChargeCommission::where('waybill_status',false)->first();
        $chargeDate = new Carbon($chargeCommission->charge_date);
        $dateOfFundingDiff = $chargeDate->diffInDays($nowDate,false);
        if ($dateOfFundingDiff > 0){
            return Redirect::to('recalculation');
            //return $next($request); 
        }else{
            return $next($request); 
        }
    }
}
