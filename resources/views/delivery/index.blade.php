@extends('layouts.master')

@section('title', 'Поставки')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/deliveries.css">
@stop

@section('javascript')
  	<script type="text/javascript" src="/assets/js/deliveries.js"></script>
@stop

@section('content')
	@include('delivery.popup',['dateToday' => $dateToday])
	@include('delivery.deliveryFilter')
	<div class="panel panel-success">
		<div class="panel-heading">Поставки</div>
		<div class="panel-body">
			<div class="form-group">
			{!! Form::open(array('action' => 'DeliveryController@store', 'files' => true)) !!}
			    {!! Form::label('getFile', 'Загрузить файл с исходными данными:')!!}
			    {!! Form::file('report')!!}
				<label for="Input1">Наличие оригинала документа:</label>
			  	{!! Form::checkbox('the_presence_of_the_original_document',null,array('class' => 'form-control','id' => 'Input1')) !!}
			    {!! Form::submit('Импортирт поставок') !!}
			{!! Form::close() !!}
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<input type="button" id="popupOpen" value="Верификация" class="form-control btn btn-success">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<input type="button" value="Отклонить" class="form-control btn btn-success">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<input type="button" value="Удалить" class="form-control btn btn-success">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<input type="button" value="Импортировать" class="form-control btn btn-success">
				</div>
			</div>		
			<div class="table-responsive">
				<table class="table table-striped">
				  <thead>
					  <tr>
					  	<th>Верификация</th>
					  	<th>Клиент</th>
					  	<th>Инн клиента</th>
					  	<th>Дебитор</th>
					  	<th>Инн дебитора</th>
					  	<th>Накладная</th>
					  	<th>Сумма накладной</th>
					  	<th>Сумма первого платежа</th>
					  	<th>Остаток долга</th>
					  	<th>Остаток долга первого платежа</th>
					  	<th>Дата накладной</th>
					  	<th>Отсрочка</th>
					  	<th>Срок оплаты</th>
					  	<th>Дата оплаты</th>
					  	<th>Дата регресса</th>
					  	<th>Дата окончания периода регресса</th>
					  	<th>Дата рег. поставок</th>
					  	<th>Просрочка</th>
					  	<th>Счет фактура</th>
					  	<th>Дата сч. ф.</th>
					  	<th>Реестр</th>
					  	<th>Дата реестра</th>
					  	<th>Дата финансирования</th>
					  	<th>Дата погашения финансирования</th>
					  	<th>Заметки</th>
					  	<th>Погасил</th>
					  	<th>Состояние</th>
					  	<th>Статус</th>
					  	<th>Наличие оригинала документа</th>
					  	<th>Тип факторинга</th>
					  </tr>
				  </thead>
				  <tbody id="deliveryTableTemplate">
				  	 @include('delivery.deliveryTable')
				  </tbody>
				</table>
			</div>
		</div>
	</div>
@stop