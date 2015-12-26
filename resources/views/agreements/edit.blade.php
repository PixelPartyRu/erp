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
		  	  @if( $agreement->account ) 
			  	{{ Form::checkbox('account', null, true) }}
			  @else
				{{ Form::checkbox('account', null) }}
			  @endif
			  @if($agreement->type) 
				  {{ Form::checkbox('type', null, true) }}
			  @else
			  	  {{ Form::checkbox('type', null) }}
			  @endif
			  {!! Form::date('penalty',@$agreement->penalty) !!}
			  @if($agreement->second_pay) 
	  			  {{ Form::checkbox('second_pay', null ,true) }}
			  @else
			  	  {{ Form::checkbox('second_pay', null) }}
			  @endif
			  {!! Form::text('code_1c',@$agreement->code_1c) !!}
			  {!! Form::text('description',@$agreement->description) !!}
			  @if($agreement->active) 
				  {{ Form::checkbox('active', null, true) }}
			  @else
			  	  {{ Form::checkbox('active', null) }}
			  @endif
			  {!! Form::hidden('client_id',@$agreement->client_td) !!}
			  {!! Form::date('date_end',@$agreement->date_end) !!}

			  {!! Form::submit('Сохранить') !!}

		{!! Form::close() !!}
		</div>
	</div>
@stop