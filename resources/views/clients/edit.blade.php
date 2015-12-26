@extends('layouts.master')

@section('title', 'Заголовок страницы')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/clients.css">
@stop

@section('content')
    <div class="panel panel-success">
		<div class="panel-heading">Редактирование данных клиента</div>
		<div class="panel-body">
		{!! Form::model($client, array('route' => array('client.update', $client->id), 'method' => 'PUT')) !!}

		  {!! Form::text('full_name', @$client->full_name) !!}
		  {!! Form::text('name', @$client->name) !!}
		  {!! Form::text('inn', @$client->inn) !!}
		  {!! Form::text('kpp', @$client->kpp) !!}
		  {!! Form::text('ogrn', @$client->ogrn) !!}
		  {!! Form::submit('Сохранить') !!}

		{!! Form::close() !!}
		</div>

    </div>
	<div class="panel panel-success">
		<div class="panel-heading">Добавить договор</div>
		<div class="panel-body">
			{!! Form::open(array('action' => 'AgreementController@store')) !!}

			  {!! Form::text('code') !!}
			  {{ Form::checkbox('type') }}
			  {{ Form::checkbox('account') }}
			  {!! Form::date('penalty') !!}
  			  {{ Form::checkbox('second_pay') }}
			  {!! Form::text('code_1c') !!}
			  {!! Form::text('description') !!}
			  {{ Form::checkbox('active') }}
			  {!! Form::date('date_end') !!}
			  {!! Form::hidden('client_id',$client->id) !!}
			  {!! Form::submit('Сохранить') !!}

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
				  		<th>Штраф</th>
				  		<th>Начисление второго платежа</th>
				  		<th>Код в 1С</th>
				  		<th>Описание</th>
				  		<th>Активность</th>
				  		<th>Дата создания</th>
				  		<th>Дата окончания</th>
				  		<th></th>
				  		<th></th>
				  	</tr>
				  </thead>
				  <tbody>
				  	@forelse($agreements as $agreements)
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
							<td>{{ $agreements->created_at }}</td>
							<td>{{ $agreements->date_end }}</td>
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