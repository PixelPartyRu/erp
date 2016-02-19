@extends('layouts.master')

@section('title', 'Начисленные комиссии')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/chargeCommission.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/form.css">
@stop	

@section('javascript')
  	<script type="text/javascript" src="/assets/js/chargeCommission.js"></script>
@stop

@section('content')
	<h1><strong>Начисленные комиссии</strong></h1>
	@include('chargeCommission.filter')
	<div class="panel panel-success openClickTable" >
		<div class="panel-heading">
			<span>Тестирование</span>
			<i class="fa fa-chevron-down"></i> 
		</div>
		<div class="panel-body">
			<div class="row">
				{!! Form::open(array('action' => 'ChargeCommissionController@recalculationTest')) !!}
					<div class="col-xs-2">
						<input type="date" name="dateTest" class="form-control">
					</div>
					<div class="col-xs-2">
						{!! Form::submit('Перерасчет',array('class' => 'btn btn-success')) !!}
					</div>
					<div class="col-xs-2">
						<input type="date" readonly name="output" class="form-control" value='{{$nowDate}}'>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>


	<div class="panel panel-info" >
	<div class="panel-heading">
		<span>Начисление комиссии</span>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-striped" id="client-table">
			  <thead>
			  	<tr>
			  		<th>Клиент</th>
			  		<th>Дебитор</th>
			  		<th>Номер реестра</th>
			  		<th>Номер накладной</th>
			  		<th>Дата накладной</th>
			  		<th>Фиксированный сбор</th>
			  		<th>Проценты за финансирование</th>
			  		<th>Вознаграждение за УДЗ</th>
			  		<th>Пеня за просрочку</th>
			  		<th>Всего начисленно комиссий без НДС</th>
			  		<th>НДС</th>
			  		<th>Всего начисленно комиссий c НДС</th>
			  		<th>Долг по комиссиям</th>
			  		<th>Дата погашения</th>
			  		<th>Дата финансирования</th>
			  		<th>Статус (накладной)</th>
			  	</tr>
			  </thead>
			  <tbody class='layoutTable'>
			  </tbody>
			</table>
		</div>
	</div>
@stop