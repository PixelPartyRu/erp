<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Комиссии тарифного плана "{{$tariff->name}}"</h4>
</div>
<div class="modal-body" id="modal-body">
				<table class="table table-striped" id="commissions-table">
				  	<thead>
					  	<tr>
					  		<th>Название</th>
					  		<th>НДС</th>
					  		<th>Удержание комиссии</th>
					  		<th>Плательщик</th>
					  		<th></th>
					  	</tr>
				 	</thead>
				  	<tbody class='layoutTable'>

	@foreach($tariff->commissions as $commission)
						<tr class='commisions' id="{{$commission->id}}" data-tariff-id="{{$tariff->id}}" data-comission-type="{{$commission->type}}">
							<td>{{ $commission->name }}</td>
							<td class="center_text">
							@if($commission->nds)
							<span class="green">&#10004;</span>
							@else
							<span class="red">&#10006;</span>
							@endif
							</td>
							<td class="center_text">
							@if($commission->deduction)
							<span class="green">&#10004;</span>
							@else
							<span class="red">&#10006;</span>
							@endif
							</td>
							<td>
							@if($commission->payer)
							Дебитор
							@else
							Клиент
							@endif
							</td>
							</td>
							<td class="EditItem"><i class="fa fa-pencil" data-toggle="tooltip" title="Редактировать"></i></td>
							<td class="deleteItem" data-delete="/commission/{{$commission->id}}">
									<i class="fa fa-minus" data-toggle="tooltip" title="Удалить комиссию"></i>
							</td>
						</tr>

	@endforeach
	@if(count($commissionTypes)>0)
						<tr class='commisions' id="0" data-tariff-id="{{$tariff->id}}">
							<td class="no-space"> 
								{{ Form::select('commission_select', $commissionTypes, 'balance_owed',array('id' => 'commission_select','class'=>'selectpicker')) }}
							</td>
							<td></td><td></td><td></td><td></td>
							<td class="addItem">
								<i class="fa fa-plus" data-toggle="tooltip" title="Добавить комиссию"></i>
							</td>
						</tr>
	@endif
					</tbody>
				</table>
			<div id="ajaxLoadCommission"></div>
	</div>
	<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
</div>