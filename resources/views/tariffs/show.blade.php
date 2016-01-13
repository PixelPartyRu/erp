<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Тариф: "{{$tariff->name}}"</h4>
</div>
<div class="modal-body" id="modal-body">
	@if ( count($tariff->commissions) > 0)
				<table class="table table-striped" id="commissions-table">
				  	<thead>
					  	<tr>
					  		<th>Название</th>
					  		<th>НДС</th>
					  		<th>Удержание комиссии</th>
					  		<th>Платильщик</th>
					  	</tr>
				 	</thead>
				  	<tbody class='layoutTable'>
	@endif
	@forelse($tariff->commissions as $commission)
						<tr class='edit_commision' id="{{$commission->id}}" type="{{$commission->type}}">
							<td class="commissions_name">{{ $commission->name }}</td>
							<td>{{ $commission->nds }}</td>
							<td>{{ $commission->deduction }}</td>
							<td>{{ $commission->payer }}</td>
						</tr>
	@empty
	@endforelse
	@if ( count($tariff->commissions) > 0)
					</tbody>
				</table>
		{!! Form::model($commission, array('route' => array('commission.update', $commission->id), 'method' => 'PUT','class' => 'ajaxFormCommission','id' => 'ajaxFormCommissionEdit')) !!}
		{{ Form::hidden('tariff_id',$tariff->id , array('id' => 'tariff_id')) }}
		<div id="ajaxLoadEdit"></div>
		{!! Form::close() !!}
	@endif

	{!! Form::open(array('action' => 'CommissionController@store','class' => 'ajaxFormCommission')) !!}

	    {{ Form::select('commission_select', array('finance' => 'Вознаграждение за пользование денежными средствами', 'document' => 'Плата за обработку одного документа','peni' => 'Пеня за просрочку','udz' => 'Вознаграждение за УДЗ'), '0',array('id' => 'commission_select','class'=>'selectpicker')) }}
	    {{ Form::hidden('name', 'Вознаграждение за пользование денежными средствами', array('id' => 'commission_name')) }}
	    {{ Form::hidden('tariff_id',$tariff->id , array('id' => 'tariff_id')) }}
	    <div id="ajaxLoadAdd"></div>
	{!! Form::close() !!}
	</div>
	<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
</div>