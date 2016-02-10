@forelse($finances as $finance)
	<tr class="financeTrPopupClick ActiveTableTrConst">
		<td><input type="checkbox" checked='checked' data-id='{{ $finance->id }}' class='financeChoicePopup'></td>
		<td>{{ $finance->client }}</td>
		<td class="financeSum">{{ number_format($finance->sum,2,',',' ') }} </td>
		<td>{{ $finance->number_of_waybill }}</td>
		<td>{{ $finance->type_of_funding }}</td>
		<td>{{ $finance->registry }}</td>
		<td>{{ date('d/m/Y', strtotime($finance->date_of_registry)) }}</td>
	</tr>
@empty
	<tr><td>Финансирование отсутствует</td></tr>
@endforelse