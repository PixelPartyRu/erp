
@extends('layouts.master')
@section('title', 'Выставление счетов')
@section('filter')
<div class="row" id="filter-container">
	<script>var Dates = '{!! json_encode($dt) !!}' </script>
	<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<div class="row">
			<div class="form-group col-xs-4 col-sm-4 col-md-4 col-lg-4">
				{!! Form::label('client_id', 'Клиент (ИНН):') !!}
			</div>
			<div class="form-group col-xs-8 col-sm-8 col-md-8 col-lg-8">
			<select name="client_id" id="client_id" class="form-control filter_select">
				<option value="all" selected>Все клиенты</option> 
				@foreach ($clients as $client)
			 	<option value="{{$client->client->id}}">{{$client->client->name}}</option>
				@endforeach
			</select>
			</div>
		</div>
	</div>
	<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-2">
		<div class="row">
			<div class="form-group col-xs-3 col-sm-3 col-md-3 col-lg-4">
				{!! Form::label('status', 'Cтатус:') !!}
			</div>
			<div class="form-group col-xs-9 col-sm-9 col-md-9 col-lg-8">
				{!! Form::select('status', ['all'=>'Все','first'=>'Оплачен','second'=>'К оплате'],'0', array('class' => 'form-control filter_select','id' => 'status'))!!}
			</div>
		</div>
	</div>
	<div class="form-group col-xs-12 col-sm-8 col-md-7 col-lg-5">
		<div class="row">
			<div class="form-group col-xs-4 col-sm-4 col-md-3 col-lg-4">
				{!! Form::label('client', 'Месяц выставления:') !!}
			</div>
			<div class="form-group col-xs-4 col-sm-4 col-md-4 col-lg-4">
				{!! Form::select('year', [null => 'Выберите год'],null, array('class' => 'form-control filter_select','id' => 'year'))!!}
			</div>
			<div class="form-group col-xs-4 col-sm-4 col-md-4 col-lg-4">
				{!! Form::select('month', ['' => 'Выберите месяц','01' => 'Январь','02' => 'Февраль','03' => 'Март','04' => 'Апрель','05' => 'Май','06' => 'Июнь','07' => 'Июль','08' => 'Август','09' => 'Сентябрь','10' => 'Октябрь','11' => 'Ноябрь','12' => 'Декабрь'],'', array('class' => 'form-control filter_select','id' => 'month','disabled'=>'disabled'))!!}
			</div>
		</div>
	</div>
	<div class="form-group col-xs-12 col-sm-4 col-md-5 col-lg-2">
		<div class="row">
			<div class="form-group col-xs-3 col-sm-3 col-md-3 col-lg-3">
				<button class="btn btn-success form-control" style="padding:0;" id="update" type="submit" value="Обновить" disabled='disabled'>
					<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
				</button>
			</div>
			<div class="form-group col-xs-9 col-sm-9 col-md-9 col-lg-9">
				{!! Form::submit('Закрыть месяц',array('class' => 'btn btn-success form-control','id'=>'create','disabled'=>'disabled')) !!}	
			</div>
		</div>
	</div>
</div>
@stop
@section('content')
	<div class="panel panel-info">
		<div class="panel-heading">Выставление счетов</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped" id="client-table">
						<thead>
						  	<tr class="sv">
						  		<th>Клиент</th>
								<th>Номер Ген. договора</th>
						  		<th>Дата счета</th>
						  		<th>Номер счета</th>
						  		<th>Сумма комиссий</th>
								<th>Сумма НДС</th>
								<th>Комиссий с НДС</th>
								<th>Долг на конец месяца</th>
						  		<th>Текущий долг</th>
						  	</tr>
						</thead>
						<tbody id="AjaxUpdate">
							<tr>
								<td class="no-space">
									<p>Выберите месяц выставления</p>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@stop
@section('javascript')
  	<script type="text/javascript" src="/assets/js/invoicing.js"></script>
@stop