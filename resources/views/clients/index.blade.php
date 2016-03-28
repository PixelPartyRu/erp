@extends('layouts.master')

@section('title', 'Клиенты')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/clients.css">
@stop	

@section('javascript')
  	<script type="text/javascript" src="/assets/js/clients.js"></script>
	<script type="text/javascript" src="/assets/js/validator.js"></script>
@stop

@section('content')
	<h1><strong>Клиенты</strong></h1>
	<div class="panel panel-success" id="filter_client">
		<div class="panel-heading">
			<span>Фильтр</span>
		</div>
		<div class="panel-body">
			@include('clients.filter')
		</div>	
	</div>
	<div class="panel panel-success openClickTable" id="clientCreate">
		<div class="panel-heading">
			<span>Создание нового клиента</span>
			<i class="fa fa-chevron-down"></i> 
		</div>
		<div class="panel-body">
				{!! Form::open(array('action' => 'ClientController@store','id'=>'form-client','class'=>'noDoubleClickNoAjaxForm')) !!}
					<div class="row">
						<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label for="Input1">Полное наименование:</label>
						  	{!! Form::text('full_name',null,array('class' => 'form-control','id' => 'Input1','required' => 'required')) !!}
						</div>		
						<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label for="Input2">Наименование:</label>
						  	{!! Form::text('name',null,array('class' => 'form-control','id' => 'Input2','required' => 'required')) !!}
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<label for="Input3">ИНН:</label>
						  	{!! Form::text('inn',null,array('class' => 'form-control','id' => 'Input3', 'data-inns'=>'bar', 'maxlength'=>'12','required' => 'required')) !!}
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<label for="Input4">КПП:</label>
						  	{!! Form::text('kpp',null,array('class' => 'form-control','id' => 'Input4','data-minlength'=>'9', 'maxlength'=>'9', 'data-error'=>"КПП введен не верно",'required' => 'required')) !!}
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<label for="Input5">ОГРН:</label>
						  	{!! Form::text('ogrn',null,array('class' => 'form-control','id' => 'Input5','data-minlength'=>'13', 'maxlength'=>'13', 'data-error'=>"ОРГН введен не верно",'required' => 'required')) !!}
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" id='btn-container-label'>
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
		<div class="panel-heading">
			<span>Наши клиенты</span>
			<i class="fa fa-file-excel-o pull-right export-excel" data-name='Clients'></i>	
		</div>
		<div class="panel-body" id='client-table'>
			
		</div>
	</div>
@stop

<!--, array('class' => 'btn btn-warning'-->