
<!-- Modal -->
<div id="verificationModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close verificationModalClose">&times;</button>
        <h4 class="modal-title">Верификация поставок</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <label for="verificationDate">Дата верификации</label>
            <input type="date" id="verificationDate" value='{{ $dateToday }}' class="form-control">
          </div>
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <label for="verificationRespondent">ФИО респондента</label>
            <input type="text" id="verificationRespondent" class="form-control">
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr> 
                <th><input type="checkbox" id="verificationPopup_all" checked></th>
                <th>NN</th>
                <th>Дебитор</th>
                <th>Накладная</th>
                <th>Дата накладной</th>
                <th>Сумма накладной</th>
                <th>Сумма первого платежа</th>
              </tr>
            </thead>
            <tbody id="popup-table">
            </tbody>
          </table>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <textarea name="verificationComents" readonly id="verificationComents" class='form-control' cols="30" rows="3"></textarea>
            </div>
        </div>
      </div>
      
      <div class="modal-footer">
        <input type="button" id="financeBtn" value="Финансировать" class="btn btn-success">
        <input type="button" id="verificationBtn" value="Верифицировать" class="btn btn-success">
        <input type="button" id="notVerificationBtn" value="Не верифицировать" class="btn btn-success">
        <button type="button" class="btn btn-danger verificationModalClose">Отменить</button>
      </div>
    </div>
  </div>
</div>