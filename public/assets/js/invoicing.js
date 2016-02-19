update = function(){
	$.get('/invoicing?client_id='+$("#client_id").val()+'&status='+$("#status").val()+'&month='+$("#month").val()+'&year='+$("#year").val(), function( data ) {
		$('#AjaxUpdate').empty().append(data);
		console.log(data);
		if(data=='<span>С выбранными параметрами счетов нет!!!</span>' && $('#month').val()!=''){
			$('#update').prop('disabled', true);
			$('#create').prop('disabled', false);
			$('#client_id').val('all');
		}else{
			$('#create').prop('disabled', true);
			$('#update').prop('disabled', false);
		}
	})
}
$(document).ready(function(){
	$('#DeleteAlert .modal-title').text('Закрытие месяца');
	$('#DeleteAlert .modal-body p').text('Вы уверены, что хотите закрыть месяц?');
	$('#DeleteAlert form').remove();
	$('#DeleteAlert .modal-footer').append('<button id="create_submit" type="submit" class="btn btn-danger btn-mini">Да</button>')
	$.each(JSON.parse(Dates), function(year, item) {
		$('#year').append("<option value='"+year+"'>"+year+"</option>")
	});
	var months = ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'];
	console.log(JSON.parse(Dates)['2016'])
	$('body').on('change','#year',function(){
		if($(this).val()!=''){
			$('.btn.btn-success').prop('disabled', true);
			$('#month').empty()
			$('#month').append('<option value="" selected="selected">Выберите месяц</option>');
			$.each(JSON.parse(Dates)[$(this).val()], function(i,month) {
				$('#month').append("<option value='"+month+"'>"+months[i-1]+"</option>")
		    });
		}
		$('#month').val(null).prop('disabled', false).selectpicker('refresh')
	})
	$('body').on('change','.filter_select',function(){
		$('.btn.btn-success').prop('disabled', true);
		$.get('/invoicing?client_id='+$("#client_id").val()+'&status='+$("#status").val()+'&month='+$("#month").val()+'&year='+$("#year").val(), function( data ) {
			console.log(data);
			if(data=='<span>С выбранными параметрами счетов нет!!!</span>' && $('#month').val()!=''){
				$('#update').prop('disabled', true);
				$('#create').prop('disabled', false);
				$('#client_id').val('all');
				$('#AjaxUpdate').empty().append(data);

			}else{
				$('#create').prop('disabled', true);
				$('#update').prop('disabled', false);
			}
		})
	})
	$('body').on('click','#create',function(){
		$('#DeleteAlert').modal('show');
	})
	$('body').on('click','#create_submit',function(){
		if($('#month').val!=null){
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				type: "POST",
				url: "/invoicing",
				data: "year="+$("#year").val()+"&month="+$("#month").val()+"&_token="+CSRF_TOKEN,
				success: function(msg){
					update();
					$('#DeleteAlert').modal('hide');
			  	}
			});
		}
	})
	$('body').on('click','#update',function(){
		update();
	})
})
