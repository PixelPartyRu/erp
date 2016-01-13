<div class="row">
		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="payer">Плательщик:</label>
		  	{!! Form::select('payer', array('0' => 'Клиент', '1' => 'Дебитор'), isset($commission->payer)?$commission->payer:'0',array('class'=>'selectpicker')) !!}
		</div>
		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="nds">Начислять НДС</label>
			{!! Form::checkbox('nds', 'true', isset($commission->nds)?$commission->nds:false, array('id'=>'nds'));!!}
			<div class="clearfix"></div>
			<label for="deduction">Удержать комиссию</label>
			{!! Form::checkbox('deduction', 'true', isset($commission->deduction)?$commission->deduction:false, array('id'=>'deduction'));!!}
		</div>
		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="additional_sum">Начислять на сумму:</label>
		  	{!! Form::select('additional_sum', array('0' => 'Финанса', '1' => 'Уступки'), isset($commission->additional_sum)?$commission->additional_sum:'0',array('class'=>'selectpicker')) !!}
		</div>
		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="rate_stitching">% ставка в период отсрочки:</label>
			<div class="row">
				<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
					{!! Form::text('commission_value',isset($commission->commission_value)?$commission->commission_value:null,array('class' => 'form-control','id' => 'commission_value')) !!}
				</div>
				<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
					{!! Form::select('rate_stitching', array('0' => '% в день', '1' => '% годовых'), isset($commission->rate_stitching)?$commission->rate_stitching:'0',array('class'=>'selectpicker')) !!}
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id='btn-container'>
			{!! Form::submit('Сохранить',array('class' => 'btn btn-success')) !!}
		</div>
		<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
			{!! Session::get('message') !!}
		</div>
	</div>