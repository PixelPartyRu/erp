@extends('layouts.master')

@section('title', 'Клиенты')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/clients.css">
@stop	

@section('javascript')
  	<script type="text/javascript" src="/assets/js/clients.js"></script>
@stop

@section('content')
	<h1><strong>Клиенты</strong></h1>
	<div class="panel panel-success openClickTable" id="clientCreate">
		<div class="panel-heading">
			<span>Заполните данные клиента</span>
			<i class="fa fa-chevron-down"></i> 
		</div>
		<div class="panel-body">
				{!! Form::open(array('action' => 'ClientController@store')) !!}
					<div class="row">
						<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label for="Input1">Полное наименование:</label>
						  	{!! Form::text('full_name',null,array('class' => 'form-control','id' => 'Input1')) !!}
						</div>		
						<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label for="Input2">Наименование:</label>
						  	{!! Form::text('name',null,array('class' => 'form-control','id' => 'Input2')) !!}
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<label for="Input3">ИНН:</label>
						  	{!! Form::text('inn',null,array('class' => 'form-control','id' => 'Input3')) !!}
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<label for="Input4">КПП:</label>
						  	{!! Form::text('kpp',null,array('class' => 'form-control','id' => 'Input4')) !!}
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<label for="Input5">ОГРН:</label>
						  	{!! Form::text('ogrn',null,array('class' => 'form-control','id' => 'Input5')) !!}
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
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped" id="client-table">
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
				  		<th></th>
				  	</tr>
				  </thead>
				  <tbody class='layoutTable'>
				  	  {{-- */ $num = 0; /* --}}
				  	@forelse($clients as $client)
						<tr>
							<td>{{ $num += 1 }}</td>
							<td>{{ $client->full_name }}</td>
							<td>{{ $client->name }}</td>
							<td>{{ $client->inn }}</td>
							<td>{{ $client->kpp }}</td>
							<td>{{ $client->ogrn }}</td>
							<td><a href="/client/{{ $client->id }}/agreement"><i class="fa fa-file-text-o"></i></a></td>
							<td><a href="/client/{{ $client->id }}/edit"><i class="fa fa-pencil"></i></a></td>
							<td>
								{{ Form::model($client, array('route' => array('client.destroy', $client->id), 'method' => 'DELETE')) }}
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