@extends('layouts.master')

@section('title', 'Поступившие платежи')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/repayment.css">
@stop

@section('javascript')
  	<script type="text/javascript" src="/assets/js/repayment.js"></script>
  	<script type="text/javascript" src="/assets/js/numberFormat.js"></script>
@stop

@section('content')
	<h1><strong>Поступившие платежи</strong></h1>
	@include('repayment.importModal')
	@include('repayment.createModal')
	@include('repayment.repaymentModal')
	@include('repayment.deleteModal')
	<div class="panel panel-info">
		<div class="panel-heading">Платежи</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-4">
					<input type="button" value="Импортировать" data-toggle="modal" data-target="#importModal" class="form-control btn btn-success">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-4">
					<input type="button" value="Создать вручную" data-toggle="modal" data-target="#createModal" class="form-control btn btn-success">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-4">
					<input type="button" id="repaymentModalBtn" value="Проведение погашения" class="form-control btn btn-success">
				</div>
			</div>
			@include('repayment.tableRepayment')
		</div>
	</div>	
@stop