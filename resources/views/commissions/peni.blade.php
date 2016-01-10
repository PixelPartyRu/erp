<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="payer">Платильщик:</label>
		  	{!! Form::select('payer', array('true' => 'Клиент', 'false' => 'Дебитор'), 'Клиент',array('class'=>'selectpicker')) !!}
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="nds">Начислять НДС</label>
			{!! Form::checkbox('nds', 'true', true, array('id'=>'nds'));!!}
			<div class="clearfix"></div>
			<label for="deduction">Удержать комиссию</label>
			{!! Form::checkbox('deduction', 'true', true, array('id'=>'deduction'));!!}
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="additional_sum">Начислять на сумму:</label>
		  	{!! Form::select('additional_sum', array('true' => 'Уступки', 'false' => 'Финанса'), 'При подтверждении финансирования',array('class'=>'selectpicker')) !!}
		</div>
		<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			<label for="rate_stitching">% ставка в период отсрочки:</label>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9" id="range_form">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 range_form_filds">
						<div class="row" >
							<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 delete_rage">
								<i class="fa fa-minus rage_controls"></i>
							</div>
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
							{!! Form::text('range_commission_min[]','0',array('class' => 'form-control','id' => 'commission_value','placeholder'=>'от')) !!}
							<span class="input_tag">&#x2012</span>
							</div> 
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
								{!! Form::text('range_commission_max[]',null,array('class' => 'form-control','id' => 'commission_value','placeholder'=>'до')) !!}
								<span class="input_tag">д.</span>
							</div>
							<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
								{!! Form::text('range_commission_value[]',null,array('class' => 'form-control','id' => 'commission_value','placeholder'=>'значение')) !!}
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 add_rage">
						<div class="row">
							<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
								<i class="fa fa-plus rage_controls"></i>
							</div>
							<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
							<span style="line-height: 34px;">Добавить диапозон</span>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					{!! Form::select('rate_stitching', array('true' => '% в день', 'false' => '% годовых'), '% в день',array('class'=>'selectpicker')) !!}
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id='btn-container'>
			{!! Form::submit('Добавить',array('class' => 'btn btn-success')) !!}
		</div>
		<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
			{!! Session::get('message') !!}
		</div>
	</div>