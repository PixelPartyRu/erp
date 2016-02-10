<!-- Modal -->
<div id="createModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      {!! Form::open(array('action' => 'RepaymentController@createStore')) !!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Создание П/П в ручном режиме</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
              <label for="paymentNumber">П/П №:</label>
              {!! Form::text('paymentNumber',null,array('class' => 'form-control','id' => 'paymentNumber')) !!}
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
              <label for="paymentDate">Дата П/П</label>
              {!! Form::date('paymentDate',date('Y-m-d'),array('class' => 'form-control','id' => 'paymentDate')) !!}
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
              <label for="paymentSum">Сумма</label>
              {!! Form::text('paymentSum',null,array('class' => 'form-control','id' => 'paymentSum')) !!}
            </div>
          </div>
          <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
               <label for="paymentTextarea">Назначение платежа:</label>
                {!! Form::text('textarea',null,array('class' => 'form-control','id' => 'paymentTextarea')) !!}
             </div>
          </div>
         <div class="row">
           <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
             <label for="paymentСlient">Клиент</label>
             {!! Form::select('clientPayerCreate',['0' => 'Выбрать клиента'] + array_pluck($clients, 'name', 'id'),0, array('class'=>'selectpicker','data-style' => 'btn-danger')) !!}
           </div>
           <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <label for="paymentDebtor">
              Дебитор
              {!! Form::checkbox('radioCreate','1',false,array('id' => 'paymentDebtor')) !!}
            </label>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                   {!! Form::select('debtorPayerCreate',['0' => 'Выбрать дебитора'] + array_pluck($debtors, 'name', 'id'),'0', array('class'=>'selectpicker debtorPayerCreate','disabled')) !!}
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                   {!! Form::select('debtorPayerCreateRadio',['0' => 'Платеж от дебитора','1' => 'Платеж от клиента'],'1', array('class'=>'selectpicker debtorPayerCreate','disabled')) !!}
                </div>
            </div>
           </div>
          </div>       
      </div>
      <div class="modal-footer">
         {!! Form::button('Добавить',array('class' => 'btn btn-success','id'=> 'modal-btn-success')) !!}
        <button type="button" class="btn btn-danger" data-dismiss="modal">Отмена</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>