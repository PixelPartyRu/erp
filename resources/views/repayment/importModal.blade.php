
<!-- Modal -->
<div id="importModal" class="modal fade" role="dialog">
  <div class="modal-dialog big-modal">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Импорт файла</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
      		<form>
                <input name="file" id="importFile" encoding='multipart/form-data'  accept=".txt" type="file" />
                <input type="button" id="importBtn" value="Загрузка файла клиент-банка" class="btn btn-success" />
          </form>
    		</div>
        <div class="table-responsive" id="importModalTableBody">
          
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="sendImportRepayment">Сохранить</button>
        <button type="button" class="btn btn-danger" id="importClose" data-dismiss="modal">Отменить</button>
      </div>
    </div>

  </div>
</div>