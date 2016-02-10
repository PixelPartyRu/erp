{{-- */ $num = 0; /* --}}
{{-- */ $sum_first_payment_amount = 0; /* --}}
{{-- */ $sum_waybill_amount = 0; /* --}}
{{-- */ $sum_balance_owed = 0; /* --}}
{{-- */ $sum_remainder_of_the_debt_first_payment = 0; /* --}}

@forelse($deliveries as $delivery)
{{-- */ $sum_waybill_amount += $delivery->waybill_amount; /* --}}
{{-- */ $sum_first_payment_amount += $delivery->first_payment_amount; /* --}}
{{-- */ $sum_balance_owed += $delivery->balance_owed; /* --}}
{{-- */ $sum_remainder_of_the_debt_first_payment += $delivery->remainder_of_the_debt_first_payment; /* --}}
		<tr>
			<td class="add_popup"><input type="checkbox" data-id='{{ $delivery->id }}' class='verification'></td>
			<td class="add_popup">{{$num = $num +1}}</td>
			<td>{{ $delivery->client->name }}</td>
			<td>{{ $delivery->client->inn }}</td>
			<td class="add_popup">{{ $delivery->debtor->name }}</td>
			<td>{{ $delivery->debtor->inn }}</td>
			<td class="add_popup">{{ $delivery->waybill }}</td>
			<td  class="add_popup"nowrap>{{ number_format($delivery->waybill_amount,2,',',' ') }}</td>
			<td nowrap>{{ number_format($delivery->balance_owed,2,',',' ') }}</td>
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
			<td>
				@if ($delivery->date_of_invoice)
					{{ date('d/m/Y', strtotime($delivery->date_of_invoice)) }}
				@endif
			</td>
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
			<td nowrap>{{ $delivery->status }}</td>
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
			<!-- <td class="destroyDelivery">
				{{ Form::model($delivery, array('route' => array('delivery.destroy', $delivery->id), 'method' => 'DELETE')) }}
				{{ Form::button('<i class="fa fa-close"></i>', array('class'=>'', 'type'=>'submit')) }}
			{{ Form::close() }}
			</td> -->
		</tr>
	@empty
	<p>Поставок нет</p>
@endforelse
<tr style="background-color:rgb(179, 255, 255);">	
<td><b>Сумма</b></td>
<td colspan="6"></td>
<td nowrap><b><nobr>{{ number_format($sum_waybill_amount,2,',',' ') }}</nobr></b></td>
<td nowrap><b><nobr>{{ number_format($sum_balance_owed,2,',',' ') }}</nobr></b></td>		
<td nowrap><b><nobr>{{ number_format($sum_first_payment_amount,2,',',' ') }}</nobr></b></td>	
<td nowrap><b><nobr>{{ number_format($sum_remainder_of_the_debt_first_payment,2,',',' ') }}</nobr></b></td>
<td colspan="99"></td>
</tr>		