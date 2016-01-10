<div class="row">
		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="payer">Платильщик:</label>
		  	{!! Form::select('payer', array('true' => 'Клиент', 'false' => 'Дебитор'), 'Клиент',array('class'=>'selectpicker')) !!}
		</div>
		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="nds">Начислять НДС</label>
			{!! Form::checkbox('nds', 'true', true, array('id'=>'nds'));!!}
			<div class="clearfix"></div>
			<label for="deduction">Удержать комиссию</label>
			{!! Form::checkbox('deduction', 'true', true, array('id'=>'deduction'));!!}
		</div>
		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="time_of_settlement">Момент расчета:</label>
		  	{!! Form::select('time_of_settlement', array('true' => 'При подтверждении финансирования', 'false' => 'При погашении накладной'), 'При подтверждении финансирования',array('class'=>'selectpicker')) !!}
		</div>
		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="rate_stitching">Размер комиссии:</label>
			<div class="row">
				<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
					{!! Form::text('commission_value',null,array('class' => 'form-control','id' => 'commission_value')) !!}
				</div>
				<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<span>рублей</span>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id='btn-container'>
			{!! Form::submit('Добавить',array('class' => 'btn btn-success')) !!}
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			{!! Session::get('message') !!}
		</div>
	</div>