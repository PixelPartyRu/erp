<?php $sum = 1; ?>
<?php $sumWaybill = 0; ?>
<thead>
	<tr>
		<th>№</th>
		<th>Клиент</th>
		<th>Дебитор</th>
		<th>Накладная</th>
		<th><nobr>№ П/П</nobr></th>
		<th>Дата накладной</th>
		<th>Дата финансирования</th>
		<th>Сумма уступки</th>
		<th>Реестр</th>
		<th>Сумма погашения</th>
		<th>Сумма погашения Фин.</th>
		<th>Сумма погашения Ком.</th>
		<th>Дата погашения</th>
		<th>Вид погашения</th>
		<th>Изначальный остаток долга Фин.</th>
		<th>Остаток долга </th>
		<th>Остаток долга Фин.</th>
		<th>Остаток клиенту</th>
	</tr>
</thead>
<tbody>
	@forelse($commissions as $commission)
		<tr>
			<td>{{ $sum++ }}</td>
			<td>{{ $commission->delivery->client->name }}</td>
			<td>{{ $commission->delivery->debtor->name }}</td>
			<td>{{ $commission->delivery->waybill }}</td>
			<td>{{ $commission->repayment->number }}</td>
			<td>{{ date('d/m/Y', strtotime($commission->delivery->date_of_waybill))  }}</td>
			<td>{{ date('d/m/Y', strtotime($commission->delivery->date_of_funding)) }}</td>
			<td>{{ number_format($commission->delivery->waybill_amount,2,',',' ') }}</td>
			<?php $sumWaybill += $commission->delivery->waybill_amount; ?>
			<td>{{ $commission->delivery->registry }}</td>
			<td>{{ number_format($commission->repayment_sum,2,',',' ') }}</td>
			<td>{{ number_format($commission->first_payment_sum,2,',',' ') }}</td>
			<td>{{ number_format($commission->with_nds,2,',',' ') }}</td>
			<td>{{ $commission->created_at->format('Y/m/d') }}</td>
			<td>{{ $commission->type_of_payment }}</td>
			<td>{{ number_format($commission->first_payment_debt_before,2,',',' ') }}</td>
			<td>{{ number_format($commission->balance_owed_after,2,',',' ') }}</td>
			<td>{{ number_format($commission->first_payment_debt_after,2,',',' ') }}</td>
			<td>{{ number_format($commission->to_client,2,',',' ') }}</td>
		</tr>
	@empty
		<tr>
			<td>погашения не найдены</td>
		</tr>
	@endforelse
	@if (count($commissions) > 0)
		<tr class="in-total">
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>Итого:</td>
			<td>{{ number_format($sumWaybill,2,',',' ') }}</td>
			<td></td>
			<td>{{ number_format($commissions->sum('repayment_sum'),2,',',' ') }}</td>
			<td>{{ number_format($commissions->sum('first_payment_sum'),2,',',' ')  }}</td>
			<td>{{ number_format($commissions->sum('with_nds'),2,',',' ') }}</td>
			<td></td>
			<td></td>
			<td>{{ number_format($commissions->sum('first_payment_debt_before'),2,',',' ') }}</td>
			<td>{{ number_format($commissions->sum('balance_owed_after'),2,',',' ') }}</td>
			<td>{{ number_format($commissions->sum('first_payment_debt_after'),2,',',' ') }}</td>
			<td>{{ number_format($commissions->sum('to_client'),2,',',' ') }}</td>
		</tr>
	@endif
</tbody>