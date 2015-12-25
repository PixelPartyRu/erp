@extends('layouts.master')

@section('title', 'Заголовок страницы')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/clients.css">
@stop

@section('content')
	<div class="panel panel-success">
		<div class="panel-heading">Связи клиент-дебитор</div>
		<div class="panel-body">
				{!! Form::open(array('action' => 'RelationController@store')) !!}
					<div class="row">
						<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label for="InputEmail1">Клиент:</label>
						  	{!! Form::select('client', array_pluck($clients, 'full_name', 'id')) !!}
						  	<label for="InputEmail1">Дебитор:</label>
						  	{!! Form::select('debtor', array_pluck($debtors, 'full_name', 'id')) !!}
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<label for="rpp">Коэфициент финансирования:</label>
						  	{!! Form::text('rpp',null,array('class' => 'form-control','id' => 'rpp')) !!} <span>%</span>
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
				  		<th>Полное наименование</th>
				  		<th>Наименование</th>
				  		<th>ИНН</th>
				  		<th>КПП</th>
				  		<th>ОГРН</th>
				  		<th></th>
				  		<th></th>
				  	</tr>
				  </thead>
				  <tbody>
				  </tbody>
				</table>
			</div>
		</div>
	</div>

@stop

<!--, array('class' => 'btn btn-warning'-->