@extends('layouts.master')

@section('title', 'Заголовок страницы')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/clients.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/form.css">
@stop

@section('content')
	<div class="panel panel-success">
		<div class="panel-heading">Редактирование договора</div>
		<div class="panel-body">
		{!! Form::model($agreement, array('route' => array('agreement.update', $agreement->id), 'method' => 'PUT')) !!}
			<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
					<div class="absolute">
						<div class="input_text_a">
						<label for="Input1">Номер договора</label>
						{!! Form::text('code',$agreement->code,array('class' => 'form-control left1','id' => 'Input1')) !!}
						</div>
						<div class="input_text_a">
						<label for="Input5">Код в 1С</label>
						{{ Form::text('code_1c',$agreement->code_1c,array('class' => 'form-control left1','id' => 'Input5')) }}
						</div>
						<div class="input_text_a">
						<label for="Input8">Дата договора</label>
						{{ Form::date('created_at',$agreement->created_at,array('class' => 'form-control left1','id' => 'Input9')) }}
						</div>
						<div class="input_text_a">
						<label for="Input8">Окончания договора</label>
						@if($agreement->penalty)
							{{ Form::date('date_end',$agreement->date_end,array('class' => 'form-control left1','id' => 'Input10')) }}
						@else
							{{ Form::date('date_end',NULL,array('class' => 'form-control left1','id' => 'Input10')) }}
						@endif	
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-4">
					<div class="absolute">
						<div class="input_text_a">
							<label for="Input3">Выставление счетов</label>
							
							{{ Form::select('account',array('0'=>'Ежемесячно','1'=>'В месяц оплаты'),$agreement->account,array('class' => 'form-control selectpicker left2','id' => 'Input3')) }}
						</div>
						<div class="input_text_a">
							<label for="Input8">Остановить расчет пеней с</label>
							@if($agreement->penalty)
								{{ Form::date('penalty',$agreement->penalty,array('class' => 'form-control left2','id' => 'Input8')) }}
							@else
								{{ Form::date('penalty',NULL,array('class' => 'form-control left2','id' => 'Input8')) }}
							@endif	
						</div>
						<div class="input_text_a">						
						<label for="Input2">Тип</label>
							{{ Form::select('type',['0'=>'Без регресса','1'=>'C регрессом'],$agreement->type,array('class' => 'form-control selectpicker left2','id' => 'Input2')) }}
						</div>
						<div class="input_text_a">						
						<label for="Input2">Начисление 2-го платежа</label>
							{{ Form::select('second_pay',['0'=>'Погашение финансирования','1'=>'Полное погашение уступки'], $agreement->second_pay ,array('class' => 'form-control selectpicker left2','id' => 'Input2')) }}
						</div>
					</div>
				</div>	
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-5">
					<label for="Input7">Действующий</label>
					{{ Form::checkbox('active',$agreement->active,array('class' => 'form-control','id' => 'Input7')) }}
					<a href="{{URL::route('client.index')}}" class="btn btn-danger form-control">Отменить</a>
					{!! Form::submit('Сохранить',array('class' => 'btn btn-success form-control')) !!}
				</div>
			</div>
			</div>
		{!! Form::close() !!}
		</div>
	</div>
@stop