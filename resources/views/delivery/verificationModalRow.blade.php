<?php $num = 1; ?>
@forelse($deliveries as $delivery)
	<tr>
		<td><input type="checkbox" checked data-id='{{ $delivery->id }}' class='verificationPopup'></td>
		<td>{{ $num }}</td>
		<td>{{ $delivery->debtor->name }}</td>
		<td>{{ $delivery->waybill }}</td>
		<td>{{ date('d/m/Y', strtotime($delivery->date_of_waybill)) }}</td>
		<td nowrap>{{ number_format($delivery->waybill_amount,2,',',' ') }}</td>
		<td nowrap>{{ number_format($delivery->first_payment_amount, 2,',',' ') }}</td>
	</tr>
	<?php $num++; ?>
@empty
	<tr>
		<td>Поставки отсутствуют</td>
	</tr>
@endforelse