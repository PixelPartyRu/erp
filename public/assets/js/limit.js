var ajaxReload = function(){
		data='sort='+$('.sort.active-sort').attr('name')+'&'+'sortDirection='+$('tbody#ajaxUpdate').attr('sortDirection')+'&';
		data+=$('#count_congestion').attr('name')+'='+$('#count_congestion').val()+'&'+$('#client_id_select').attr('name')+'='+$('#client_id_select').val()
		console.log(data)
		$.ajax({
           	type: "GET",
           	url: document.location.href,
           	data: data,
           	success: function(data)
		   		{
		   			$('tbody#ajaxUpdate').empty().append(data);
		   			$('.editable').editable({
						ajaxOptions: {
						    type: 'put',
						    dataType: 'json'
						},
						success: function(response, newValue) {
							ajaxReload();
				   		}
					});
		   		}
		});
	}
$(document).ready(function(){
	$('body').on('change','#client_id',function(){
		$.get( "/relationsByClient/"+$(this).val(), function( data ) {
			console.log(data);
			$('#debtor_id').empty().append('<option disabled selected>Выберите дебитора</option> ');
			$.each(data, function(index, item){
				append='<option value="'+item["id"]+'">'+item["debtor"]["name"]+' ('+item["debtor"]["inn"]+')</option>';
				$('#debtor_id').append(append).selectpicker('refresh');
		    });
		});
	})
	$('body').on('change','#debtor_id',function(){
		form=$(this).closest('form');
		$.get( "/limit/"+$(this).val(), function( data ) {
			if(data['value']==undefined){
				$('#limit_value').val('');
				$('#limit_value_old').text('Лимит не установлен');
				$(form).find('input[type="submit"]').val('Создать')
			}
			else{
				$('#limit_value').val('');
				$('#limit_value_old').text('Текущий лимит: '+data['value']);
				$(form).find('input[type="submit"]').val('Сохранить')
			}
			action=document.location.href+'/'+data['id'];
			$(form).closest('form').attr('action',action);
			$(form).find('input[type="submit"]').removeAttr('disabled');
		});
	})
	$('body').on('submit','#addLimit',function(event){
		event.preventDefault();
		data = $(this).serialize();
		url = $(this).attr('action');
	  	$.ajax({
           type: "POST",
           url: url,
           data: data, // serializes the form's elements.
           success: function(data)
           {
           		if(data['callback']=='success'){
           			message(data);
           			ajaxReload();
           			$('#limit_value_old').text('Текущий лимит: '+$('#limit_value').val());
           		}
           		else{
           			message(data);
           		}
           }
        });
	})
	$('body').on('change','#count_congestion,#client_id_select',function(){
		ajaxReload();
	})
	$('body').on('click','.sort',function(event){
		event.preventDefault();
		if($(this).hasClass('active-sort')){
			if($('tbody#ajaxUpdate').attr('sortDirection')=='ASC'){
				$('tbody#ajaxUpdate').attr('sortDirection','DESC')
				$(this).next().removeClass().addClass('fa fa-arrow-circle-o-up')
			}else{
				$('tbody#ajaxUpdate').attr('sortDirection','ASC')
				
				$(this).next().removeClass().addClass('fa fa-arrow-circle-o-down')
			}
			ajaxReload();
		}else{
			$('.sort').removeClass('active-sort');
			$(this).addClass('active-sort');
			$('tbody#ajaxUpdate').attr('sortDirection','DESC')
			$(this).parent().find('i').removeClass();
			$(this).next().removeClass().addClass('fa fa-arrow-circle-o-up')
			ajaxReload();
		}
		
	})
})