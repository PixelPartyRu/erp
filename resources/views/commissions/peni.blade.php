<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="payer">Плательщик:</label>
		  	{!! Form::select('payer', array('0' => 'Клиент', '1' => 'Дебитор'), isset($commission->payer)?$commission->payer:'0',array('class'=>'selectpicker')) !!}
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="nds">Начислять НДС</label>
			{!! Form::checkbox('nds', 'true', isset($commission->nds)?$commission->nds:false, array('id'=>'nds'));!!}
			<div class="clearfix"></div>
			<label for="deduction">Удержать комиссию</label>
			{!! Form::checkbox('deduction', 'true', isset($commission->deduction)?$commission->deduction:false, array('id'=>'deduction'));!!}
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="additional_sum">Начислять на сумму:</label>
		  	{!! Form::select('additional_sum', array('0' => 'Финанса', '1' => 'Уступки'), isset($commission->additional_sum)?$commission->additional_sum:'0',array('class'=>'selectpicker')) !!}
		</div>
		<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			<label for="rate_stitching">% ставка в период просрочки:</label>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9" id="range_form">
				@if ( isset($commission))
					@forelse($commission->commissionsRages as $commissionsRage)
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 range_form_filds">
						<div class="row" >
							<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 delete_rage">
								<i class="fa fa-minus rage_controls"></i>
								{{ Form::hidden('range_commission_id[]', $commissionsRage->id) }}
							</div>
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
							{!! Form::text('range_commission_min[]',$commissionsRage->min,array('class' => 'form-control','id' => 'commission_value','placeholder'=>'от')) !!}
							<span class="input_tag">&#x2012</span>
							</div> 
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
								{!! Form::text('range_commission_max[]',$commissionsRage->max,array('class' => 'form-control','id' => 'commission_value','placeholder'=>'до')) !!}
								<span class="input_tag">д.</span>
							</div>
							<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
								{!! Form::text('range_commission_value[]',$commissionsRage->value,array('class' => 'form-control','id' => 'commission_value','placeholder'=>'значение')) !!}
							</div>
						</div>
					</div>
					@empty
					@endforelse
					@else
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
					@endif
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 add_rage">
						<div class="row">
							<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
								<i class="fa fa-plus rage_controls"></i>
							</div>
							<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
							<span style="line-height: 34px;">Добавить диапазон</span>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					{!! Form::select('rate_stitching', array('0' => '% в день', '1' => '% годовых'), isset($commission->rate_stitching)?$commission->rate_stitching:'0',array('class'=>'selectpicker')) !!}
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id='btn-container'>
			{!! Form::submit('Сохранить',array('class' => 'btn btn-success')) !!}
		</div>
	</div>