
<!-- Modal -->
<div id="popapModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close popapClose">&times;</button>
        <h4 class="modal-title">Подтверждение финансирования</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <label for="financingSuccessDate">Дата финансирования</label>
            <input type="date" id="financingSuccessDate" value='{{ $dateToday }}' class="form-control">
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                 <tr>
                  <th></th>
                  <th>Клиент</th>
                  <th>Сумма</th>
                  <th>Количество накладных</th>
                  <th>Тип финансирования</th>
                  <th>Реестр</th>
                  <th>Дата реестра</th>
                </tr>
              </tr>
            </thead>
            <tbody id="popup-table">
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <input type="button" id="financingSuccessBtn" value="Подтвердить" class="btn btn-success">
        <input type="button" value="Закрыть" class="popapClose btn btn-danger">
      </div>
    </div>

  </div>
</div>