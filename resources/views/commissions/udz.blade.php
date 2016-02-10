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
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="payer">Плательщик:</label>
		  	{!! Form::select('payer', array('0' => 'Клиент', '1' => 'Дебитор'), isset($commission->payer)?$commission->payer:'0',array('class'=>'selectpicker')) !!}
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="nds">Начислять НДС</label>
			{!! Form::checkbox('nds', 'true', isset($commission->nds)?$commission->nds:true, array('id'=>'nds'));!!}
			<div class="clearfix"></div>
			<label for="deduction">Удержать комиссию</label>
			{!! Form::checkbox('deduction', 'true', isset($commission->deduction)?$commission->deduction:true, array('id'=>'deduction'));!!}
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label for="additional_sum">Начислять на сумму:</label>
		  	{!! Form::select('additional_sum', array('0' => 'Финанса', '1' => 'Уступки'), isset($commission->additional_sum)?$commission->additional_sum:'0',array('class'=>'selectpicker')) !!}
		</div>
		<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			<label for="rate_stitching">% ставка в период отсрочки:</label>
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
							{!! Form::hidden('range_commission_min[]',$commissionsRage->min,array('class' => 'range_commission_min form-control float_mask','id' => 'commission_value','placeholder'=>'от')) !!}
							{!! Form::hidden('range_commission_max[]',$commissionsRage->max,array('class' => 'range_commission_max form-control float_mask','id' => 'commission_value','placeholder'=>'до')) !!}
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 commissions_rage_text">
								<span class='rageText text_in_forms'>
								@if($commissionsRage->min==0 && $commissionsRage->max==0)
									С первого дня
								@else
									@if($commissionsRage->min==0 && $commissionsRage->max!==0)
										до {{$commissionsRage->max}} дн.
									@else
										@if($commissionsRage->min!==0 && $commissionsRage->max==0)
											от {{$commissionsRage->min}} дн.
										@else
											{{$commissionsRage->min}} - {{$commissionsRage->max}} дн.
										@endif
									@endif
								@endif
								</span>
							</div>
							<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
								{!! Form::text('range_commission_value[]',$commissionsRage->value,array('class' => 'form-control float_mask','id' => 'commission_value','placeholder'=>'значение','required' => 'required')) !!}
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
								{!! Form::hidden('range_commission_min[]','0',array('class' => 'range_commission_min')) !!}
								{!! Form::hidden('range_commission_max[]',null,array('class' => 'range_commission_max')) !!}
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 commissions_rage_text">
								<span class='rageText text_in_forms'>C первого дня</span>
							</div>
							<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
								{!! Form::text('range_commission_value[]',null,array('class' => 'form-control float_mask','required' => 'required')) !!}
							</div>
						</div>
					</div>
					@endif
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 add_rage">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<span class="text_in_forms">Добавить диапозон:</span>
							</div>
							<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
								<span class="text_in_forms">на </span>
							</div>
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
								{!! Form::text('range_commission_days',null,array('class' => 'range_commission_days form-control float_mask')) !!}
							</div>
							<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
								<span class="text_in_forms">дней</span>
							</div>
							<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
								<i class="fa fa-plus-square-o fa-rage-add fa-2"></i>
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
			{!! Form::submit(isset($commission->nds)?'Сохранить':'Создать',array('class' => 'btn btn-success')) !!}
		</div>
	</div>
	{!! Form::close() !!}