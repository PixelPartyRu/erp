<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		{!! Form::select('filterActive', ['0' => 'Все','1'=>'Действующие','2'=>'Недействующие'],'0', array('class' => 'form-control'))!!}
	</div>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<input type="button" class="btn btn-success" value="Обновить" id="client-filter-update">
	</div>
</div>