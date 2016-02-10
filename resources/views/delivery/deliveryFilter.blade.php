	<div class="panel panel-success">
		<div class="panel-heading">
			<span>Фильтр поставок</span>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-6">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-4">
							<label for="StatusRegistration">Зарегистрированные</label>
			  				{!! Form::checkbox('StatusRegistration','Зарегистрирована',false,['class' => "deliveryFilter deliveryFilterStatus checkbox-inline pull-right",'id' => 'StatusRegistration']) !!}
			  		</div>
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-4">
							<label for="StatusNotVerefication">Не верифицированные</label>
			  				{!! Form::checkbox('StatusNotVerefication','Не верифицирована',false,['class' => "deliveryFilter deliveryFilterStatus checkbox-inline pull-right",'id' => 'StatusNotVerefication']) !!}
					</div>
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-4">
							<label for="StatusVerefication">Верифицированные</label>
			  				{!! Form::checkbox('StatusVerefication','Верифицирована',false,['class' => "deliveryFilter deliveryFilterStatus pull-right",'id' => 'StatusVerefication']) !!}
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-4">	
						<label for="StatusToFinancing">К финансированию</label>
			  			{!! Form::checkbox('StatusToFinancing','К финансированию',false,['class' => "deliveryFilter deliveryFilterStatus checkbox-inline pull-right",'id' => 'StatusToFinancing']) !!}
					</div>
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-4">
			  			<label for="StatusRejected">Отклоненные</label>
			  			{!! Form::checkbox('StatusRejected','Отклонена',false,['class' => "deliveryFilter deliveryFilterStatus checkbox-inline pull-right",'id' => 'StatusRejected']) !!}
					</div>
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-4">				
						<label for="StatusFinancing">Профинансированные</label>
			  			{!! Form::checkbox('StatusFinancing','Профинансирована',false,['class' => "deliveryFilter deliveryFilterStatus pull-right",'id' => 'StatusFinancing']) !!}
					</div>
				</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-4">
					{!! Form::select('filterState', array('0' => 'Все поставки','true' => 'Погашена','false' => 'Непогашена'),'0',['class'=>"deliveryFilter selectpicker",'id' => 'filterState']) !!}
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-2">			
					{!! Form::select('filterClient', ['0' => 'Клиент (ИНН)'] + select_inn($clients, 'name', 'id'),'0',['class' => "deliveryFilter selectpicker",'id' => 'filterClient']) !!}
				</div>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-2">	
					{!! Form::select('filterDebtor', ['0' => 'Дебитор (ИНН)'] + select_inn($debtors, 'name', 'id'),'0',['class' => "deliveryFilter selectpicker",'id' => 'filterDebtor']) !!}
				</div>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-2">					
					{!! Form::select('filterRegistry',  ['0' => 'Все реестры'] + $registries,'0',['class' => "deliveryFilter selectpicker",'id' => 'filterRegistry']) !!}
				</div>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-2">	
					{!! Form::select('filterDebtor', ['0' => 'Выберите период для','Реестра','Накладной','Финансирования'],'0',['class' => "deliveryFilter selectpicker",'id' => 'filterDateRadioRegistry']) !!}
				</div>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-4">
					<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-1">
						<div class="col-xs-12 col-sm-12 col-md-1 col-lg-2">
							<p style="margin-top:5px;">от</p>
						</div>
					</div>	
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							{!! Form::date('filterDateRadio',date('Y-m-d'),['class' => "form-control deliveryFilter deliveryRadioDate",'id' => 'filterDateRadioDateStart','disabled']) !!}
						</div>
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-1">	
						<div class="col-xs-12 col-sm-12 col-md-1 col-lg-2">
							<p style="margin-top:5px;">до</p>
						</div>
					</div>	
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							{!! Form::date('filterDateRadio',date('Y-m-d'),['class' => "form-control deliveryFilter deliveryRadioDate",'id' => 'filterDateRadioDateFinish','disabled']) !!}
						</div>
					</div>	
				</div>
			</div>
			<div class="row" style="margin-top: 10px;">
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-1">
					<input type="button" class="btn btn-success pull-center" value="Показать" id="filterUpdate">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-2 pull-center" id="search_count" style="margin-top: 10px;">
				</div>
			</div>	
		</div>
	</div>