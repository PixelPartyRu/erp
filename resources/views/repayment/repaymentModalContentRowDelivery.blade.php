<?php $num = 0; ?>
@forelse($deliveries as $delivery)
	<tr>
		<td>{!! Form::checkbox('deliveryChoice',$delivery->id) !!}</td>
		<td class="add_popup">{{$num = $num +1}}</td>
		<td>{{ $delivery->client->name }}</td>
		<td>{{ $delivery->client->inn }}</td>
		<td class="add_popup">{{ $delivery->debtor->name }}</td>
		<td>{{ $delivery->debtor->inn }}</td>
		<td class="add_popup">{{ $delivery->waybill }}</td>
		<td  class="add_popup"nowrap>{{ number_format($delivery->waybill_amount,2,',',' ') }}</td>
		<td nowrap>{{ number_format($delivery->balance_owed,2,',',' ') }}</td>
		<td nowrap>
			<span class="repaymentSum">{{ number_format(0,2,',',' ') }}</span>
			<input class="repaymentSumHidden" type="hidden" value="0">
		</td>
		<td  class="add_popup" nowrap>{{ number_format($delivery->first_payment_amount, 2,',',' ') }}</td>
		<td nowrap>{{ number_format($delivery->remainder_of_the_debt_first_payment,2,',',' ') }}</td>
		<td class="add_popup">{{ date('d/m/Y', strtotime($delivery->date_of_waybill)) }}</td>
		<td>{{ $delivery->due_date }}</td>
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
				Погашено
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
				Конфиденциальный
			@else
				Открытый
			@endif
		</td>
	</tr>
@empty
<tr>
	<td>Поставок нет</td>
</tr>
@endforelse