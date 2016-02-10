<input type="hidden" id='tabsType' value='{{ $type }}'>
<table class="table table-striped" id='tableModal'>
  <thead>
	  <tr>
	  	<th><input type="checkbox"></th>
		<th>№</th>
	  	<th>Договор клиента</th>
	  	<th>Тип долга</th>
	  	<th>Остаток долга</th>
	  	<th>Сумма погашения</th>
	  </tr>
  </thead>
  <tbody>
  	@include('repayment.tableCommissionRow')
  </tbody>
</table>