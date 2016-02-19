@extends('layouts.master')

@section('title', 'Финансирование')

@section('stylesheet')

@stop

@section('javascript')

@stop

@section('content')
	<div class="container text-center">
		<h1 class='text-center'>Автоматический перерасчет комиссий не был выполнен</h1>
		{!! Form::open(array('action' => 'NightChargeController@recalculate')) !!}
			{!! Form::submit('Выполнить перерасчет вручую',array('class' => 'btn btn-success')) !!}
		{!! Form::close() !!}
	</div>
@stop
