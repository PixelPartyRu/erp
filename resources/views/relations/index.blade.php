@extends('layouts.master')

@section('title', 'Связи')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/clients.css">
@stop

@section('content')
	<div class="panel panel-success">
		<div class="panel-heading">Связи клиент-дебитор</div>
		<div class="panel-body">
				{!! Form::open(array('action' => 'RelationController@store')) !!}
					<div class="row">
						<div id="relation_selectors" class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<label for="client_id">Клиент:</label>
						  	{!! Form::select('client_id',['0' => 'Выбрать клиента'] + array_pluck($clients, 'full_name', 'id')) !!}
							<label for="debtor_id"> Дебитор:</label>
						  	{!! Form::select('debtor_id',['0' => 'Выбрать дебитора'] + array_pluck($debtors, 'full_name', 'id')) !!}
						  	<label for="active"> Активно</label>
							{!! Form::checkbox('active', 'true', true);!!}

						</div>
						<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
							<label for="created_at">Условия вступают в силу:</label>
						  	{!! Form::date('created_at') !!}
						</div>
						<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
							<label for="rpp">Коэфициент финансирования(%):</label>
						  	{!! Form::text('rpp',null,array('class' => 'form-control','id' => 'rpp')) !!}
						</div>
						<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
							<label for="confedential_factoring">Конфеденциальный факторинг:</label>
							{!! Form::checkbox('confedential_factoring', 'true');!!}
						</div>
						<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
							<label for="deferment_start">Отсчет начала отсрочки:</label>
							{!! Form::select('size', array('true' => 'Дата накладной', 'false' => 'Дата финансирования'), 'true') !!}
						</div>
						<div class="clearfix"></div>
						<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
							<label for="deferment">Отсрочка:</label>
						  	{!! Form::text('deferment',null,array('class' => 'form-control','id' => 'deferment')) !!}
						  	{!! Form::select('deferment_type', array('Календарных дней' => 'Календарных дней', 'Банковских дней' => 'Банковских дней'), 'Календарных дней') !!}
						</div>
						<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
							<label for="deferment">Период ожидания:</label>
						  	{!! Form::text('waiting_period',null,array('class' => 'form-control','id' => 'waiting_period')) !!}
						  	{!! Form::select('waiting_period_type', array('Календарных дней' => 'Календарных дней', 'Банковских дней' => 'Банковских дней'), 'Календарных дней') !!}
						</div>
						<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
							<label for="regress_period">Период регресса:</label>
						  	{!! Form::text('regress_period',null,array('class' => 'form-control','id' => 'regress_period')) !!}
						  	{!! Form::select('regress_period_type', array('Календарных дней' => 'Календарных дней', 'Банковских дней' => 'Банковских дней'), 'Календарных дней') !!}
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label for="original_documents_select">Оригиналы первичных документов:</label>
							<div class="clearfix"></div>
							<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
							  	{!! Form::select('original_documents_select', array('0' => 'Финансирование по оригиналам', '1' => 'Нет', '2' => 'Первичные документы через'), '0') !!}
							</div>
							<div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4" id="o_documents_value">
								{!! Form::text('original_documents_value',null,array('class' => 'form-control','id' => 'original_documents_value')) !!}	
							</div>										
						</div>
						<div class="clearfix"></div>
						<h4>Контракт</h4>
						<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<label for="contract_code">Номер договора:</label>
							  	{!! Form::text('contract_code',null,array('class' => 'form-control','id' => 'contract_code')) !!}
							</div>
							<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<label for="contract_name">Наименование:</label>
							  	{!! Form::text('contract_name',null,array('class' => 'form-control','id' => 'contract_name')) !!}
							</div>
							<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<label for="contract_code_1c">Номер договора для 1С:</label>
							  	{!! Form::text('contract_code_1c',null,array('class' => 'form-control','id' => 'contract_code_1c')) !!}
							</div>
							<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<label for="contract_gd_debitor_1c">Номер ГД (дебитора) для 1С:</label>
							  	{!! Form::text('contract_gd_debitor_1c',null,array('class' => 'form-control','id' => 'contract_gd_debitor_1c')) !!}
							</div>
							<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<label for="contract_created_at">Дата договора:</label>
							  	{!! Form::date('contract_created_at') !!}
							</div>
							<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<label for="contract_date_end">Действителен до:</label>
							  	{!! Form::date('contract_date_end') !!}
							</div>
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label for="contract_description">Коментарии:</label>
						  	{!! Form::textarea('contract_description',null,array('class' => 'form-control','id' => 'contract_description')) !!}
						</div>
						<div class="clearfix"></div>
						<h4>Тариф</h4>
						<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
						  	{!! Form::select('tariff_id',['0' => 'Выбрать тариф'] + array_pluck($tariffs, 'name', 'id')) !!}
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							{!! Form::submit('Создать связь') !!}
							{!! Session::get('message') !!}
						</div>
					</div>
				{!! Form::close() !!}
  		</div>
	</div>
	<div class="panel panel-info">
		<div class="panel-heading">Связи</div>
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped" id="client-table">
				  <thead>
				  	<tr>
				  		<th>Связь</th>
				  		<th>Статус</th>
				  		<th>Коэффициент финансирования %</th>
				  		<th>Отсчет начала отсрочки</th>
				  		<th>Отсрочка</th>
				  		<th>Период ожидания</th>
				  		<th>Период регресса</th>
				  		<th>Оригиналы первичных документов</th>

				  		<th></th>
				  	</tr>
				  </thead>
				  <tbody>
				  	@forelse($relations as $relation)
						<tr>
							<td>{{ $relation->debtor->name }}<span>&nbsp&#x2012&nbsp</span>{{ $relation->client->name }}</td>
							<td>{{ $relation->active == true ? 'Активна' : 'Не активна' }}</td>
							<td>{{ $relation->rpp}}</td>
							<td>{{ $relation->deferment_start == true ? 'Дата накладной' : 'Дата финансирования' }}</td>
							<td>{{ $relation->deferment }}<span>&nbsp</span>{{ $relation->deferment_type }}</td>
							<td>{{ $relation->waiting_period }}<span>&nbsp</span>{{ $relation->waiting_period_type }}</td>
							<td>{{ $relation->regress_period }}<span>&nbsp</span>{{ $relation->regress_period_type }}</td>
							<td>
								@if ($relation->originalDocument->type == '0')
								По оригиналам
								@elseif($relation->originalDocument->type == '1')
								Нет
								@else
								через &nbsp {{$relation->originalDocument->value}} &nbsp дней
								@endif

							</td>
							<td><a href="/relation/{{ $relation->id }}/edit"><i class="fa fa-pencil"></i></a></td>

						</tr>
					@empty
						<p>Связей нет</p>
					@endforelse
				  </tbody>
				</table>
			</div>
		</div>
	</div>
<script type="text/javascript" src="/assets/js/relations.js"></script>
@stop

<!--, array('class' => 'btn btn-warning'-->