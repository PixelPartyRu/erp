<div id="popup" class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<label for="financingSuccessDate">Дата финансирования</label>
			<input type="date" id="financingSuccessDate" value='{{ $dateToday }}' class="form-control">
		</div>
	</div>
	<div class="row">	
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<input type="button" id="financingSuccessBtn" value="Подтвердить" class="form-control btn btn-success">
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<input type="button" id="popupClose" value="Отменить" class="form-control btn btn-success">
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-striped">
		  <thead>
			  <tr>
			  	 <tr>
				  	<th></th>
				  	<th>Клиент</th>
				  	<th>Сумма</th>
				  	<th>Количество накладных</th>
				  	<th>Тип финансирования</th>
				  	<th>Дата финансирования</th>
				  	<th>Реестр</th>
				  	<th>Дата реестра</th>
				  	<th>Статус</th>
				  </tr>
			  </tr>
		  </thead>
		  <tbody id="popup-table">
		  </tbody>
		</table>
	</div>
</div>