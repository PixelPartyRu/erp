@extends('layouts.master')

@section('title', 'Заголовок страницы')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/clients.css">
@stop

@section('content')
	<div class="panel panel-success">
		<div class="panel-heading">Заполните данные клиента</div>
		<div class="panel-body">
				{!! Form::open(array('action' => 'DebtorController@store')) !!}
					<div class="row">
						<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label for="InputEmail1">Полное наименование:</label>
						  	{!! Form::text('full_name',null,array('class' => 'form-control','id' => 'InputEmail1')) !!}
						</div>		
						<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label for="InputEmail2">Наименование:</label>
						  	{!! Form::text('name',null,array('class' => 'form-control','id' => 'InputEmail2')) !!}
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<label for="InputEmail3">ИНН:</label>
						  	{!! Form::text('inn',null,array('class' => 'form-control','id' => 'InputEmail3')) !!}
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<label for="InputEmail4">КПП:</label>
						  	{!! Form::text('kpp',null,array('class' => 'form-control','id' => 'InputEmail4')) !!}
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<label for="InputEmail5">КПП:</label>
						  	{!! Form::text('ogrn',null,array('class' => 'form-control','id' => 'InputEmail5')) !!}
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" id='btn-container'>
						  	{!! Form::submit('Добавить',array('class' => 'btn btn-success')) !!}
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							{!! Session::get('message') !!}
						</div>
					</div>
				{!! Form::close() !!}
  		</div>
	</div>
	<div class="panel panel-info">
		<div class="panel-heading">Наши клиенты</div>
			<div class="table-responsive">
				<table class="table table-striped" id="debtor-table">
				  <thead>
				  	<tr>
				  		<th>№</th>
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
				  	@forelse($debtors as $debtor)
						<tr>
							<td>{{ $debtor->id }}</td>
							<td>{{ $debtor->full_name }}</td>
							<td>{{ $debtor->name }}</td>
							<td>{{ $debtor->inn }}</td>
							<td>{{ $debtor->kpp }}</td>
							<td>{{ $debtor->ogrn }}</td>
							<td><a href="/debtor/{{ $debtor->id }}/edit"><i class="fa fa-pencil"></i></a></td>
							<td>
								{{ Form::model($debtor, array('route' => array('debtor.destroy', $debtor->id), 'method' => 'DELETE')) }}
									{{ Form::button('<i class="fa fa-close"></i>', array('class'=>'', 'type'=>'submit')) }}
								{{ Form::close() }}
							</td>
						</tr>
					@empty
						<p>Клиентов нет</p>
					@endforelse
				  </tbody>
				</table>
			</div>
		</div>
	</div>

@stop

<!--, array('class' => 'btn btn-warning'-->