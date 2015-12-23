@extends('layouts.master')

@section('title', 'Заголовок страницы')

@section('content')
    <p>
		{!! Form::model($client, array('route' => array('ClientController@update', $client->id), 'method' => 'PUT')) !!}

		  {!! Form::text('full_name', @$client->full_name) !!}
		  {!! Form::text('name', @$client->name) !!}
		  {!! Form::text('inn', @$client->inn) !!}
		  {!! Form::text('kpp', @$client->kpp) !!}
		  {!! Form::text('ogrn', @$client->ogrn) !!}
		  {!! Form::submit('Сохранить') !!}

		{!! Form::close() !!}
    </p>
@stop