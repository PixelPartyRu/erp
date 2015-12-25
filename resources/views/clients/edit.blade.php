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
			  {{ Form::radio('account', 'true') }}
			  {{ Form::radio('account', 'false') }}
			  {{ Form::radio('type', 'true') }}
			  {{ Form::radio('type', 'false') }}
			  {!! Form::text('penalty') !!}
  			  {{ Form::radio('second_pay', 'true') }}
			  {{ Form::radio('second_pay', 'false') }}
			  {!! Form::text('code_1c') !!}
			  {!! Form::text('description') !!}
			  {{ Form::radio('active', 'true') }}
			  {{ Form::radio('active', 'false') }}
			  {!! Form::date('penalty') !!}
			  {!! Form::date('date_end') !!}
			  {!! Form::hidden('client_id',$client->id) !!}
			  {!! Form::submit('Сохранить') !!}

			{!! Form::close() !!}
		</div>
	</div>
	<div class="panel panel-success">
		<div class="panel-heading">Все договора</div>
			@forelse($agreements as $agreement)
				<p><a href="/agreement/{{ $agreement->id }}/edit">{{$agreement->code }}</a></p>
			@empty
				<p>Договора отсутствуют</p>
			@endforelse 
		</div>
	</div>
@stop