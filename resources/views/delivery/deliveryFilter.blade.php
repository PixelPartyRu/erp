<div class="panel panel-success openClickTable">
		<div class="panel-heading">
		<span>Фильтр</span>
			<i class="fa fa-chevron-down"></i>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
					<div class="panel panel-success">
						<div class="panel-heading">Статус поставки</div>
						<div class="panel-body">
							<label for="StatusRegistration">Зарегестрированные:</label>
			  				{!! Form::checkbox('StatusRegistration','Зарегестрирована',false,['class' => "deliveryFilter deliveryFilterStatus",'id' => 'StatusRegistration']) !!}
			  				<label for="StatusNotVerefication">Не верифицированные:</label>
			  				{!! Form::checkbox('StatusNotVerefication','Не верифицирована',false,['class' => "deliveryFilter deliveryFilterStatus",'id' => 'StatusNotVerefication']) !!}
			  				<label for="StatusVerefication">Верифицированные:</label>
			  				{!! Form::checkbox('StatusVerefication','Верифицирована',false,['class' => "deliveryFilter deliveryFilterStatus",'id' => 'StatusVerefication']) !!}
			  				<label for="StatusToFinancing">К финансированию:</label>
			  				{!! Form::checkbox('StatusToFinancing','К финансированию',false,['class' => "deliveryFilter deliveryFilterStatus",'id' => 'StatusToFinancing']) !!}
			  				<label for="StatusRejected">Отклоненные:</label>
			  				{!! Form::checkbox('StatusRejected','Отклонена',false,['class' => "deliveryFilter deliveryFilterStatus",'id' => 'StatusRejected']) !!}
			  				<label for="StatusFinancing">Профинансированные:</label>
			  				{!! Form::checkbox('StatusFinancing','Профинансирована',false,['class' => "deliveryFilter deliveryFilterStatus",'id' => 'StatusFinancing']) !!}

						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<div class="panel panel-success">
						<div class="panel-heading">Состояние поставки</div>
						<div class="panel-body">
							{!! Form::select('filterState', array('0' => 'Выбор состояния','true' => 'Погашена','false' => 'Непогашена'),'0',['class'=>"deliveryFilter selectPicker",'id' => 'filterState']) !!}
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<div class="panel panel-success">
						<div class="panel-heading">Клиент(ИНН)</div>
						<div class="panel-body">
							{!! Form::select('filterClient', ['0' => 'Выбор клиента'] + array_pluck($clients, 'name', 'id'),'0',['class' => "deliveryFilter selectPicker",'id' => 'filterClient']) !!}
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<div class="panel panel-success">
						<div class="panel-heading">Дебитор(ИНН)</div>
						<div class="panel-body">
							{!! Form::select('filterDebtor', ['0' => 'Выбор дебитора'] + array_pluck($debtors, 'name', 'id'),'0',['class' => "deliveryFilter selectPicker",'id' => 'filterDebtor']) !!}
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
					<div class="panel panel-success">
						<div class="panel-heading">
							Дата
							{!! Form::checkbox('filterDateOpen','1',false,['id' => 'filterDateOpen']) !!}
						</div>
						<div class="panel-body">
							<label for="filterDateRadioRegistry">Реестра:</label>
							{!! Form::radio('filterDateRadio','1',false,['class' => "deliveryFilter deliveryRadio deliveryRadioDate",'id' => 'filterDateRadioRegistry','disabled','checked']) !!}
							<label for="filterDateRadioDelivery">Накладной:</label>
							{!! Form::radio('filterDateRadio','2',false,['class' => "deliveryFilter deliveryRadio deliveryRadioDate",'id' => 'filterDateRadioDelivery','disabled']) !!}
							<label for="filterDateRadioFinance">Финансирования:</label>
							{!! Form::radio('filterDateRadio','3',false,['class' => "deliveryFilter deliveryRadio deliveryRadioDate",'id' => 'filterDateRadioFinance','disabled']) !!}

							{!! Form::date('filterDateRadio',null,['class' => "deliveryFilter deliveryRadioDate",'id' => 'filterDateRadioDateStart','disabled']) !!}
							<span> - </span>
							{!! Form::date('filterDateRadio',null,['class' => "deliveryFilter deliveryRadioDate",'id' => 'filterDateRadioDateFinish','disabled']) !!}
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<div class="panel panel-success">
						<div class="panel-heading">Реестр</div>
							{!! Form::select('filterRegistry',  ['0' => 'Выбор реестра'] + array_pluck($deliveries, 'registry', 'registry'),'0',['class' => "deliveryFilter selectPicker",'id' => 'filterRegistry']) !!}
						<div class="panel-body">

						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<input type="button" class="btn btn-success" value="Обновить" id="filterUpdate">
				</div>
			</div>
		</div>
</div>