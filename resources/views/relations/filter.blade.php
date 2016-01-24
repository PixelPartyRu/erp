<div class="row">
	<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-2">
		<label for="filterClientInn">Клиент (ИНН)</label>
		{!! Form::select('filterClientInn', ['0' => 'Клиент (ИНН)'] + select_inn($clients, 'name', 'id'),'0', array('class' => 'form-control','id' => 'filterClientInn'))!!}
	</div>
	<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-2">
		<label for="filterDebtorInn">Дебитор (ИНН)</label>	
		{!! Form::select('filterDebtorInn', ['0' => 'Дебитор (ИНН)'] + select_inn($debtors, 'name', 'id'),'0', array('class' => 'form-control','id' => 'filterDebtorInn'))!!}
	</div>
	<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-2">
			<label for="filterDebtorInn">Статус</label>	
			{!! Form::select('filterActive', ['0' => 'Все','1'=>'Активные','2'=>'Не активные'],'0', array('class' => 'form-control','id' => 'filterActive'))!!}
	</div>
	<div class="filter_bottom">
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-2">
			<input type="button" class="btn btn-success" value="Обновить" id="filterUpdate">
		</div>
	</div>
</div>