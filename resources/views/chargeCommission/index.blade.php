@extends('layouts.master')

@section('title', 'Начисленные комиссии')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/chargeCommission.css">
@stop	

@section('javascript')
  	<script type="text/javascript" src="/assets/js/chargeCommission.js"></script>

@stop

@section('content')
	<h1><strong>Начисленные комиссии</strong></h1>
	@include('chargeCommission.filter')
<div class="container">
	<div class="row">
		{!! Form::open(array('action' => 'ChargeCommissionController@recalculationTest')) !!}
			<input type="date" name="dateTest">
			{!! Form::submit('Перерасчет',array('class' => 'btn btn-success')) !!}
		{!! Form::close() !!}
	</div>
</div>

<div class="panel panel-success" >
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
		  	@include('chargeCommission.tableRow')
		  </tbody>
		</table>
	</div>
</div>
@stop