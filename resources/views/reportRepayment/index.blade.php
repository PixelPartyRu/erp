@extends('layouts.master')

@section('title', 'Отчет о погашениях')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/reportRepayment.css">
@stop

@section('javascript')
  	<script type="text/javascript" src="/assets/js/reportRepayment.js"></script>
  	<script type="text/javascript" src="/assets/js/numberFormat.js"></script>
@stop
@section('filter')
{!! Form::open(array('id' => 'filter-form')) !!}
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<select name="filter-client" id="filter-client" class="form-control filter_select">
				<option value="0" selected>Все клиенты</option> 
				@foreach ($clients as $client)
			 		<option value="{{$client->id}}">{{$client->name}}</option>
				@endforeach
			</select>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<select name="filter-debtor" id="filter-debtor" class="form-control filter_select">
				<option value="0" selected>Все дебиторы</option> 
				@foreach ($debtors as $debtor)
			 		<option value="{{$debtor->id}}">{{$debtor->name}}</option>
				@endforeach
			</select>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<select name="filter-registry" id="filter-registry" class="form-control filter_select">
				<option value="0" selected>Все реeстры</option>
				@foreach ($registries as $registry)
			 		<option value="{{$registry}}">{{$registry}}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="row margin-top-bottom">
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<select name="filter-choice" id="filter-choice" class="form-control filter_select">
				<option value="0" selected>Выберите период</option>
				<option value="1">Дата реестра</option>
				<option value="2">Дата погашения</option>    
			</select>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-6">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-1">
					<span class="padding-top"> От: </span>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-5">
					<input name='filter-before' disabled type="date" class="form-control filter-date-select" value="{{ date('Y-m-d') }}">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-1">
					<span class="padding-top"> До: </span>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-5">
					<input name='filter-after' disabled type="date" class="form-control filter-date-select" value="{{ date('Y-m-d') }}">
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-1">
			<input type="button" class="btn btn-success" id="filter-send" value="Обновить">
		</div>
	</div>
{!! Form::close() !!}	
@stop
@section('content')
	<div class="panel panel-info">
		<div class="panel-heading">Отчет по погашениям</div>
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped" id="report-repayment-table">
					<tbody id="AjaxUpdate">
						<tr>
							<td class="no-space">
								Выберите критерий поиска
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
@stop