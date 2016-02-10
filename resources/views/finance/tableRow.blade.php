<?php $sum = 0 ?>
<input type="hidden" id="sum" value="{{$sum}}">
@forelse($finances as $finance)
	<tr  class="financeTrClick">
		<td><input type="checkbox" data-id='{{ $finance->id }}' class='financeChoice'></td>
		<td>{{ $finance->client }}</td>
		<td class="financeSum">{{ number_format($finance->sum,2,',',' ') }} </td>
		<?php $sum +=  $finance->sum ?> 
		<td>{{ $finance->number_of_waybill }}</td>
		<td>{{ $finance->type_of_funding }}</td>
		<td>
			@if ($finance->date_of_funding)
				{{ date('d/m/Y', strtotime($finance->date_of_funding)) }}
			@endif
		</td>
		<td>{{ $finance->registry }}</td>
		<td>{{ date('d/m/Y', strtotime($finance->date_of_registry)) }}</td>
		<td>{{ $finance->status }}</td>
		<td>
			<span data-id="{{ $finance->id }}" class="fa fa-list-alt deliveryOpenModal"></span>
		</td>
	</tr>
@empty
	<tr><td>Поставок нет</td></tr>
@endforelse
<tr>
	<td></td>
	<td><strong>Итого:</strong></td>
	<td><strong>{{ number_format($sum,2,',',' ') }}</strong></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
</tr>