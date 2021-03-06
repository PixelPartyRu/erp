@extends('layouts.master')

@section('title', 'Редактирование клиента')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/clients.css">
@stop
@section('javascript')
	<script type="text/javascript" src="/assets/js/validator.js"></script>
  	<script type="text/javascript" src="/assets/js/clients.js"></script>
@stop
@section('content')
	<h1><strong>Редактирование клиента</strong></h1>
    <div class="panel panel-success">
		<div class="panel-heading">Редактирование данных клиента</div>
		<div class="panel-body">
			{!! Form::model($client, array('route' => array('client.update', $client->id), 'method' => 'PUT','id'=>'form-client','class'=>'noDoubleClickNoAjaxForm')) !!}
				<div class="row">
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label>Полное наименование:</label>
					  	{!! Form::text('full_name', @$client->full_name,array('class' => 'form-control')) !!}
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label>Наименование:</label>
					  	{!! Form::text('name', @$client->name,array('class' => 'form-control')) !!}
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label>ИНН:</label>
					  	{!! Form::text('inn', @$client->inn,array('class' => 'form-control', 'data-inns'=>'bar','maxlength'=>'12')) !!}
						 <div class="help-block with-errors"></div>
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label>КПП:</label>
					  	{!! Form::text('kpp', @$client->kpp,array('class' => 'form-control','data-minlength'=>'9', 'maxlength'=>'9', 'data-error'=>"КПП введен не верно")) !!}
						<div class="help-block with-errors"></div>
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<label>ОГРН:</label>
						{!! Form::text('ogrn', @$client->ogrn,array('class' => 'form-control', 'data-minlength'=>'13', 'maxlength'=>'13', 'data-error'=>"ОРГН введен не верно")) !!}
						<div class="help-block with-errors"></div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" id='btn-container'>
			  			  {!! Form::submit('Сохранить',array('class' => 'btn btn-success')) !!}
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" id='btn-container'>
						  <a href='{{URL::route('client.index')}}' class="btn btn-danger">Отменить</a>
					</div>
				</div>
			{!! Form::close() !!}
		</div>

    </div>
@stop