				  @forelse($tariffs as $tariff)
						<tr>
							<td><a href="#" id="name" data-token="{{ csrf_token() }}" class="editable" data-type="text" data-pk="1" data-url="/tariff/{{$tariff->id}}" data-title="Название тарифа">{{ $tariff->name }}</a></td>
							<td>{{ $tariff->created_at ? date_format($tariff->created_at,'d/m/Y') : ' '}}</td>
							<td>{{ $tariff->active == true ? 'Активный' : 'Не активный' }}</td>
							<td>{{ $tariff->deactivated_at ? @date('d/m/Y',strtotime($tariff->deactivated_at)) : ' ' }}</td>
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
							        	<?php 	
							        		$clients = App\Client::whereHas('relations', function ($query) use ($tariff) {
												    $query->where('tariff_id', '=', $tariff->id);
												})->get();
							        	?>
										@forelse($clients as $client)
											{{ $client->name }} <a href="/client/{{ $client->id }}/edit"><i class="table_icons fa fa-pencil" data-toggle="tooltip" title="Редактировать клиента"></i></a><br>
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
									{{ Form::button('<i class="fa fa-close" data-toggle="tooltip" title="Отключить"></i>', array('class'=>'', 'type'=>'submit')) }}
								{{ Form::close() }}
								</td>
							@else
								<td>
									{{ Form::open(array('action' => array('TariffController@activateTariff', $tariff->id), 'method' => 'GET')) }}
										{{ Form::button('<i class="fa fa-check" data-toggle="tooltip" title="Включить"></i>', array('class'=>'', 'type'=>'submit')) }}
									{{ Form::close() }}
								</td>
							@endif
						</tr>
					@empty
						<p>Тарифов нет</p>
					@endforelse