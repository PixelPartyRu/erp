<?php $numberSum = 1 ?>
<?php $sumFixed = 0 ?>
<?php $sumPercent = 0 ?>
<?php $sumUdz = 0 ?>
<?php $sumDeferment = 0 ?>
<?php $sumWithout = 0 ?>
<?php $sumNds = 0 ?>
<?php $sumWith = 0 ?>
<?php $sumDebt= 0 ?>
@forelse ($deliveries as $delivery)
	<tr>
		<td>{{ $numberSum++ }}</td>
		<td nowrap>{{ $delivery->client->name }}</td>
		<td nowrap>{{ $delivery->debtor->name  }}</td>
		<td>{{ $delivery->registry }}</td>
		<td>{{ $delivery->waybill}}</td>
		<td>{{ date('d/m/Y', strtotime($delivery->date_of_waybill)) }}</td>
		<td>{{ number_format($delivery->dailyChargeCommission()->where('handler','=',false)->sum('fixed_charge'),2,',',' ') }}</td>
		<?php $sumFixed += $delivery->dailyChargeCommission()->where('handler','=',false)->sum('fixed_charge') ?> 
		<td>{{ number_format($delivery->dailyChargeCommission()->where('handler','=',false)->sum('percent'),2,',',' ') }}</td>
		<?php $sumPercent += $delivery->dailyChargeCommission()->where('handler','=',false)->sum('percent') ?> 
		<td>{{ number_format($delivery->dailyChargeCommission()->where('handler','=',false)->sum('udz'),2,',',' ') }}</td>
		<?php $sumUdz += $delivery->dailyChargeCommission()->where('handler','=',false)->sum('udz') ?> 
		<td>{{ number_format($delivery->dailyChargeCommission()->where('handler','=',false)->sum('deferment_penalty'),2,',',' ') }}</td>
		<?php $sumDeferment += $delivery->dailyChargeCommission()->where('handler','=',false)->sum('deferment_penalty') ?> 
		<td>{{ number_format($delivery->dailyChargeCommission()->where('handler','=',false)->sum('without_nds'),2,',',' ') }}</td>
		<?php $sumWithout += $delivery->dailyChargeCommission()->where('handler','=',false)->sum('without_nds') ?> 
		<td>{{ number_format($delivery->dailyChargeCommission()->where('handler','=',false)->sum('nds'),2,',',' ') }}</td>
		<?php $sumNds +=  $delivery->dailyChargeCommission()->where('handler','=',false)->sum('nds') ?> 
		<td>{{ number_format($delivery->dailyChargeCommission()->where('handler','=',false)->sum('with_nds'),2,',',' ') }}</td>
		<?php $sumWith +=  $delivery->dailyChargeCommission()->where('handler','=',false)->sum('with_nds') ?> 
		<td>{{ number_format($delivery->chargeCommission->debt,2,',',' ') }}</td>
		 <?php $sumDebt +=  $delivery->chargeCommission->debt ?>
		<td>
			@if($delivery->ChargeCommission->date_of_repayment != 0)
				{{ date('d/m/Y', strtotime($delivery->chargeCommission->date_of_repayment)) }}
			@endif
		</td>
		<td>@if($delivery->date_of_funding != 0)
			{{ date('d/m/Y', strtotime($delivery->date_of_funding)) }}</td>
			@endif
		<td>
			@if ($delivery->chargeCommission->debt <= 0)
				Погашена
			@else
				Непогашена
			@endif
		</td>
	</tr>
@empty
	<tr>
		<td>Начисленных комиссий нет</td>
	</tr>
@endforelse
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td><strong>Итого:</strong></td>
		<td><strong>{{ number_format($sumFixed,2,',',' ') }}</strong></td>
		<td><strong>{{ number_format($sumPercent,2,',',' ') }}</strong></td>
		<td><strong>{{ number_format($sumUdz,2,',',' ') }}</strong></td>
		<td><strong>{{ number_format($sumDeferment,2,',',' ') }}</strong></td>
		<td><strong>{{ number_format($sumWithout,2,',',' ') }}</strong></td>
		<td><strong>{{ number_format($sumNds,2,',',' ') }}</strong></td>
		<td><strong>{{ number_format($sumWith,2,',',' ') }}</strong></td>
		<td><strong>{{ number_format($sumDebt,2,',',' ')}}</strong></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>