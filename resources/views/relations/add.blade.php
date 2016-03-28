		<div class="panel-body">
			{!! Form::open(array('action' => 'RelationController@store','class'=>'noDoubleClickNoAjaxForm')) !!}
				<div class="row">
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4"> 
							  			{!! Form::select('client_id',['0' => 'Выбор клиента (ИНН)'] + select_inn($clients, 'full_name', 'id'),0, array('class'=>'selectpicker')) !!}
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4"> 
							  			{!! Form::select('debtor_id',['0' => 'Выбор дебитора (ИНН)'] + select_inn($debtors, 'full_name', 'id'),0, array('class'=>'selectpicker')) !!}
							  		</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4" id="relation_selectors" style=""> 
									{!! Form::select('agreement_id',['0' => 'Договоров нет'],0, array('class'=>'selectpicker','disabled','id'=>'agreement_id')) !!}
									</div>
									
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
										<div class="row">
											<div class="col-xs-6"><label for="created_at">Условия вступают в силу</label></div>
											<div class="col-xs-6">{!! Form::date('created_at',date('Y-m-d'), array('class'=>'form-control inline')) !!}</div>
										</div>
									</div>
									<div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4" id="act">
										<label for="confedential_factoring">Конфеденциальный факторинг</label>
										{!! Form::checkbox('confedential_factoring', 'true');!!}<br />
										{!! Form::checkbox('active', 'true', true, array('style'=>'display:none'));!!}
									</div>
				</div>
						<div class="panel panel-success">
							<div class="panel-heading">Условия связи</div>
							<div class="panel-body">
							<div class="row">
								<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-3">
									<label for="rpp">Коэффициент финансирования(%)</label>
								  	{!! Form::text('rpp',null,array('class' => 'form-control small_checkbox inline','id' => 'rpp','required'=>'true')) !!}
								</div>

								<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-4">
									<label for="deferment_start">Отсчет начала отсрочки</label>
									{!! Form::select('size', array('true' => 'Дата накладной', 'false' => 'Дата финансирования'), 'true',array('class'=>'selectpicker inline date_naklad')) !!}
								</div>
									<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-5">
									  	<label for="original_documents_select">Документы</label>
										{!! Form::select('original_documents_select', array('2' => 'Первичные документы через', '0' => 'Финансирование по оригиналам', '1' => 'Оригиналы по запросу'), '2', array('class'=>'selectpicker inline document_finance','id'=>'original_documents_select')) !!}
										<span id="original_documents_value">
										{!! Form::text('original_documents_value',null,array('class' => 'form-control small_checkbox inline','id' => '')) !!}	
										дней
										</span>
									</div>
							</div>
							<div class="row">
								<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-4">
									<label for="deferment">Отсрочка</label>
								  	{!! Form::text('deferment',null,array('class' => 'form-control small_checkbox inline','id' => 'deferment','required'=>'true')) !!}
								  	{!! Form::select('deferment_type', array('Календарных дней' => 'Календарных дней', 'Банковских дней' => 'Банковских дней'), 'Календарных дней', array('class'=>'selectpicker select_period_type')) !!}
								</div>
								<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-4">
									<label for="deferment">Период ожидания</label>
								  	{!! Form::text('waiting_period',null,array('class' => 'form-control small_checkbox inline','id' => 'waiting_period','required'=>'true')) !!}
								  	{!! Form::select('waiting_period_type', array('Календарных дней' => 'Календарных дней', 'Банковских дней' => 'Банковских дней'), 'Календарных дней', array('class'=>'selectpicker select_period_type')) !!}
								</div>
								<div class="form-group col-xs-12 col-sm-3 col-md-3 col-lg-4">
									<label for="regress_period">Период регресса</label>
								  	{!! Form::text('regress_period',null,array('class' => 'form-control small_checkbox inline','id' => 'regress_period','required'=>'true')) !!}
								  	{!! Form::select('regress_period_type', array('Календарных дней' => 'Календарных дней', 'Банковских дней' => 'Банковских дней'), 'Календарных дней', array('class'=>'selectpicker select_period_type')) !!}
								</div>
							</div>
						</div>										
					
				</div>
				<div class="clearfix"></div>
				<div class="panel panel-success">
									<div class="panel-heading">Условия контракта</div>
									<div class="panel-body" >
										<div class="row contract">
											<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-4">
												<div class="conctract_input_block"> 
												<label for="contract_code">Номер договора</label>
											  	{!! Form::text('contract_code',null,array('class' => 'form-control inline input_contract','id' => 'contract_code')) !!}
												</div>
												<div class="conctract_input_block">
												<label for="contract_name">Наименование</label>
											  	{!! Form::text('contract_name',null,array('class' => 'form-control inline input_contract','id' => 'contract_name')) !!}
												</div>
												<div class="conctract_input_block">
												<label for="contract_code_1c">Номер договора для 1С</label>
											  	{!! Form::text('contract_code_1c',null,array('class' => 'form-control inline input_contract','id' => 'contract_code_1c')) !!}
												</div>
											</div>
											<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-4">
												<div class="conctract_input_block">
												<label for="contract_gd_debitor_1c">Номер ГД (дебитора) для 1С</label>
											  	{!! Form::text('contract_gd_debitor_1c',null,array('class' => 'form-control inline input_contract','id' => 'contract_gd_debitor_1c')) !!}
												</div>
												<div class="conctract_input_block">
												<label for="contract_created_at">Дата договора</label>
											  	{!! Form::date('contract_created_at',null,array('class' => 'form-control inline input_contract')) !!}
												</div>
												<div class="conctract_input_block">
												<label for="contract_date_end">Действителен до</label>
											  	{!! Form::date('contract_date_end',null,array('class' => 'form-control inline input_contract')) !!}
												</div>
											</div>
											<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-4">
												{!! Form::textarea('contract_description',null,array('class' => 'form-control','id' => 'contract_description', 'rows'=>'5', 'placeholder'=>'Коментарии')) !!}
											</div>
										</div>
									</div>
								</div>
							<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-3">
										  	{!! Form::select('tariff_id',['0' => 'Выберите тариф'] + array_pluck($tariffs, 'name', 'id'), 0, array('class'=>'selectpicker')) !!}
							</div>
							<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-3">							
							{!! Form::submit('Создать связь', array('class' => 'btn btn-success')) !!}
							</div>
											{!! Session::get('message') !!}
				{!! Form::close() !!}			
		</div>

