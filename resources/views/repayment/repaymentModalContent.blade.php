{{ Form::hidden('repaymentId',$repayment->id, array('id' => 'repaymentId')) }}
<div class="row">
	<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<label for="repaymentModalTextarea">Назначение платежа:</label>
		<textarea name="repaymentModalTextarea" id="repaymentModalTextarea" class='form-control' readonly cols="30" rows="7">{{$repayment->purpose_of_payment }}</textarea>
	</div>
	<div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-6">
		<div class="row">
			<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-8">
				<label for="repaymentModalСlientInn">Клиент:</label>
				{!! Form::text('repaymentModalСlientInn',$client->name.'('.$client->inn.')',['class' => "form-control",'readonly','id'=> 'repaymentModalСlientInn']) !!}
				<label for="repaymentModalKorrespInn">Корреспондент:</label>
				{!! Form::textarea('repaymentModalKorrespInn',$repayment->info,['class' => "form-control",'readonly','id' => "repaymentModalKorrespInn", 'size' => '30x3']) !!}
			</div>
			<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-4">
				<label for="repaymentModalSum">Сумма:</label>
				{!! Form::text('repaymentModalSum',number_format($repayment->sum,2,',',' '),['class' => "form-control",'readonly','id'=> "repaymentModalSum"]) !!}
				<label for="repaymentModalBalance">Остаток</label>
				{!! Form::text('repaymentModalBalance',number_format($repayment->balance,2,',',' '),['class' => "form-control",'readonly','id'=> "repaymentModalBalance"]) !!}
				{{ Form::hidden('repaymentBalanceHidden',$repayment->balance, array('id' => 'repaymentBalanceHidden')) }}
			</div>
		</div>
	</div>
</div>
<!-- <div class="row">
	<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<label for="repaymentModalDeliveryType">Тип платежа:</label>
		@if ($repayment->type === 0)
			{!! Form::select('repaymentModalDeliveryType',array('1'=>'Платежи клиентов','0'=>'Платежи дебиторов'),'0',['class' => "form-control",'id'=> "repaymentModalDeliveryType"]) !!}
		@else
			{!! Form::select('repaymentModalDeliveryType',array('1'=>'Платежи клиентов','0'=>'Платежи дебиторов'),'1',['class' => "form-control",'id'=> "repaymentModalDeliveryType"]) !!}
		@endif
	</div>
</div> -->

<div class="modal-footer">
	<button type="button" class="btn btn-success" id="repaymentModalPush">Провести</button>
	<button type="button" class="btn btn-danger" id="repaymentClose" data-dismiss="modal">Закрыть</button>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<ul class="nav nav-tabs" id="repaymentTabs">
		  <li class="active"><a href="#" data-id="delivery">Поставки</a></li>
		  <li><a href="#" data-id="commission">Вознаграждения</a></li>
		  <li><a href="#" data-id="repayment">Погашенные</a></li>
		</ul>
		<div class="table-responsive" id="tableRepaymentContent">
		</div>
	</div>
</div>
