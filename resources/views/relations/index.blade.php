@extends('layouts.master')

@section('title', 'Связи')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/relations.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/form.css">
@stop


@section('javascript')
  	<script type="text/javascript" src="/assets/js/relations.js"></script>
@stop


@section('content')
	<h1><strong>Связи клиент-дебитор</strong></h1>
	<div class="panel panel-success openClickTable" id="relationsCD">
		<div class="panel-heading">
			<span>Создание cвязи клиент-дебитор</span>
			<i class="fa fa-chevron-down"></i>
		</div>
			@include('relations.add')
	</div>
	<div class="panel panel-success openClickTable" id="filter_relations">
		<div class="panel-heading">
			<span>Фильтр</span>
			<i class="fa fa-chevron-down"></i>
		</div>
		<div class="panel-body" style="display: none;">
			@include('relations.filter')
		</div>	
	</div>	
	<div class="panel panel-info">
		<div class="panel-heading">Связи</div>
		<div class="panel-body">
		 @include('relations.table')
		
	</div>
@stop

<!--, array('class' => 'btn btn-warning'-->