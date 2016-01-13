@extends('layouts.master')

@section('title', 'Все договора клиента')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/clients.css">
@stop

@section('content')
	<h1><strong>Договора</strong></h1>
	<div class="panel panel-success openClickTable">
		<div class="panel-heading">
		<span>Добавить договор</span>
			<i class="fa fa-chevron-down"></i>
		</div>
		<div class="panel-body">
			{!! Form::open(array('action' => 'AgreementController@store')) !!}
			<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
					<label for="Input1">Код:</label>
				  	{!! Form::text('code',null,array('class' => 'form-control','id' => 'Input1')) !!}
				</div>
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
					<label for="Input2">Тип:</label>
					{{ Form::checkbox('type',null,array('class' => 'form-control','id' => 'Input2')) }}
				</div>
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
					<label for="Input3">Счет:</label>
					{{ Form::checkbox('account',null,array('class' => 'form-control','id' => 'Input3')) }}
				</div>
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
					<label for="Input8">Пеня:</label>
					{{ Form::date('penalty',null,array('class' => 'form-control','id' => 'Input8')) }}
				</div>
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
					<label for="Input4">Начисление второго платежа:</label>
					{{ Form::checkbox('second_pay',null,array('class' => 'form-control','id' => 'Input4')) }}
				</div>
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
					<label for="Input5">Код в 1С:</label>
					{{ Form::text('code_1c',null,array('class' => 'form-control','id' => 'Input5')) }}
				</div>
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
					<label for="Input6">Описание:</label>
					{{ Form::text('description',null,array('class' => 'form-control','id' => 'Input6')) }}
				</div>
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
					<label for="Input7">Активный:</label>
					{{ Form::checkbox('active',null,array('class' => 'form-control','id' => 'Input7')) }}
				</div>
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
					{!! Form::submit('Сохранить',array('class' => 'btn btn-success form-control')) !!}
				</div>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
	<div class="panel panel-success">
		<div class="panel-heading">Все договора</div>
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped" id="client-table">
				  <thead>
				  	<tr>
				  		<th>Код</th>
				  		<th>Тип</th>
				  		<th>Счет</th>
				  		<th>Пеня</th>
				  		<th>Начисление второго платежа</th>
				  		<th>Код в 1С</th>
				  		<th>Описание</th>
				  		<th>Активный</th>
				  		<th>Дата создания</th>
				  		<th>Дата окончания</th>
				  		<th></th>
				  		<th></th>
				  	</tr>
				  </thead>
				  <tbody class='layoutTable'>
				  	@forelse($client->agreements as $agreements)
						<tr>
							<td>{{ $agreements->code }}</td>
							<td>
								@if ($agreements->type)
									true
								@else
									false
								@endif
							</td>
							<td>
								@if ($agreements->account)
									true
								@else
									false
								@endif
							</td>
							<td>{{ $agreements->penalty }}</td>
							<td>
								@if ($agreements->second_pay)
									true
								@else
									false
								@endif
							<td>{{ $agreements->code_1c }}</td>
							<td>{{ $agreements->description }}</td>
							<td>
								@if ($agreements->active)
									true
								@else
									false
								@endif
							</td>
							<td>{{ @date_format($agreements->created_at,'d/m/Y') }}</td>
							<td>{{ @date('d/m/Y',strtotime($agreements->date_end))}}</td>
							<td><a href="/agreement/{{ $agreements->id }}/edit"><i class="fa fa-pencil"></i></a></td>
							<td>
								{{ Form::model($agreements, array('route' => array('agreement.destroy', $agreements->id), 'method' => 'DELETE')) }}
									{{ Form::button('<i class="fa fa-close"></i>', array('class'=>'', 'type'=>'submit')) }}
								{{ Form::close() }}
							</td>
						</tr>
					@empty
						<p>Договора отсутствуют</p>
					@endforelse
				  </tbody>
				</table>
			</div>
		</div>
	</div>
@stop

