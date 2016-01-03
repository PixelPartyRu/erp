@extends('layouts.master')

@section('title', 'Редактирование дебитора')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/debtor.css">
@stop

@section('content')
	<h1><strong>Редактирование дебитора</strong></h1>
    <div class="panel panel-success">
		<div class="panel-heading">Редактирование данных дебитора</div>
		<div class="panel-body">
		{!! Form::model($debtor, array('route' => array('debtor.update', $debtor->id), 'method' => 'PUT')) !!}
			<div class="row">
				<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
					<label>Полное наименование:</label>
		 			{!! Form::text('full_name', @$debtor->full_name,array('class' => 'form-control')) !!}
		 		</div>
		 		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
					<label>Наименование:</label>
		 			{!! Form::text('name', @$debtor->name, array('class' => 'form-control')) !!}
		 		</div>
		 		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
					<label>ИНН:</label>
		 			{!! Form::text('inn', @$debtor->inn, array('class' => 'form-control')) !!}
		 		</div>
		 		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
					<label>КПП:</label>
		 			 {!! Form::text('kpp', @$debtor->kpp, array('class' => 'form-control')) !!}
		 		</div>
		 		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
					<label>ОГРН:</label>
		 			 {!! Form::text('ogrn', @$debtor->ogrn, array('class' => 'form-control')) !!}
		 		</div>
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4" id='btn-container'>
			  		{!! Form::submit('Сохранить',array('class' => 'btn btn-success')) !!}
				</div>
		{!! Form::close() !!}
    </div>
@stop