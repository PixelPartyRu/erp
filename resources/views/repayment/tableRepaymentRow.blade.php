<?php $sum = 0 ?>
<?php $balance= 0 ?>
<thead>
	<tr>
		<th></th>
		<th>№</th>
		<th>Дата</th>
		<th>Инфо по корреспонденту</th>
		<th>ИНН кореспондента</th>
		<th>Клиент в системе</th>
		<th>Сумма</th>
		<th>Остаток</th>
		<th>Назначение платежа</th>
	</tr>
</thead>
<tbody>	
	@forelse($repayments as $repayment)
		<tr>
			<td>{{ Form::radio('fieldTable', $repayment->id, null, ['class' => 'fieldTable']) }}</td>
			<td>{{ $repayment->number }}</td>
			<td><nobr>{{ date('d/m/Y', strtotime($repayment->date)) }}</nobr></td>
			<td>{{ $repayment->info }}</td>
			<td><nobr>{{ $repayment->inn }}</nobr></td>	
			<td>
				{{ $repayment->client->name }}
			</td>	
			<td><nobr>{{ number_format($repayment->sum,2,',',' ') }}</nobr></td>
			<?php $sum +=  $repayment->sum ?> 
			<td><nobr class="repaymentIndexBalance">{{ number_format($repayment->balance,2,',',' ') }}</nobr></td>
			<?php $balance +=  $repayment->balance ?> 
			<td>{{ $repayment->purpose_of_payment }}</td>
			<td>
				@if ($repayment->balance != $repayment->sum)
					<i class="fa fa-close text-muted repaymentDelete" data-id='{{$repayment->id}}' title="Удалить"></i>
				@else
					<i class="fa fa-close text-danger repaymentDelete" data-id='{{$repayment->id}}' title="Удалить"></i>
				@endif
			</td>
		</tr>
	@empty
		<tr>
			<td>Платежей нет</td>
		</tr>
	@endforelse
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td><strong>Итого:</strong></td>
			<td><strong>{{ number_format($sum,2,',',' ') }}</strong></td>
			<td><strong>{{ number_format($balance,2,',',' ') }}</strong></td>
			<td></td>
			<td></td>
		</tr>
</tbody>
