@extends('layouts.master')

@section('title', 'Заголовок страницы')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/clients.css">
@stop

@section('content')
	<div class="panel panel-success">
		<div class="panel-heading">Редактирование договора</div>
		<div class="panel-body">
		{!! Form::model($agreement, array('route' => array('agreement.update', $agreement->id), 'method' => 'PUT')) !!}

		  	  {!! Form::text('code', @$agreement->code) !!}
			  {{ Form::radio('account', 'true') }}
			  {{ Form::radio('account', 'false') }}
			  {{ Form::radio('type', 'true') }}
			  {{ Form::radio('type', 'false') }}
			  {!! Form::text('penalty',@$agreement->penalty) !!}
  			  {{ Form::radio('second_pay', 'true') }}
			  {{ Form::radio('second_pay', 'false') }}
			  {!! Form::text('code_1c',@$agreement->code_1c) !!}
			  {!! Form::text('description',@$agreement->description) !!}
			  {{ Form::radio('active', 'true') }}
			  {{ Form::radio('active', 'false') }}
			  {!! Form::hidden('client_id',@$agreement->client_td) !!}
			  {!! Form::date('created_at',@$agreement->created_at) !!}
			  {!! Form::date('date_end',@$agreement->date_end) !!}

			  {!! Form::submit('Сохранить') !!}

		{!! Form::close() !!}
		</div>
	</div>
@stop