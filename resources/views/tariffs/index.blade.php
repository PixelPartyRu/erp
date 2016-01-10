@extends('layouts.master')

@section('title', 'Заголовок страницы')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/tariffs.css">
@stop

@section('javascript')
  	<script type="text/javascript" src="/assets/js/tariffs.js"></script>
@stop

@section('content')
	<h1><strong>Тарифы</strong></h1>
	<div class="panel panel-success openClickTable" id="debtorCreate">
		<div class="panel-heading">
			<span>Добавить тариф</span>
			<i class="fa fa-chevron-down"></i> 
		</div>		
		<div class="panel-body">
				{!! Form::open(array('action' => 'TariffController@store','id' => 'addTariff')) !!}
					<div class="row">
						<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label for="InputEmail2">Наименование:</label>
						  	{!! Form::text('name',null,array('class' => 'form-control','id' => 'InputEmail2','required' => 'required')) !!}
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" id='btn-container'>
						  	{!! Form::submit('Добавить',array('class' => 'btn btn-success')) !!}
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							{!! Session::get('message') !!}
						</div>
					</div>
				{!! Form::close() !!}
  		</div>
	</div>
	<div class="panel panel-info">
		<div class="panel-heading">Доступные тарифы <br> {{ Form::checkbox('active', 1, true, ['id' => 'active']) }} <label for="active">активные</label><span>&nbsp &nbsp &nbsp</span> {{ Form::checkbox('deactive', 1, null, ['id' => 'deactive']) }} <label for="deactive">не активные</label></div>
			<div class="table-responsive">
				<table class="table table-striped" id="debtor-table">
				  <thead>
				  	<tr>
				  		<th>Наименование</th>
				  		<th>Дата создания</th>
				  		<th>Статус</th>
				  		<th>Дата прекращения действия</th>
				  		<th></th>
				  		<th></th>
				  	</tr>
				  </thead>
				  <tbody>
				  	@forelse($tariffs as $tariff)
						<tr {{ $tariff->active == true ? 'class=active' : 'class=deactive' }}>
							<td>{{ $tariff->name }}</td>
							<td>{{ $tariff->created_at }}</td>
							<td>{{ $tariff->active == true ? 'Активный' : 'Не активный' }}</td>
							<td>{{ $tariff->deactivated_at }}</td>
							<td><a href="/tariff/{{ $tariff->id }}/edit"><i class="fa fa-pencil"></i></a></td>
							@if ( $tariff->active == true)
								<td>
								{{ Form::model($tariff, array('route' => array('tariff.destroy', $tariff->id), 'method' => 'DELETE')) }}
									{{ Form::button('<i class="fa fa-close"></i>', array('class'=>'', 'type'=>'submit')) }}
								{{ Form::close() }}
								</td>
							@else
								<td>
									{{ Form::open(array('action' => array('TariffController@activateTariff', $tariff->id), 'method' => 'GET')) }}
										{{ Form::button('<i class="fa fa-check"></i>', array('class'=>'', 'type'=>'submit')) }}
									{{ Form::close() }}
								</td>
							@endif
						</tr>
					@empty
						<p>Тарифов нет</p>
					@endforelse
				  </tbody>
				</table>
			</div>
		</div>
	</div>
<!-- Modal -->
<div id="addCommissions" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Комиссии</h4>
      </div>
      <div id="id_last_tariff">
      		{{ Form::hidden('tariff_id', '0', array('id' => 'last_tariff')) }}
      </div>
      <div class="modal-body" id="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@stop

<!--, array('class' => 'btn btn-warning'-->