var getComission = function(id,type,tariff_id,commissionName){
	$.ajax({
       type: "GET",
       url: '/commission/'+type, // serializes the form's elements.
       data: { 
       			id: id,
       			tariff_id: tariff_id,
       			commissionName: commissionName
       		 },
       success: function(data)
       {	
          	$('#ajaxLoadCommission').empty().append(data); 
           	$('.selectpicker').selectpicker('refresh')
           	$('#range_form .range_form_filds .fa-minus').hide()
           	$('#range_form .range_form_filds:gt(0):last .fa-minus').show()
       }
    });
}
var TariffReload = function(){
		data='sort='+$('.sort.active-sort').attr('name')+'&'+'sortDirection='+$('tbody#ajaxUpdate').attr('sortDirection');
		$('.filtrCheckbox:checked').each(function() {
			data=data+'&'+$(this).attr('name')+'='+$(this).val;
		})
		console.log(data)
		$.ajax({
           	type: "GET",
           	url: '/tariff',
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
				        	console.log(response);
				        	console.log(newValue)
				   		}
					});
		   		}
		});
	}
var getTariff = function(url){
  	$.get( url, function( data ) {
  		$('#addCommissions .modal-content').attr('data-update',url).empty().append(data);
  		$('#addCommissions').modal('show');
  		$('.selectpicker').selectpicker('refresh');
	});
}
$(document).ready(function(){
	tariff_id=0;

	$( "body" ).on('submit','#addTariff',function( event ) {
		event.preventDefault();
		$('#small_modal').modal('hide');
		data = $(this).serialize();
		url = $(this).attr('action');
	  	$.ajax({
           type: "POST",
           url: url,
           data: data, // serializes the form's elements.
           success: function(data)
           {
           		if(data['callback']=='success'){
           			getTariff("/tariff/"+data['tariff_id']);
           			message(data);
           			TariffReload();
           		}
           		else{
           			message(data);
           		}
           }
        });
	});
	$( "body" ).on('submit','.ajaxFormCommission',function( event ) {
		event.preventDefault();
		data = $(this).serialize();
		data = data.split('%2C').join('.');
		url = $(this).attr('action');
	  	$.ajax({
	        type: "POST",
	        url: url,
	        data: data, // serializes the form's elements.
	        success: function(data)
	        {
	        	getTariff("/tariff/"+data)
	        }
        });
	});
	$('body').on('click','.addItem',function(){
		event.preventDefault();
		tr=$(this).closest('tr');
		id=$(tr).attr('id');
		tariff_id=$(tr).attr('data-tariff-id');
		type=$(tr).find('#commission_select').val();
		commissionName=$(tr).find('#commission_select option:selected').text();
		getComission(id,type,tariff_id,commissionName);
	})
	$('body').on('click','.EditItem',function(event){
		event.preventDefault();
		tr=$(this).closest('tr');
		id=$(tr).attr('id');
		tariff_id=$(tr).attr('data-tariff-id');
		type=$(tr).attr('data-comission-type');
		commissionName=$(tr).find('td:first-child').text();
		getComission(id,type,tariff_id,commissionName);
	})
	$('body').on('click','.fa-rage-add',function(){
		if($('.range_commission_days').val()!==''){
			$('#range_form .range_form_filds:last .range_commission_max').val($('.range_commission_days').val())
			range_commission_min = parseInt($('#range_form .range_form_filds:last .range_commission_min').val())
			range_commission_max = parseInt($('.range_commission_days').val())
			if(range_commission_max<range_commission_min){
				message({callback: "warning", message_shot: "Предупреждение!", message: " Некорректный интервал"})
				return
			}
			$('.range_commission_days').val("");
			if(range_commission_min ==0){
				$('#range_form .range_form_filds:last .rageText').text('до '+range_commission_max+' дн.')
			}
			else{
				$('#range_form .range_form_filds:last .rageText').text(range_commission_min+' - '+range_commission_max+' дн.');
			}
			$('#range_form .range_form_filds:last').clone().insertBefore($('.add_rage'));
			$('#range_form .range_form_filds:last input').val('');
			range_commission_max++

			$('#range_form .range_form_filds:last .range_commission_min').val(range_commission_max);
			$('#range_form .range_form_filds:last .rageText').text( 'от '+range_commission_max+' дн.');
			$('#range_form .range_form_filds .fa-minus').hide()
			$('#range_form .range_form_filds:last .fa-minus').show()
		}
		else{
			message({callback: "warning", message_shot: "Предупреждение!", message: " Введите количество дней"})
		}
	})
	$('body').on('click','.delete_rage:gt(0):last',function(){
		if(parseInt($(this).find('input').val()) > 0){
			$.ajax({
		        type: "DELETE",
		        url: '/commissions_rage/'+$(this).find('input').val(),
		        success: function(data)
		        {
		        }
	        });
		}
		prev_rage=$(this).closest( ".range_form_filds" ).prev()
		$(this).closest( ".range_form_filds" ).remove()
		$(prev_rage).find('.range_commission_max').val('0');
		if($(prev_rage).find('.range_commission_min').val()=='0'){
			$(prev_rage).find('.rageText').text('C первого дня')
		}else{
			$(prev_rage).find('.rageText').text('от '+$(prev_rage).find('.range_commission_min').val()+' дн.')
			$('#range_form .range_form_filds:last .fa-minus').show()
		}
	})
	$('body').on('click','.comissions_edit',function(event){
		event.preventDefault();
		getTariff($(this).attr('href'))
	})
	
	$('body').on('click','.client_show_active',function(event){
		event.preventDefault();
		$(this).find('.modal-content').clone().show().appendTo($('#small_modal').modal('show').find('.modal-dialog').empty());
		$("[data-toggle='tooltip']").tooltip();
	});
	$('body').on('click','.copy_tariff_button',function(event){
		event.preventDefault();
		$(this).find('.modal-content').clone().show().appendTo($('#small_modal').modal('show').find('.modal-dialog').empty());

	});
	$('body').on('keypress','.float_mask',function( key ){
		if((key.charCode < 48 || key.charCode > 57) && (key.charCode != 44) && (key.charCode != 13)&& (key.charCode != 46)){
			message({callback: "warning", message_shot: "Предупреждение!", message: " Некорректный символ"})
			return false;
		}	
	});
	$('body').on('change','.filtrCheckbox',function(){
		TariffReload();
	})
	$('Input#active').prop("checked", true).trigger("change");
	$('body').on('click','.sort',function(event){
		event.preventDefault();
		if($(this).hasClass('active-sort')){
			if($('tbody#ajaxUpdate').attr('sortDirection')=='ASC'){
				$('tbody#ajaxUpdate').attr('sortDirection','DESC')
				$(this).parent().find('i').removeClass().addClass('fa fa-arrow-circle-o-up')
			}else{
				$('tbody#ajaxUpdate').attr('sortDirection','ASC')
				
				$(this).parent().find('i').removeClass().addClass('fa fa-arrow-circle-o-down')
			}
			TariffReload();
		}else{
			$('.sort').removeClass('active-sort');
			$(this).addClass('active-sort');
			$('tbody#ajaxUpdate').attr('sortDirection','DESC')
			$('th i').removeClass();
			$(this).parent().find('i').removeClass().addClass('fa fa-arrow-circle-o-up')
			TariffReload();
		}
		
	})

	
})
