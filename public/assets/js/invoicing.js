$(document).ready(function(){
	year = new Date().getFullYear();
	month = new Date().getMonth()+1;
	months = $('#month option');
	$('body').on('change','#year',function(){
		$('#month').empty()
		if($(this).val()==year){
			$.each(months, function( index, value ) {
				if(index==month){
				  	return false;
				}
				$('#month').append(value);
			});
		}else{
			$('#month').append(months);
		}
		$('#month').val(null).prop('disabled', false).selectpicker('refresh')
	})
	$('body').on('click','input[type="submit"]',function(){
		if($(this).val!=null){
			$.get('/invoicing?client_id='+$("#client_id").val()+'&status='+$("#status").val()+'&month='+$("#month").val()+'&year='+$("#year").val(), function( data ) {
				$('#AjaxUpdate').empty().append(data);
			})
		}
	})

	// 	if($('#client_id').val()==null){
	// 		$(this).val('');
	// 	}else{
	// 		$.get('/invoicing?client_id='+$("#client_id").val()+'&status='+$("#status").val()+'&month='+$(this).val(), function( data ) {
	// 			$('#AjaxUpdate').empty().append(data);
	// 		})
	// 	}
	// })
	// $('body').on('change','#client_id',function(){
	// 	$("#status").val('all');
	// 	$("#month").val('');
	// })
})
