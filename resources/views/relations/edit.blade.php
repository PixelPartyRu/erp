@extends('layouts.master')

@section('title', 'Редактирование связи')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/relations.css">
		<link rel="stylesheet" type="text/css" href="/assets/css/form.css">
@stop
@section('content')
	<h1><strong>Редактирование связи</strong></h1>
    <div class="panel panel-success">
		<div class="panel-heading">Редактирование связи</div>
		<div class="panel-body">
				{!! Form::model($relation, array('route' => array('relation.update', $relation->id), 'method' => 'PUT')) !!}
					<div class="row">
						<div class="form-group col-xs-3 col-sm-3 col-md-3 col-lg-2"> 
						  	{!! Form::select('client_view',array($client->id => $client->name.'('.$client->inn.')'),$relation->client->id,array('class'=>'selectpicker','disabled')) !!}
						</div>
						<div class="form-group col-xs-3 col-sm-3 col-md-3 col-lg-2"> 	
						  	{!! Form::select('debtor_view',array($debtor->id => $debtor->name.'('.$debtor->inn.')'),$relation->debtor->id,array('class'=>'selectpicker','disabled')) !!}
						</div>
						<div class="form-group col-xs-3 col-sm-3 col-md-3 col-lg-2" id="relation_selectors" style=""> 
							{!! Form::select('agreement_id',array_pluck($client->agreements,'code','id'),0, array('class'=>'selectpicker','id'=>'agreement_id')) !!}
						</div>  	
						<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
							<label for="created_at">Условия вступают в силу:</label>
						  	{!! Form::date('created_at',$relation->created_at, array('class'=>'inline')) !!}
						</div>
						<div class="form-group col-xs-3 col-sm-3 col-md-3 col-lg-3" id="act">
						<label for="active"> Активно</label>
							{!! Form::checkbox('active', 'true', $relation->active);!!}
						
						<label for="confedential_factoring">Конфеденциальный факторинг:</label>
							{!! Form::checkbox('confedential_factoring', $relation->confedential_factoring);!!}
						</div>
					</div>	
						<div class="panel panel-success">
							<div class="panel-heading">Условия связи</div>
							<div class="panel-body">
								<div class="row">	
						
									<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
										<label for="rpp">Коэфициент финансирования(%):</label>
										{!! Form::text('rpp',$relation->rpp,array('class' => 'form-control small_checkbox inline','id' => 'rpp')) !!}
									</div>
									<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-4">
										<label for="deferment_start">Отсчет начала отсрочки:</label>
										@if( $relation->deferment_start )
											{!! Form::select('size', array('true' => 'Дата накладной', 'false' => 'Дата финансирования'), 'true', array('class'=>'selectpicker inline date_naklad')) !!}
										@else
											{!! Form::select('size', array('true' => 'Дата накладной', 'false' => 'Дата финансирования'), 'false', array('class'=>'selectpicker inline date_naklad')) !!}
										@endif				
									</div>
									<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-5">
									  	<label for="original_documents_select">Документы</label>
											{!! Form::select('original_documents_select', array('2' => 'Первичные документы через', '0' => 'Финансирование по оригиналам', '1' => 'Оригиналы по запросу'), $relation->originalDocument->type, array('class'=>'selectpicker inline document_finance','id'=>'original_documents_select')) !!}
											{!! Form::text('original_documents_value',$relation->originalDocument->value,array('class' => 'form-control   small_checkbox inline','id' => 'original_documents_value')) !!}
											дней
										</span>
									</div>
								</div>	
								<div class="row">
									<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-4">
										<label for="deferment">Отсрочка:</label>
										{!! Form::text('deferment',$relation->deferment,array('class' => 'form-control small_checkbox inline','id' => 'deferment')) !!}
										{!! Form::select('deferment_type', array('Календарных дней' => 'Календарных дней', 'Банковских дней' => 'Банковских дней'), $relation->deferment_type, array('class'=>'selectpicker select_period_type')) !!}
									</div>
									<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-4">
										<label for="deferment">Период ожидания:</label>
										{!! Form::text('waiting_period',$relation->waiting_period,array('class' => 'form-control small_checkbox inline','id' => 'waiting_period')) !!}
										{!! Form::select('waiting_period_type', array('Календарных дней' => 'Календарных дней', 'Банковских дней' => 'Банковских дней'), $relation->waiting_period_type,array('class'=>'selectpicker select_period_type')) !!}
									</div>
									<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-4">
										<label for="regress_period">Период регресса:</label>
										{!! Form::text('regress_period',$relation->regress_period,array('class' => 'form-control small_checkbox inline','id' => 'regress_period')) !!}
										{!! Form::select('regress_period_type', array('Календарных дней' => 'Календарных дней', 'Банковских дней' => 'Банковских дней'), $relation->regress_period_type,array('class'=>'selectpicker select_period_type')) !!}
									</div>
								</div>
							</div>	
						</div>						
						<div class="panel panel-success">
									<div class="panel-heading">Условия контракта</div>
									<div class="panel-body">
										<div class="row contract">
											<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-4">
												<div class="conctract_input_block"> 
													<label for="contract_code">Номер договора</label>
													{!! Form::text('contract_code',$relation->contract->code,array('class' => 'form-control inline input_contract','id' => 'contract_code')) !!}
												</div>
												<div class="conctract_input_block">
													<label for="contract_name">Наименование</label>
													{!! Form::text('contract_name',$relation->contract->name,array('class' => 'form-control inline input_contract','id' => 'contract_name')) !!}
												</div>
												<div class="conctract_input_block">
													<label for="contract_code_1c">Номер договора для 1С</label>
													{!! Form::text('contract_code_1c',$relation->contract->code_1c,array('class' => 'form-control inline input_contract','id' => 'contract_code_1c')) !!}
												</div>
											</div>
											<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-4">
												<div class="conctract_input_block">
													<label for="contract_gd_debitor_1c">Номер ГД (дебитора) для 1С</label>	
													{!! Form::text('contract_gd_debitor_1c',$relation->contract->gd_debitor_1c,array('class' => 'form-control inline input_contract','id' => 'contract_gd_debitor_1c')) !!}
												</div>
												<div class="conctract_input_block">
													<label for="contract_created_at">Дата договора</label>
													{!! Form::date('contract_created_at',$relation->contract->created_at,array('class' => 'form-control inline input_contract')) !!}
												</div>
												<div class="conctract_input_block">
													<label for="contract_date_end">Действителен до</label>
													{!! Form::date('contract_date_end',$relation->contract->date_end,array('class' => 'form-control inline input_contract')) !!}
												</div>
											</div>
											<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-4">
												<label for="contract_description">Коментарии:</label>
												{!! Form::textarea('contract_description',$relation->contract->description,array('class' => 'form-control','id' => 'contract_description', 'rows'=>'5', 'placeholder'=>'Коментарии')) !!}
											</div>
										</div>
									</div>
								</div>			

						<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-3">
						  	{!! Form::select('tariff_id',['0' => 'Выбрать тариф'] + array_pluck($tariffs, 'name', 'id'),$relation->tariff_id,array('class'=>'selectpicker')) !!}
						</div>
						<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-3">		
							{!! Form::submit('Обновить связь',array('class' => 'btn btn-success')) !!}
							<a href='{{URL::route('relation.index')}}' class="btn btn-danger">Отменить</a>
							{!! Session::get('message') !!}
						</div>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
@stop