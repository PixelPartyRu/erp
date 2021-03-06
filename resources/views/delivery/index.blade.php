@extends('layouts.master')

@section('title', 'Поставки')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/deliveries.css">
@stop

@section('javascript')
  	<script type="text/javascript" src="/assets/js/deliveries.js"></script>
@stop

@section('content')
	<h1><strong>Поставки</strong></h1>
	@include('delivery.verificationModal',['dateToday' => $dateToday])
	@include('delivery.deliveryFilter')
	@include('delivery.importModal')
	@include('delivery.importModalDelete')
	@include('delivery.importDeleteConfirm')
	<div class="panel panel-success">
		<div class="panel-heading">Поставки</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<input type="button" id="popupOpen" value="Верификация" class="form-control btn btn-success">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<input type="button" value="Отклонить" class="form-control btn btn-success">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<input type="button" value="Удалить" data-toggle="modal" id="modalConfirm" class="form-control btn btn-success">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<input type="button" value="Импорт поставок" data-toggle="modal" data-target="#importModal" class="form-control btn btn-success">
				</div>
			</div>		
			<div class="table-responsive">
				<table class="table table-striped" id="delivery-table">
				  <thead>
					  <tr>
					  	<th><input type="checkbox" id="checkAll_checkbox"></th>
						<th>№</th>
					  	<th>Клиент</th>
					  	<th>ИНН клиента</th>
					  	<th>Дебитор</th>
					  	<th>ИНН дебитора</th>
					  	<th>Накладная</th>
					  	<th>Сумма накладной</th>
						<th>Остаток долга</th>
					  	<th>Сумма первого платежа</th>
					  	<th>Остаток первого платежа</th>
					  	<th>Дата накладной</th>
					  	<th>Отсрочка</th>
					  	<th>Срок оплаты</th>
					  	<th>Дата погашения поставки</th>
					  	<th>Дата регресса</th>
					  	<th>Дата окончания регресса</th>
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
				  </tbody>
				</table>
			</div>
		</div>
	</div>
@stop