
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
							<label for="name">Наименование:</label>
						  	{!! Form::text('name',null,array('class' => 'form-control','id' => 'name','required' => 'required')) !!}
						</div>
						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" id='btn-container'>
						  	{!! Form::submit('Добавить',array('class' => 'btn btn-success')) !!}
						</div>
					</div>
				{!! Form::close() !!}
  		</div>
	</div>
	<div class="panel panel-info">
		<div class="panel-heading">Доступные тарифы <br> {{ Form::checkbox('active', 1, null, ['id' => 'active', 'class'=>'filtrCheckbox']) }} <label for="active">активные</label><span>&nbsp &nbsp &nbsp</span> {{ Form::checkbox('deactive', 1, null, ['id' => 'deactive', 'class'=>'filtrCheckbox']) }} <label for="deactive">не активные</label></div>
			<div class="table-responsive">
				<table class="table table-striped" id="debtor-table">
				  <thead>
				  	<tr>
				  		<th><a href="{{URL::current()}}?sort=name&active=1" name="name" class="sort">Наименование </a><i class="fa"></i></th>
				  		<th><a href="{{URL::current()}}?sort=created_at&active=1" name="created_at" class="sort active-sort">Дата создания </a><i class="fa fa-arrow-circle-o-up"></i></th>
				  		<th><a href="{{URL::current()}}?sort=active&active=1" name="active" class="sort">Статус </a><i class="fa"></i></th>
				  		<th><a href="{{URL::current()}}?sort=deactivated_at&active=1" name="deactivated_at" class="sort">Дата прекращения действия </a><i class="fa"></i></th>
				  		<th></th>
				  		<th></th>
				  		<th></th>
				  		<th></th>
				  	</tr>
				  </thead>
				  <tbody id="ajaxUpdate" sortDirection='DESC'>
				  	@forelse($tariffs as $tariff)
						<tr>
							<td><a href="#" id="name" class="editable" data-params="_token:'{{csrf_token}}"data-type="text" data-pk="1" data-url="/tariff/{{$tariff->id}}" data-toggle="tooltip" title="Название тарифа">{{ $tariff->name }}</a></td>
							<td>{{ $tariff->created_at ? date_format($tariff->created_at,'d/m/Y') : ' '}}</td>
							<td>{{ $tariff->active == true ? 'Активный' : 'Не активный' }}</td>
							<td>{{ $tariff->deactivated_at ? @date('d/m/Y',strtotime($tariff->deactivated_at)) : '' }}</td>
							<td>
								<a href="" class="copy_tariff_button">
									<i class="table_icons fa fa-files-o" data-toggle="tooltip" title="Копировать тариф"></i>
									<div class="modal-content">
										<div class="modal-header">
							          		<button type="button" class="close" data-dismiss="modal">&times;</button>
							          		<h4 class="modal-title">Копия тарифа "{{$tariff->name}}"</h4>
										</div>
										<div class="modal-body">
										{{ Form::open(array('action' => 'TariffController@store','id' => 'addTariff')) }}
											<div class="row">
												<div class=" col-xs-12 col-sm-12 col-md-12 col-lg-12">
													<label for="name">Название для нового тарифа:</label>
												  	{{ Form::text('name',null,array('class' => 'form-control','id' => 'name','required' => 'required')) }}
													{{ Form::hidden('tariff_id',$tariff->id , array('id' => 'tariff_id')) }}
												</div>
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id='btn-container'>
												  	{!! Form::submit('Далее',array('class' => 'btn btn-success')) !!}
												</div>
											</div>
										{{ Form::close() }}
										</div>
								        <div class="modal-footer">
								          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
								        </div>
								    </div>
								</a>
							</td>
							<td class="tariffs_clients_link {{ count($tariff->relations)>0 ? 'client_show_active' : 'client_show_deactive'}} " >
								<i class="fa fa-users table_icons" data-toggle="tooltip" title="{{ count($tariff->relations)>0 ? 'Клиенты' : 'Нет клиентов'}}"></i>
								<div class="modal-content tariffs_clients">
									<div class="modal-header">
	          							<button type="button" class="close" data-dismiss="modal">&times;</button>
	          							<h4 class="modal-title">Клиенты с тарифом "{{$tariff->name}}"</h4>
							        </div>
							        <div class="modal-body">
										@forelse($tariff->relations as $relation)
											{{ $relation->client->name }} <a href="/client/{{ $relation->client->id }}/edit"><i class="table_icons fa fa-pencil" data-toggle="tooltip" title="Редактировать клиента"></i></a><br>
										@empty
										@endforelse
									</div>
							        <div class="modal-footer">
							          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
							        </div>
							    </div>
							</td>
							<td><a class="comissions_edit" href="/tariff/{{ $tariff->id }}"><i class="table_icons fa fa-money" data-toggle="tooltip" title="Редактирование комиссий"></i></a></td>

							@if ( $tariff->active == true)
								<td>
								{{ Form::model($tariff, array('route' => array('tariff.destroy', $tariff->id), 'method' => 'DELETE')) }}
									{{ Form::button('<i class="fa fa-minus" data-toggle="tooltip" title="Отключить"></i></i>', array('class'=>'', 'type'=>'submit')) }}
								{{ Form::close() }}
								</td>
							@else
								<td>
									{{ Form::open(array('action' => array('TariffController@activateTariff', $tariff->id), 'method' => 'GET')) }}
										{{ Form::button('<i class="fa fa-minus" data-toggle="tooltip" title="Включить"></i></i>', array('class'=>'', 'type'=>'submit')) }}
									{{ Form::close() }}
								</td>
							@endif
						</tr>
					@empty
						<tr><td><p>Тарифов нет</p></td></tr>
					@endforelse
				  </tbody>
				</table>
			</div>
		</div>
	</div>
<!-- Modal -->
  <div class="modal fade" id="small_modal" role="dialog">
    <div class="modal-dialog modal-sm">
    </div>
  </div>
<div id="addCommissions" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content AjaxUpdateList" data-update="">
    </div>
  </div>
</div>
@stop

<!--, array('class' => 'btn btn-warning'-->