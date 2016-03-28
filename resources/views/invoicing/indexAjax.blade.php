@if(count($bills)>0)
	<?php
		$debtSum = 0;
		$fullDebtSum = 0;
	?>
	@foreach ($bills as $bill)
	<tr>
		<td>{{$bill->client->name}}</td>
		<td>{{$bill->agreement->code}}</td>
		<td>{{@date('d/m/Y',strtotime($bill->bill_date))}}</td>
		<td>{{$bill->id}}</td>
		<td>{{number_format($bill->without_nds,2,',',' ')}}</td>
		<td>{{number_format($bill->nds,2,',',' ')}}</td>
		<td>{{number_format($bill->with_nds,2,',',' ')}}</td>
		<td>{{number_format($bill->debt,2,',',' ')}}</td>
		<td>{{number_format($monthRepayment[$bill->agreement->id],2,',',' ')}}</td>
	</tr>
	@endforeach
	<tr style="background: #FFE68A;">
		<td><b>Итого</b></td>
		<td></td>
		<td></td>
		<td></td>
		<td>{{number_format($sum['without_nds'],2,',',' ')}}</td>
		<td>{{number_format($sum['nds'],2,',',' ')}}</td>
		<td>{{number_format($sum['with_nds'],2,',',' ')}}</td>
		<td></td>
		<td></td>
	</tr>
@elseif(count($stop)>0)<span>Идет расчет комиссий!!!</span>@else<span>С выбранными параметрами счетов нет!!!</span>@endif