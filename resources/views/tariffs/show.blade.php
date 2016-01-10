
@if ( count($tariff->commissions) > 0)
				<table class="table table-striped" id="client-table">
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
						<tr>
							<td>{{ $commission->name }}</td>
							<td>{{ $commission->nds }}</td>
							<td>{{ $commission->deduction }}</td>
							<td>{{ $commission->payer }}</td>
						</tr>
@empty
	<p>Тариф "{{$tariff->name}}" создан, теперь можно добавить к нему комиссии</p>
@endforelse
@if ( count($tariff->commissions) > 0)
					</tbody>
				</table>
@endif
{!! Form::open(array('action' => 'CommissionController@store','id' => 'addCommission')) !!}
    {{ Form::select('commission_select', array('/commission/finance' => 'Вознаграждение за пользование денежными средствами', '/commission/document' => 'Плата за обработку одного документа','/commission/peni' => 'Пеня за просрочку','/commission/udz' => 'Вознаграждение за УДЗ'), '0',array('id' => 'commission_select','class'=>'selectpicker')) }}
    {{ Form::hidden('name', 'Вознаграждение за пользование денежными средствами', array('id' => 'commission_name')) }}
    {{ Form::hidden('tariff_id',$tariff->id , array('id' => 'tariff_id')) }}
    <div id="ajaxLoad"></div>
{!! Form::close() !!}