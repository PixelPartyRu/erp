@extends('layouts.master')

@section('title', 'Заголовок страницы')

@section('content')
	<div class="panel panel-success">
		<div class="panel-heading">Файл получен</div>
		<div class="panel-body">
			{!! Form::open(array('action' => 'DeliveryController@store')) !!}
				<div class="row">
					<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-3">
						<label>Клиент</label>
					  	{!! Form::text('client',$resultArray[3][2],array('class' => 'form-control')) !!}
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-3">
						<label>ИНН Клиента</label>
					  	{!! Form::text('inn_client', $resultArray[3][7],array('class' => 'form-control')) !!}
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-3">
						<label>Дебитор</label>
					  	{!! Form::text('debtor', $resultArray[4][1],array('class' => 'form-control')) !!}
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-3">
						<label>ИНН Дебитора</label>
					  	{!! Form::text('inn_debtor', $resultArray[4][6],array('class' => 'form-control')) !!}
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-3">
						<label>Номер договора связи</label>
					  	{!! Form::text('relation_contract', $resultArray[5][2],array('class' => 'form-control')) !!}
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-3">
						<label>Дата договора связи</label>
					  	{!! Form::date('relation_contract_date', $resultArray[6][2],array('class' => 'form-control')) !!}
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-3">
						<label>Реестр уступаемых требований</label>
					  	{!! Form::text('registry', $resultArray[1][6] ,array('class' => 'form-control')) !!}
					</div>
					<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-3">
						<label>Дата реестра</label>
					  	{!! Form::date('date_of_registry', $resultArray[1][4],array('class' => 'form-control')) !!}
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-striped" id="client-table">
					  <thead>
						  <tr>
						  	<th>Накладная</th>
						 	<th>Сумма накладной</th>
						  	<th>Сумма первого платежа</th>
						  	<th>Остаток долга</th>
						  	<th>Остаток долга первого платежа</th>
						  	<th>Дата накладной</th>
						  	<th>Отсрочка</th>
						  	<th>Срок оплаты</th>
						  	<th>Дата оплаты</th>
						  	<th>Дата регресса</th>
						  	<th>Дата окончания периода регресса</th>
						  	<th>Дата рег. поставок</th>
						  	<th>Фактическая просрочка</th>
						  	<th>Счет фактура</th>
						  	<th>Дата сч. ф.</th>
						  	<th>Реестр</th>
						  	<th>Дата реестра</th>
						  	<th>Дата финансирования</th>
						  	<th>Дата погашения финансирования</th>
						  	<th>Заметки</th>
						  	<th>Погасил</th>
						  	<th>Состояние</th>
						  	<th>Статус</th>
						  	<th>Наличие оригинала документа</th>
						  	<th>Тип факторинга</th>	
						  </tr>
					  </thead>
					  <tbody>
					  		<!-- @forelse($waybillArray as $row)
						    	<tr>
						    		<td>{!! Form::text('waybill',$row[0],array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::date('date_of_waybill',$row[1],array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::text('invoice',$row[2],array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::date('date_of_invoice',$row[3],array('class' => 'form-control')) !!}</td>
							    	<td>{!! Form::date('due_date',$row[4],array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::text('waybill_amount',$row[5], array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::checkbox('the_presence_of_the_original_document',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::text('notes',$row[6],array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::text('first_payment_amount',null,array('class' => 'form-control')) !!}</td></td>
						    		<td>{!! Form::text('balance_owed',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::text('remainder_of_the_debt_first_payment',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::date('date_of_payment',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::date('date_of_recourse',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::date('the_date_of_termination_of_the_period_of_regression',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::date('the_date_of_a_regular_supply',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::text('the_actual_delay',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::date('date_of_funding',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::date('end_date_of_funding',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::checkbox('return',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::checkbox('state',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::checkbox('status',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::text('balance_owed_rub',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::checkbox('state_debt',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::text('act',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::date('date_of_act',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::text('remainder_of_the_debt_first_payment_rub',null,array('class' => 'form-control')) !!}</td>
						    		<td>{!! Form::text('type_of_factoring',null,array('class' => 'form-control')) !!}</td>
						    	</tr>
						    @empty
						    	<p>Данных нет</p>
						    @endforelse -->
					  </tbody>
					</table>
				</div>
				<div class="row">
					<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
					  	{!! Form::submit('Сохранить',array('class' => 'btn btn-success')) !!}
					</div>
					
				</div>
			{!! Form::close() !!}
		</div>
	</div>
@stop