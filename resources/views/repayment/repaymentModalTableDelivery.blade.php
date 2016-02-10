<input type="hidden" id='tabsType' value='{{ $type }}'>
<table class="table table-striped" id='tableModal'>
  <thead>
	  <tr>
	  	<th><input type="checkbox"></th>
		<th>№</th>
	  	<th>Клиент</th>
	  	<th>ИНН клиента</th>
	  	<th>Дебитор</th>
	  	<th>ИНН дебитора</th>
	  	<th>Накладная</th>
	  	<th>Сумма накладной</th>
		<th>Остаток долга</th>
		<th>Сумма погашения</th>
	  	<th>Сумма первого платежа</th>
	  	<th>Остаток первого платежа</th>
	  	<th>Дата накладной</th>
	  	<th>Отсрочка</th>
	  	<th>Срок оплаты</th>
	  	<th>Дата оплаты</th>
	  	<th>Дата регресса</th>
	  	<th>Дата окончания регресса</th>
	  	<th>Дата рег. поставок</th>
	  	<th>Просрочка</th>
	  	<th>Счет фактура</th>
	  	<th>Дата сч. ф.</th>
	  	<th>Реестр</th>
	  	<th>Дата реестра</th>
	  	<th>Дата финансирования</th>
	  	<th>Дата погашения финансирования</th>
	  	<th>Заметки</th>
	  	<th>Погасил</th>
	  	<th>Состояние</th>
	  	<th>Статус</th>
	  	<th>Наличие оригинала документа</th>
	  	<th>Тип факторинга</th>
	  </tr>
  </thead>
  <tbody id="repaymentModalDeliveryTableBody">
  	@include('repayment.repaymentModalContentRowDelivery')
  </tbody>
</table>
