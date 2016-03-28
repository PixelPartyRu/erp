
<!-- Modal -->
<div id="importModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Импорт файла</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
		{!! Form::open(array('action' => 'DeliveryController@store', 'files' => true, 'class'=>'noDoubleClickNoAjaxForm')) !!}
		    {!! Form::label('getFile', 'Загрузить файл с исходными данными:')!!}
		    {!! Form::file('report')!!}
			<label for="Input1">Наличие оригинала документа:</label>
		  	{!! Form::select('the_presence_of_the_original_document',array('0'=>'Нет',1=>'Да'),array('class' => 'form-control','id' => 'Input1')) !!}
		    {!! Form::submit('Импорт поставок',array('class'=>'btn btn-success')) !!}
		{!! Form::close('') !!}
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    </div>

  </div>
</div>