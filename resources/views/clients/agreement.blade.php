@extends('layouts.master')

@section('title', 'Все договора клиента')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/clients.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/form.css">
@stop

@section('content')
	<h1><strong>Договора {{$client->name}}</strong></h1>
	<div class="panel panel-success openClickTable">
		<div class="panel-heading">
		<span>Добавить договор</span>
			<i class="fa fa-chevron-down"></i>
		</div>
		<div class="panel-body">
			{!! Form::open(array('action' => 'AgreementController@store')) !!}
				{!! Form::hidden('client_id',$client->id) !!}
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-5 col-lg-4">
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							<label for="Input1">Номер договора</label>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							{!! Form::text('code',null,array('class' => 'form-control','id' => 'Input1')) !!}
						</div>
						<div class='clearfix'></div>	
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							<label for="Input5">Код в 1С</label>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							{{ Form::text('code_1c',null,array('class' => 'form-control','id' => 'Input5')) }}
						</div>
						<div class='clearfix'></div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							<label for="Input8">Дата договора</label>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							{{ Form::date('created_at',null,array('class' => 'form-control ','id' => 'Input9')) }}
						</div>
						<div class='clearfix'></div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							<label for="Input8">Окончания договора</label>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							{{ Form::date('date_end',null,array('class' => 'form-control ','id' => 'Input10')) }}
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-5 col-lg-4">
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							<label for="Input3">Выставление счетов</label>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							{{ Form::select('account',['1'=>'В месяц оплаты', '0'=>'Ежемесячно'],0,array('class' => 'form-control selectpicker ','id' => 'Input3')) }}
						</div>
						<div class="clearfix"></div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							<label for="Input8">Остановить расчет пеней с</label>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							{{ Form::date('penalty',null,array('class' => 'form-control ','id' => 'Input8')) }}
						</div>
						<div class="clearfix"></div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">					
							<label for="Input2">Тип</label>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							{{ Form::select('type',['1'=>'C регрессом', '0'=>'Без регресса'],1,array('class' => 'form-control selectpicker ','id' => 'Input2')) }}
						</div>
						<div class="clearfix"></div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">						
							<label for="Input2">Начисление 2-го платежа</label>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							{{ Form::select('second_pay',['1'=>'Полное погашение уступки', '0'=>'Погашение финансирования'],1,array('class' => 'form-control selectpicker ','id' => 'Input2')) }}
						</div>
					</div>
				</div>	
				<div class="col-xs-12 col-sm-12 col-md-2 col-lg-4">
					<div class="row">
						<div class="col-xs-6 col-sm-2 col-md-8 col-lg-3">
							<label for="Input7">Действующий</label>
						</div>
						<div class="col-xs-6 col-sm-10 col-md-4 col-lg-9">
							{{ Form::checkbox('active',null,array('class' => 'form-control','id' => 'Input7')) }}
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							{!! Form::submit('Сохранить',array('class' => 'btn btn-success form-control')) !!}
						</div>
					</div>
				</div>
			</div>
			</div>
			{!! Form::close() !!}
		</div>
	<div class="panel panel-success">
		<div class="panel-heading">Все договора</div>
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped" id="client-table">
				  <thead>
				  	<tr>
				  		<th>Номер</th>
				  		<th>Тип</th>
						<th>Дата создания</th>
						<th>Дата окончания</th>
				  		<th>Код в 1С</th>
						<th>Начисление второго платежа</th>
				  		<th>Выставление счета</th>
				  		<th>Пеня</th>
				  		<th>Актуальность</th>
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
									C регрессом
								@else
									Без регресса
								@endif
							</td>
							<td>{{ @date_format($agreements->created_at,'d/m/Y') }}</td>
							<td>{{ @date('d/m/Y',strtotime($agreements->date_end))}}</td>
							<td>{{ $agreements->code_1c }}</td>
							<td>
								@if ($agreements->second_pay)
									Полное погашение уступки
								@else
									Погашение финансирования
								@endif
							</td>
							<td>
								@if ($agreements->account)
									В месяц оплаты
								@else
									Ежемесячно
								@endif
							</td>
							<td>
								@if ($agreements->penalty != '2099-12-31')
								{{$agreements->penalty}}
								@else
									Нет
								@endif
							</td>
							<td>
								@if ($agreements->active)
									Действующий
								@else
									Не активен
								@endif
							</td>
							<td><a href="/agreement/{{ $agreements->id }}/edit"><i class="fa fa-pencil" data-toggle="tooltip" title="Редактировать"></i></a></td>
							<td>
								{{ Form::model($agreements, array('route' => array('agreement.destroy', $agreements->id), 'method' => 'DELETE')) }}
									{{ Form::button('<i class="fa fa-close"  data-toggle="tooltip" title="Удалить"  id="delete"></i>', array('class'=>'', 'type'=>'submit')) }}
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

