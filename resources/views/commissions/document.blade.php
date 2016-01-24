	@if(isset($commission))
		{!! Form::model($commission, array('route' => array('commission.update', $commission->id), 'method' => 'PUT','class' => 'ajaxFormCommission','id' => 'ajaxFormCommissionEdit')) !!}
		<h5 class="text-info">Редактирование комиссии "{{$commission->name}}"</h5>
	@else
		{!! Form::open(array('action' => 'CommissionController@store','class' => 'ajaxFormCommission')) !!}
		{{ Form::hidden('tariff_id', $tariff_id)}}
		{{ Form::hidden('name', $commissionName)}}
		{{ Form::hidden('commission_type', $commission_type)}}
		<h5 class="text-info">Создание комиссии "{{$commissionName}}"</h5>
	@endif

	<div class="row">

		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="payer">Плательщик:</label>
		  	{!! Form::select('payer', array('0' => 'Клиент', '1' => 'Дебитор'), isset($commission->payer)?$commission->payer:'0',array('class'=>'selectpicker')) !!}
		</div>
		<div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
			<label for="nds">Начислять НДС</label>
			{!! Form::checkbox('nds', 'true', isset($commission->nds)?$commission->nds:false, array('id'=>'nds'));!!}
			<div class="clearfix"></div>
			<label for="deduction">Удержать комиссию</label>
			{!! Form::checkbox('deduction', 'true', isset($commission->deduction)?$commission->deduction:false, array('id'=>'deduction'));!!}
		</div>
		<div class="form-group col-xs-12 col-sm-6 col-md-5 col-lg-5">
			<label for="time_of_settlement">Момент расчета:</label>
		  	{!! Form::select('time_of_settlement', array('0' => 'При подтверждении финансирования', '1' => 'При погашении накладной'), isset($commission->time_of_settlement)?$commission->time_of_settlement:'0',array('class'=>'selectpicker')) !!}
		</div>
		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="rate_stitching">Размер комиссии:</label>
			<div class="row">
				<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
					{!! Form::text('commission_value',isset($commission->commission_value)?str_replace('.', ',', $commission->commission_value):null,array('class' => 'form-control float_mask','id' => 'commission_value','required' => 'required')) !!}
				</div>
				<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<span>рублей</span>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id='btn-container'>
			{!! Form::submit(isset($commission->nds)?'Сохранить':'Создать',array('class' => 'btn btn-success')) !!}
		</div>
	</div>
	{!! Form::close() !!}