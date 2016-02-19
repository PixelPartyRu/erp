
@extends('layouts.master')

@section('title', 'Заголовок страницы')

@section('stylesheet')
  	<link rel="stylesheet" type="text/css" href="/assets/css/limits.css">
@stop

@section('javascript')
  	<script type="text/javascript" src="/assets/js/limit.js"></script>
@stop

@section('content')
	<div class="panel panel-success openClickTable">
		<div class="panel-heading">
			<span>Фильтр</span>
			<i class="fa fa-chevron-down"></i> 
		</div>		
		<div class="panel-body" id="filtr">
			<div class="row">
				<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
					<select class="selectpicker" name="client_id_select" id="client_id_select" required="required">
						<option value="all" selected>Все Клиенты</option> 
						@foreach ($relations as $relation)
					 	<option value="{{$relation->client->id}}">{{$relation->client->name}}</option>
						@endforeach
					</select>
				</div>
<!-- 				<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
					{{ Form::select('count_congestion', ['remainder_of_the_debt_first_payment'=>'по сумме финансирования','balance_owed'=>'по номиналу накладной'], 'remainder_of_the_debt_first_payment',array('id' => 'count_congestion','class'=>'selectpicker')) }}
				</div> -->
				<input type="hidden" name="count_congestion" id="count_congestion" value='balance_owed'>
			</div>			
  		</div>
	</div>
	<div class="panel panel-success openClickTable">
		<div class="panel-heading">
			<span>Создание лимита</span>
			<i class="fa fa-chevron-down"></i> 
		</div>		
		<div class="panel-body">

				{!! Form::open(array('route' => array('limit.update', ''),'id' => 'addLimit','method' => 'PUT')) !!}
					<div class="row">
						<div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-2">
							<select class="selectpicker" name="client_id" id="client_id" required="required">
								<option disabled selected>Выберите клиента</option> 
								@foreach ($relations as $relation)
							 	<option value="{{$relation->client->id}}">{{$relation->client->name}} ({{$relation->client->inn}})</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-xs-12 col-sm-6 col-md-3 col-lg-2">
							<select class="selectpicker" name="relation_id" id="debtor_id" required="required">
								<option disabled selected>Выберите дебитора</option> 
							</select>
						</div>
						<div class="form-group col-xs-12 col-sm-4 col-md-2 col-lg-2">
							<span class="text_in_forms" id="limit_value_old"></span>
						</div>
						<div class="form-group col-xs-4 col-sm-2 col-md-1 col-lg-2">
							<label for="limit_value">Лимит:</label>
						</div>
						<div class="form-group col-xs-8 col-sm-6 col-md-1 col-lg-2">
						  	{!! Form::text('value',null,array('class' => 'float_mask form-control','id' => 'limit_value','required' => 'required')) !!}
						</div>
						<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" id='btn-container-line'>
						  	{!! Form::submit('Cоздать лимит',array('class' => 'btn btn-success','disabled' => 'disabled')) !!}
						</div>
					</div>
				{!! Form::close() !!}
  		</div>
	</div>
	<div class="panel panel-info">
		<div class="panel-heading">
			<a href="{{URL::current()}}?sort=client_id" name="client_id" class="sort">Лимиты клиентов </a><i class="fa"></i>&nbsp&nbsp&nbsp
			<a href="{{URL::current()}}?sort=debtor_id" name="debtor_id" class="sort">Лимиты дебиторов </a><i class="fa"></i>&nbsp&nbsp&nbsp
			<a href="{{URL::current()}}?sort=relation_id" name="relation_id" class="sort active-sort">Лимиты связей </a><i class="fa fa-arrow-circle-o-up"></i>
		</div>
			<div class="table-responsive">
				<table class="table table-striped" id="debtor-table">
				  <thead>
				  	<tr>
				  		<th></th>
				  		<th>Клиент</th>
				  		<th>Дебитор</th>
				  		<th>Установленный лимит</th>
				  		<th>Использованный лимит</th>
				  		<th>% загруженности</th>
				  		<th>Свободный лимит</th>
				  		<th></th>
				  		<th></th>
				  	</tr>
				  </thead>
				  <tbody id="ajaxUpdate" sortDirection='DESC'>
				 		<?php 
							$value_sum=0;
							$usedLimit_sum=0;
						?>
				  	@forelse($limits as $key => $limit)
						<tr>
							<?php 
								if ($limit->value > 0)
									$congestion= $usedLimit[$key]/$limit->value*100;
								else
									$congestion= 0;
							?>
							<td class="{{ $limit->value < $usedLimit[$key] ? 'danger':''}}">&nbsp&nbsp&nbsp&nbsp</td>
							<td>{{$limit->relation->client->name}}</td>
							<td>{{$limit->relation->debtor->name}}</td>
							<td><a href="#" id="value" class="editable" data-type="text" data-pk="1" data-url="{{URL::current()}}/{{$limit->relation->limit->id}}" data-title="Новый лимит" data-set-value="null">{{number_format($limit->value, 2, ',', ' ')}}</a></td>
							<td>{{number_format($usedLimit[$key], 2, ',', ' ')}}</td>
							<td>{{number_format($congestion, 2, ',', ' ')}}</td>
							<td>{{number_format($limit->value-$usedLimit[$key], 2, ',', ' ')}}</td>
							<td><a data-delete="{{URL::current()}}/{{$limit->relation->limit->id}}" data-method="delete" class="deleteItem"><i class="fa fa-minus" data-toggle="tooltip" title="Удалить"></i></td>
							<td></td>
						</tr>
						<?php 
							$value_sum+=$limit->value;
							$usedLimit_sum+=$usedLimit[$key];
						?>
					@empty
						<tr><td><p>Лимитов нет</p></td></tr>
					@endforelse
					<?php 
						if ($value_sum > 0)
							$congestion= $usedLimit_sum/$value_sum*100;
						else
							$congestion= 0;
						$freeLimit_sum=$value_sum-$usedLimit_sum;
					?>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td>{{number_format($value_sum, 2, ',', ' ')}}</td>
							<td>{{number_format($usedLimit_sum, 2, ',', ' ')}}</td>
							<td>{{number_format($congestion, 2, ',', ' ')}}</td>
							<td>{{number_format($freeLimit_sum, 2, ',', ' ')}}</td>
							<td></td>
							<td></td>
						</tr>
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