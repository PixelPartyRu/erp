<div id="popup" class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<label for="verificationDate">Дата верефикации</label>
			<input type="date" id="verificationDate" value='{{ $dateToday }}' class="form-control">
		</div>
	</div>
	<div class="row">	
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<input type="button" id="financeBtn" value="Финансирование" class="form-control btn btn-success">
		</div>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<input type="button" id="verificationBtn" value="Верификация" class="form-control btn btn-success">
		</div>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<input type="button" id="notVerificationBtn" value="Не верефицировать" class="form-control btn btn-success">
		</div>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<input type="button" id="popupClose" value="Отменить" class="form-control btn btn-success">
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-striped">
		  <thead>
			  <tr>
			  	<th>Верификация</th>
			  	<th>Клиент</th>
			  	<th>Инн клиента</th>
			  	<th>Дебитор</th>
			  	<th>Инн дебитора</th>
			  	<th>Накладная</th>
			  	<th>Сумма накладной</th>
			  	<th>Сумма первого платежа</th>
			  	<th>Остаток долга</th>
			  	<th>Остаток долга первого платежа</th>
			  	<th>Дата накладной</th>
			  	<th>Отсрочка</th>
			  	<th>Срок оплаты</th>
			  	<th>Дата оплаты</th>
			  	<th>Дата регресса</th>
			  	<th>Дата окончания периода регресса</th>
			  	<th>Дата рег. поставок</th>
			  	<th>Фактическая просрочка</th>
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
		  <tbody id="popup-table">
		  </tbody>
		</table>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<label for="verificationComents">Коментарии</label>
			<textarea name="verificationComents" readonly id="verificationComents" class="form-control" cols="30" rows="10"></textarea>
		</div>
	</div>
</div>