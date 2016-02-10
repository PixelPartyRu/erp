<?php $num = 0; ?>
@forelse($deliveries as $delivery)
	<?php $fixed = $delivery->chargeCommission->fixed_charge + $delivery->chargeCommission->fixed_charge_nds; ?>
	@if ($fixed > 0)
		<tr>
			<td><input name='deliveryChoice' type="checkbox" value='{{$delivery->id}}' data-type="commission"></td>
			<td>{{ $num++ }}</td>
			<td>{{ $delivery->relation->contract->code }}</td>
			<td>Комиссия по поставке</td>
			<td><nobr>{{ number_format($fixed,2,',',' ') }}</nobr></td>
			<td nowrap>
				<span class="repaymentSum">{{ number_format(0,2,',',' ') }}</span>
				<input class="repaymentSumHidden" type="hidden" value="0">
			</td>
		</tr>
	@endif
	<?php $debt = $delivery->chargeCommission->debt - $fixed; ?>
	@if ($debt > 0)
		<tr>
			<td><input name='deliveryChoice' type="checkbox" value='{{$delivery->id}}' data-type="percent"></td>
			<td>{{ $num++ }}</td>
			<td>{{ $delivery->relation->contract->code }}</td>
			<td>Проценты по поставке</td>
			<td><nobr>{{ number_format($debt,2,',',' ') }}</nobr></td>
			<td nowrap>
				<span class="repaymentSum">{{ number_format(0,2,',',' ') }}</span>
				<input class="repaymentSumHidden" type="hidden" value="0">
			</td>
		</tr>
	@endif
@empty
	<tr>
		<td>Коммиссии отсутствуют</td>
	</tr>
@endforelse