@forelse($finances as $finance)
	<tr>
		<td><input type="checkbox" data-id='{{ $finance->id }}' class='financeChoice'></td>
		<td class="financeTdClick">{{ $finance->client }}</td>
		<td class="financeSum financeTdClick">{{ number_format($finance->sum,2,',',' ') }} </td>
		<td class="financeTdClick">{{ $finance->number_of_waybill }}</td>
		<td class="financeTdClick">{{ $finance->type_of_funding }}</td>
		<td class="financeTdClick">
			@if ($finance->date_of_funding)
				{{ date('d/m/Y', strtotime($finance->date_of_funding)) }}
			@endif
		</td>
		<td class="financeTdClick">{{ $finance->registry }}</td>
		<td class="financeTdClick">{{ date('d/m/Y', strtotime($finance->date_of_registry)) }}</td>
		<td class="financeTdClick">{{ $finance->status }}</td>
	</tr>
@empty
	<tr><td>Поставок нет</td></tr>
@endforelse