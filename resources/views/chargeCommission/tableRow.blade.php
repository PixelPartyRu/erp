@forelse ($commissions as $commission)
	<tr>
		<td>{{ $commission->delivery->client->name }}</td>
		<td>{{ $commission->delivery->debtor->name }}</td>
		<td>{{ $commission->registry }}</td>
		<td>{{ $commission->waybill }}</td>
		<td>{{ date('d/m/Y', strtotime($commission->date_of_waybill)) }}</td>
		<td>{{ number_format($commission->fixed_charge,2,',',' ') }}</td>
		<td>{{ number_format($commission->percent,2,',',' ') }}</td>
		<td>{{ number_format($commission->udz,2,',',' ') }}</td>
		<td>{{ number_format($commission->deferment_penalty,2,',',' ') }}</td>
		<td>{{ number_format($commission->without_nds,2,',',' ') }}</td>
		<td>{{ number_format($commission->nds,2,',',' ') }}</td>
		<td>{{ number_format($commission->with_nds,2,',',' ') }}</td>
		<td>{{ number_format($commission->debt,2,',',' ') }}</td>
		<td>
			@if($commission->date_of_repayment != 0)
				{{ date('d/m/Y', strtotime($commission->date_of_repayment)) }}
			@endif
		</td>
		<td>{{ date('d/m/Y', strtotime($commission->date_of_funding)) }}</td>
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
