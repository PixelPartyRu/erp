@extends('layouts.master')

@section('title', 'Заголовок страницы')

@section('sidebar')
    @

    <p>Этот элемент будет добавлен к главному сайдбару.</p>
@stop

@section('content')
    <p>
		{!! Form::open(array('action' => 'ClientController@store')) !!}

		  {!! Form::text('full_name', @$full_name) !!}
		  {!! Form::text('name', @$name) !!}
		  {!! Form::text('inn', @$inn) !!}
		  {!! Form::text('kpp', @$kpp) !!}
		  {!! Form::text('ogrn', @$ogrn) !!}

		  {!! Form::submit('Send') !!}

		{!! Form::close() !!}
    	@forelse($clients as $client)
      <li><a href="/client/{{ $client->id }}/edit">{{ $client->full_name }}</a></li>
		@empty
		      <p>No users</p>
		@endforelse
    </p>
@stop