<?php $sumFixed = 0 ?>
<?php $sumPercent = 0 ?>
<?php $sumUdz = 0 ?>
<?php $sumDeferment = 0 ?>
<?php $sumWithout = 0 ?>
<?php $sumNds = 0 ?>
<?php $sumWith = 0 ?>
<?php $sumDebt= 0 ?>
@forelse ($commissions as $commission)
	<tr>
		<td nowrap>{{ $commission->client->name }}</td>
		<td nowrap>{{ $commission->debtor->name }}</td>
		<td>{{ $commission->registry }}</td>
		<td>{{ $commission->waybill }}</td>
		<td>{{ date('d/m/Y', strtotime($commission->date_of_waybill)) }}</td>
		<td>{{ number_format($commission->fixed_charge,2,',',' ') }}</td>
		<?php $sumFixed +=  $commission->fixed_charge ?> 
		<td>{{ number_format($commission->percent,2,',',' ') }}</td>
		<?php $sumPercent +=  $commission->percent ?> 
		<td>{{ number_format($commission->udz,2,',',' ') }}</td>
		<?php $sumUdz +=  $commission->udz ?> 
		<td>{{ number_format($commission->deferment_penalty,2,',',' ') }}</td>
		<?php $sumDeferment +=  $commission->deferment_penalty ?> 
		<td>{{ number_format($commission->without_nds,2,',',' ') }}</td>
		<?php $sumWithout += $commission->without_nds ?> 
		<td>{{ number_format($commission->nds,2,',',' ') }}</td>
		<?php $sumNds +=  $commission->nds ?> 
		<td>{{ number_format($commission->with_nds,2,',',' ') }}</td>
		<?php $sumWith +=  $commission->with_nds ?> 
		<td>{{ number_format($commission->debt,2,',',' ') }}</td>
		<?php $sumDebt +=  $commission->debt ?> 
		<td>
			@if($commission->date_of_repayment != 0)
				{{ date('d/m/Y', strtotime($commission->date_of_repayment)) }}
			@endif
		</td>
		<td>@if($commission->date_of_funding != 0)
			{{ date('d/m/Y', strtotime($commission->date_of_funding)) }}</td>
			@endif
		<td>
			@if ($commission->waybill_status == true)
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
		<td><strong>Итого:</strong></td>
		<td><strong>{{ number_format($sumFixed,2,',',' ') }}</strong></td>
		<td><strong>{{ number_format($sumPercent,2,',',' ') }}</strong></td>
		<td><strong>{{ number_format($sumUdz,2,',',' ') }}</strong></td>
		<td><strong>{{ number_format($sumDeferment,2,',',' ') }}</strong></td>
		<td><strong>{{ number_format($sumWithout,2,',',' ') }}</strong></td>
		<td><strong>{{ number_format($sumNds,2,',',' ') }}</strong></td>
		<td><strong>{{ number_format($sumWith,2,',',' ') }}</strong></td>
		<td><strong>{{ number_format($sumDebt,2,',',' ') }}</strong></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>