@extends('layouts.master')

@section('title', 'Заголовок страницы')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/debtor.css">
@stop

@section('javascript')
  	<script type="text/javascript" src="/assets/js/debtor.js"></script>
	<script type="text/javascript" src="/assets/js/validator.js"></script>
@stop

@section('content')
	<h1><strong>Дебиторы</strong></h1>
	<div class="panel panel-success openClickTable" id="debtorCreate">
		<div class="panel-heading">
			<span>Создание нового дебитора</span>
			<i class="fa fa-chevron-down"></i> 
		</div>		
		<div class="panel-body">
				{!! Form::open(array('action' => 'DebtorController@store','id'=>'debtor-form')) !!}
					<div class="row">
						<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label for="InputEmail1">Полное наименование:</label>
						  	{!! Form::text('full_name',null,array('class' => 'form-control','id' => 'InputEmail1','required' => 'required')) !!}
						</div>		
						<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label for="InputEmail2">Наименование:</label>
						  	{!! Form::text('name',null,array('class' => 'form-control','id' => 'InputEmail2','required' => 'required')) !!}
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<label for="InputEmail3">ИНН:</label>
						  	{!! Form::text('inn',null,array('class' => 'form-control','id' => 'InputEmail3','data-inns'=>'bar','maxlength'=>'12','required' => 'required')) !!}
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<label for="InputEmail4">КПП:</label>
						  	{!! Form::text('kpp',null,array('class' => 'form-control','id' => 'InputEmail4','data-minlength'=>'9', 'maxlength'=>'9', 'data-error'=>"КПП введен не верно",'required' => 'required')) !!}
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-3">
							<label for="InputEmail5">ОГРН:</label>
						  	{!! Form::text('ogrn',null,array('class' => 'form-control','id' => 'InputEmail5', 'data-minlength'=>'13', 'maxlength'=>'13', 'data-error'=>"ОРГН введен не верно",'required' => 'required')) !!}
							<div class="help-block with-errors"></div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" id='btn-container'>
						  	{!! Form::submit('Добавить',array('class' => 'btn btn-success')) !!}
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							{!! Session::get('message') !!}
						</div>
					</div>
				{!! Form::close() !!}
  		</div>
	</div>
	<div class="panel panel-info">
		<div class="panel-heading">Наши дебиторы</div>
			<div class="table-responsive">
				<table class="table table-striped" id="debtor-table">
				  <thead>
				  	<tr>
				  		<th>№</th>
				  		<th>Полное наименование</th>
				  		<th>Наименование</th>
				  		<th>ИНН</th>
				  		<th>КПП</th>
				  		<th>ОГРН</th>
				  		<th></th>
				  		<th></th>
				  	</tr>
				  </thead>
				  <tbody>
				  {{-- */ $num = 0; /* --}}
				  	@forelse($debtors as $debtor)
						<tr>
							<td>{{ $num += 1 }}</td>
							<td>{{ $debtor->full_name }}</td>
							<td>{{ $debtor->name }}</td>
							<td>{{ $debtor->inn }}</td>
							<td>{{ $debtor->kpp }}</td>
							<td>{{ $debtor->ogrn }}</td>
							<td><a href="/debtor/{{ $debtor->id }}/edit"><i class="fa fa-pencil" data-toggle="tooltip" title="Редактировать"></i></a></td>
							<td>
							<a class="deleteItem" data-toggle="tooltip" title="Удалить" data-delete="/debtor/{{$debtor->id}}" data-method="delete"><i class="fa fa-close"></i></a>
							</td>
						</tr>
					@empty
						<tr>
							<td>Дебиторов нет</td>
						</tr>
					@endforelse
				  </tbody>
				</table>
			</div>
		</div>
	</div>

@stop

<!--, array('class' => 'btn btn-warning'-->