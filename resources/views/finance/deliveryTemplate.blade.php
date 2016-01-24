<?php $sum = 0 ?>
@forelse($deliveries as $delivery)
	<tr>
		<td>{{ $delivery->client->name }}</td>
		<!-- <td>{{ $delivery->client->inn }}</td> -->
		<td>{{ $delivery->debtor->name }}</td>
		<!-- <td>{{ $delivery->debtor->inn }}</td> -->
		<td>{{ $delivery->waybill }}</td>
		<td>{{ number_format($delivery->waybill_amount,2,',',' ') }}</td>
		<!-- <td>{{ number_format($delivery->first_payment_amount, 2,',',' ') }}</td>
		<td>{{ number_format($delivery->balance_owed,2,',',' ') }}</td>
		-->
		<td>{{ date('d/m/Y', strtotime($delivery->date_of_waybill)) }}</td>
		<td>{{ number_format($delivery->remainder_of_the_debt_first_payment,2,',',' ') }}</td>
		<?php $sum +=  $delivery->remainder_of_the_debt_first_payment ?> 
		<!-- <td>{{ $delivery->due_date }}</td>
		<td>{{ date('d/m/Y', strtotime($delivery->date_of_recourse)) }}</td>
		<td>
			@if ($delivery->date_of_payment)
				{{ date('d/m/Y', strtotime($delivery->date_of_payment)) }}
			@endif
		</td>
		<td>{{ date('d/m/Y', strtotime($delivery->date_of_regress)) }}</td>
		<td>{{ date('d/m/Y', strtotime($delivery->the_date_of_termination_of_the_period_of_regression)) }}</td>
		<td>{{ date('d/m/Y', strtotime($delivery->the_date_of_a_registration_supply)) }}</td>
		<td>{{ $delivery->the_actual_deferment }}</td>
		<td>{{ $delivery->invoice }}</td>
		<td>{{ date('d/m/Y', strtotime($delivery->date_of_invoice)) }}</td>
		<td>{{ $delivery->registry }}</td>
		<td>{{ date('d/m/Y', strtotime($delivery->date_of_registry)) }}</td>
		<td>
			@if ($delivery->date_of_funding)
			{{ date('d/m/Y', strtotime($delivery->date_of_funding)) }}
			@endif

		</td>
		<td>
			@if ($delivery->end_date_of_funding)
				{{ date('d/m/Y', strtotime($delivery->end_date_of_funding)) }}
			@endif
		</td>
		<td>{{ $delivery->notes }}</td>
		<td>{{ $delivery->return }}</td>
		<td>
			@if ( $delivery->state )
				Погасил
			@else
			Непогашено
			@endif
		</td>
		<td>{{ $delivery->status }}</td>
		<td>
			@if ($delivery->the_presence_of_the_original_document)
				Да
			@else
			 	Нет
			@endif
		</td>
		<td>
			@if ($delivery->type_of_factoring)
				Открытый
			@else
			Конфиденциальный
			@endif
		</td> -->
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
		<td><strong>{{ $sum }}</strong></td>
	</tr>