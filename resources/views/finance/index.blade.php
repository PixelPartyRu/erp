@extends('layouts.master')

@section('title', 'Финансирование')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/finances.css">
@stop

@section('javascript')
  	<script type="text/javascript" src="/assets/js/finances.js"></script>
  	<script type="text/javascript" src="/assets/js/numberFormat.js"></script>
@stop

@section('content')
	<h1><strong>Финансирование</strong></h1>
	@include('finance.popapModal',['dateToday' => $dateToday])
	@include('finance.popapDeliveryModal')
	<div class="panel panel-success">
		<div class="panel-heading">
			<span>Фильтр</span>
		</div>
		<div class="panel-body" id="financeFilter">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<div class="row"> 
						<div class="col-xs-4">
							<label for="financeFormTypeFirstPayment">Первый платеж:</label>
								<input type="checkbox" class="filterCheckbox filterCheckboxType filterUpdate" value="Первый платеж" id="financeFormTypeFirstPayment">
						</div>
						<div class="col-xs-4">
							<label for="financeFormTypeSecondPayment">Второй платеж:</label>
							<input type="checkbox" class="filterCheckbox filterCheckboxType filterUpdate" value="Второй платеж" id="financeFormTypeSecondPayment">
						</div>
						<div class="col-xs-4">
							<label for="financeFormTypeToClient">Перечислено клиенту:</label>
							<input type="checkbox" class="filterCheckbox filterCheckboxType filterUpdate" value="Перечислено клиенту" id="financeFormTypeToClient">
						</div>
					</div>
				</div>			
				<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
					{!!Form::select('financeFormStatus', [0=>'Выберите статус', 'К финансированию'=>'К финансированию', 'Подтверждено'=>'Подтверждено'], 0 ,array('class'=>'selectpicker filterUpdate', 'id'=>'financeFormStatus')) !!}
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-6">
						<label for="financeFormSum">Выделенная сумма:</label>
					</div>					
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-6">					
						<input type="text" readonly id="financeFormSum" value="0" class="form-control" >
						<input type="hidden" value='0' id='financeFormSumHidden'>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-info">
		<div class="panel-heading">Финансирование</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<input type="button" id="financeSuccess" value="Подтвердить" class="form-control btn btn-success">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<input type="button" value="Распечатать" class="form-control btn btn-success">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<input type="button" value="Экспорт П/П" class="form-control btn btn-success">
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-striped">
				  <thead>
					  <tr>
					  	<th></th>
					  	<th>Клиент</th>
					  	<th>Сумма</th>
					  	<th>Количество накладных</th>
					  	<th>Тип финансирования</th>
					  	<th>Дата финансирования</th>
					  	<th>Реестр</th>
					  	<th>Дата реестра</th>
					  	<th>Статус</th>
					  	<th></th>
					  </tr>
				  </thead>
				  <tbody id="finance-table">
				  	@include('finance.tableRow',['finances' => $finances])
				  </tbody>
				</table>
			</div>
		</div>
	</div>
@stop
