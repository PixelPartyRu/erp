@extends('layouts.master')

@section('title', 'Редактирование связи')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/clients.css">
@stop
@section('content')
	<h1><strong>Редактирование связи</strong></h1>
    <div class="panel panel-success">
		<div class="panel-heading">Редактирование связи</div>
		<div class="panel-body">
			<div class="row">
				{!! Form::model($relation, array('route' => array('relation.update', $relation->id), 'method' => 'PUT')) !!}
					<div class="row">
						<div id="relation_selectors" class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<label for="client_id">Клиент:</label>
						  	{!! Form::select('client_id',array_pluck($clients, 'full_name', 'id'),$relation->client->id) !!}
							<label for="debtor_id"> Дебитор:</label>
						  	{!! Form::select('debtor_id',array_pluck($debtors, 'full_name', 'id'),$relation->debtor->id) !!}
						  	<label for="active"> Активно</label>
							{!! Form::checkbox('active', 'true', $relation->active);!!}

						</div>
						<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
							<label for="created_at">Условия вступают в силу:</label>
						  	{!! Form::date('created_at',$relation->created_at) !!}
						</div>
						<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
							<label for="rpp">Коэфициент финансирования(%):</label>
						  	{!! Form::text('rpp',$relation->rpp,array('class' => 'form-control','id' => 'rpp')) !!}
						</div>
						<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
							<label for="confedential_factoring">Конфеденциальный факторинг:</label>
							{!! Form::checkbox('confedential_factoring', $relation->confedential_factoring);!!}
						</div>
						<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
							<label for="deferment_start">Отсчет начала отсрочки:</label>
							@if( $relation->deferment_start )
								{!! Form::select('size', array('true' => 'Дата накладной', 'false' => 'Дата финансирования'), 'true') !!}
							@else
								{!! Form::select('size', array('true' => 'Дата накладной', 'false' => 'Дата финансирования'), 'false') !!}
							@endif				
						</div>
						<div class="clearfix"></div>
						<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
							<label for="deferment">Отсрочка:</label>
						  	{!! Form::text('deferment',$relation->deferment,array('class' => 'form-control','id' => 'deferment')) !!}
						  	{!! Form::select('deferment_type', array('Календарных дней' => 'Календарных дней', 'Банковских дней' => 'Банковских дней'), $relation->deferment_type) !!}
						</div>
						<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
							<label for="deferment">Период ожидания:</label>
						  	{!! Form::text('waiting_period',$relation->waiting_period,array('class' => 'form-control','id' => 'waiting_period')) !!}
						  	{!! Form::select('waiting_period_type', array('Календарных дней' => 'Календарных дней', 'Банковских дней' => 'Банковских дней'), $relation->waiting_period_type) !!}
						</div>
						<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
							<label for="regress_period">Период регресса:</label>
						  	{!! Form::text('regress_period',$relation->regress_period,array('class' => 'form-control','id' => 'regress_period')) !!}
						  	{!! Form::select('regress_period_type', array('Календарных дней' => 'Календарных дней', 'Банковских дней' => 'Банковских дней'), $relation->regress_period_type) !!}
						</div>	
						<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label for="original_documents_select">Оригиналы первичных документов:</label>
							<div class="clearfix"></div>
							<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
									{!! Form::select('original_documents_select', array('0' => 'Финансирование по оригиналам', '1' => 'Нет', '2' => 'Первичные документы через'), $relation->originalDocument->type) !!}
							</div>
							<div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4" id="o_documents_value">
									{!! Form::text('original_documents_value',$relation->originalDocument->value,array('class' => 'form-control','id' => 'original_documents_value')) !!}
							</div>			
						</div>
						<div class="clearfix"></div>
						<h4>Контракт</h4>
						<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<label for="contract_code">Номер договора:</label>
							  	{!! Form::text('contract_code',$relation->contract->code,array('class' => 'form-control','id' => 'contract_code')) !!}
							</div>
							<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<label for="contract_name">Наименование:</label>
							  	{!! Form::text('contract_name',$relation->contract->name,array('class' => 'form-control','id' => 'contract_name')) !!}
							</div>
							<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<label for="contract_code_1c">Номер договора для 1С:</label>
							  	{!! Form::text('contract_code_1c',$relation->contract->code_1c,array('class' => 'form-control','id' => 'contract_code_1c')) !!}
							</div>
							<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<label for="contract_gd_debitor_1c">Номер ГД (дебитора) для 1С:</label>
							  	{!! Form::text('contract_gd_debitor_1c',$relation->contract->gd_debitor_1c,array('class' => 'form-control','id' => 'contract_gd_debitor_1c')) !!}
							</div>
							<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<label for="contract_created_at">Дата договора:</label>
							  	{!! Form::date('contract_created_at',$relation->contract->created_at) !!}
							</div>
							<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<label for="contract_date_end">Действителен до:</label>
							  	{!! Form::date('contract_date_end',$relation->contract->date_end) !!}
							</div>
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label for="contract_description">Коментарии:</label>
						  	{!! Form::textarea('contract_description',$relation->contract->description,array('class' => 'form-control','id' => 'contract_description')) !!}
						</div>
						<div class="clearfix"></div>
						<h4>Тариф</h4>
						<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
						  	{!! Form::select('tariff_id',['0' => 'Выбрать тариф'] + array_pluck($tariffs, 'name', 'id')) !!}
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
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