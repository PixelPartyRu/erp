			<div class="table-responsive">
				<table class="table table-striped" id="client-table">
				  <thead>
				  	<tr class="sv">
						<th>№</th>
				  		<th>Клиент</th>
						<th>Дебитор</th>
				  		<th>РПП %</th>
				  		<th>Отсчет начала отсрочки</th>
				  		<th>Отсрочка</th>
				  		<th>Период ожидания</th>
				  		<th>Период регресса</th>
				  		<th>Оригиналы первичных документов</th>
						<th>Тип факторинга</th>
						<th>Статус</th>
				  		<th></th>
				  	</tr>
				  </thead>
				  <tbody>
				  {{-- */ $num = 0; /* --}}
				  	@forelse($relations as $relation)
						<tr>
							<td>{{ $num += 1 }}</td>
							<td>{{ $relation->client->name }}</td>
							<td>{{ $relation->debtor->name }}</td>
							<td>{{ $relation->rpp}}</td>
							<td>{{ $relation->deferment_start == true ? 'Дата накладной' : 'Дата финансирования' }}</td>
							<td>{{ $relation->deferment }}<span>&nbsp</span>
								@if ($relation->deferment_type == 'Банковских дней')
								БД
								@elseif($relation->deferment_type == 'Календарных дней')
								КД
								@endif
							</td>
							<td>{{ $relation->waiting_period }}<span>&nbsp</span>
								@if ($relation->waiting_period_type == 'Банковских дней')
								БД
								@elseif($relation->waiting_period_type == 'Календарных дней')
								КД
								@endif
							</td>
							<td>{{ $relation->regress_period }}<span>&nbsp</span>
								@if ($relation->regress_period_type == 'Банковских дней')
								БД
								@elseif($relation->regress_period_type == 'Календарных дней')
								КД
								@endif
							</td>
							<td>
								@if ($relation->originalDocument->type == '0')
								По оригиналам
								@elseif($relation->originalDocument->type == '1')
								Нет
								@else
								через &nbsp {{$relation->originalDocument->value}} &nbsp дней
								@endif
							</td>
							<td>{{ $relation->confedential_factoring == true ? 'Конфеденциальный' : 'Открытый' }}</td>
							<td>{{ $relation->active == true ? 'Активна' : 'Не активна' }}</td>
							<td><a href="/relation/{{ $relation->id }}/edit"><i class="fa fa-pencil" data-title="Редактировать"></i></a></td>
							

						</tr>
					@empty
						<p>Связей нет</p>
					@endforelse
				  </tbody>
				</table>
			</div>