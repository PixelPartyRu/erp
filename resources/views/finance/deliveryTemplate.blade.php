<?php $sum = 0 ?>
@forelse($financeToDeliveries as $financeToDelivery)
	<?php $delivery = $financeToDelivery->delivery ?>
	<tr>
		<td>{{ $delivery->client->name }}</td>
		<td>{{ $delivery->debtor->name }}</td>
		<td>{{ $delivery->waybill }}</td>
		<td>{{ number_format($delivery->waybill_amount,2,',',' ') }}</td>
		<td>{{ date('d/m/Y', strtotime($delivery->date_of_waybill)) }}</td>
		<td>{{ number_format($delivery->first_payment_amount,2,',',' ') }}</td>
		<?php $sum +=  $delivery->first_payment_amount ?> 
	</tr>
@empty
	<tr><td>Поставок нет</td></tr>
@endforelse
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td><strong>Итого:</strong></td>
		<td><strong>{{ number_format($sum,2,',',' ') }}</strong></td>
	</tr>
