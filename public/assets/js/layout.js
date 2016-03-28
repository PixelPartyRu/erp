$(document).ajaxComplete(function(){
	$('.editable').editable({
		ajaxOptions: {
		    type: 'put',
		    dataType: 'json'
		},
		success: function(response, newValue) {
			ajaxReload();
   		}
	});
    $("[data-toggle='tooltip']").tooltip();
});
$(document).ready(function(){
	tableChecked();

	$('body').on('submit','.noDoubleClickNoAjaxForm',function(){
		$(this).find('input[type="submit"]').prop('disabled', true);
	})

	$('input').attr('autocomplete', 'off');
	//table scroll
	var win_height = $(window).height();
	$('.table-responsive').css('maxHeight',win_height * 0.9);
	//----------
	$('body').on('click','.openClickTable>.panel-heading',function(){
		var icon = $(this).find('i');
		$(this).closest('.openClickTable').find('.panel-body').first().toggle('fast',function(){
			if (icon.hasClass('active')){
				icon.removeClass();
				icon.addClass('fa fa-chevron-down');
			}else{
				icon.removeClass();
				icon.addClass('fa fa-chevron-up active');
			}
		});
	})
	$("[data-toggle='tooltip']").tooltip();
	$('body').on('keypress','.float_mask',function( key ){
		if((key.charCode < 48 || key.charCode > 57) && (key.charCode != 44) && (key.charCode != 13)&& (key.charCode != 46)){
			message({callback: "warning", message_shot: "Предупреждение!", message: " Некорректный символ"})
			return false;
		}	
	});
	$('table').on('click','tr', function(){
		//var table = $(this).closest('table');
		//var tr = table.find('tr');
		//deleteClass(tr);
		//$(this).addClass('activeTableTd');
	})
	$.fn.editable.defaults.params = function (params) {
	    params._token = $("meta[name='_token']").attr("content");
	    return params;
	};
	$('.editable').editable({
		ajaxOptions: {
		    type: 'put',
		    dataType: 'json'
		},
		success: function(response, newValue) {
			ajaxReload();
   		}
	});
	var delete_tr;
	$('body').on('click','.deleteItem',function(event){
		event.preventDefault();
		event.stopPropagation();
		delete_tr = $(this).closest('tr');
		$('#DeleteAlert form').attr('action',$(this).attr('data-delete'));
		$('#DeleteAlert').modal('show');
	})
	$( "body" ).on('click','#delete',function(event) {
		event.preventDefault();
		event.stopPropagation();
		$('#DeleteAlert form').attr('action',$(this).attr('data-delete'));
		$('#DeleteAlert').modal('show');
	})
	$('body').on('submit','#DeleteAlert form',function(event){
		event.preventDefault();
		delete_tr.remove();
		url = $(this).attr('action');
		data = $(this).serialize();
		$.ajax({
		        type: "DELETE",
		        url: url,
		        data: data,
		        success: function(data)
		        {
		        	$('#DeleteAlert').modal('hide');
		        	console.log($("td[data-delete='"+url+"']").closest(".AjaxUpdateList").attr('data-update'))
		        	ajaxUpdateList($("td[data-delete='"+url+"']").closest(".AjaxUpdateList").attr('data-update'))
		        	ajaxReload();

		        }
	    });
	})

	$('body').on('click','.export-excel',function(event){
		var headerArray = [];
		var intervalArray = [];
		var bodyArray = [];
		var table = $('.excel-table');
		var th = table.find('thead th');
		var direction = false;
		th.each(function (){
			if ($(this).hasClass('head-start')){
				direction = true;
			}
			if (direction === true){
				headerArray.push($(this).html());
			}
			if ($(this).hasClass('head-finish')){
				direction = false;
			}
		});

		var tr = table.find('tbody tr');
		tr.each(function (index){
			var td = $(this).find('td');
			intervalArray = [];
			var direction = false;
			td.each(function (){
				if ($(this).hasClass('body-start')){
					direction = true;
				}
				if (direction === true){
					intervalArray.push($(this).html());
				}
				if ($(this).hasClass('body-finish')){
					direction = false;
				}
			});

			if (intervalArray.length > 0){
				bodyArray.push(intervalArray);
			}
		});
		var name = $(this).data('name');
		bodyArray.unshift(headerArray);

		$.ajax({
			type: "POST",
		  	url: "excelCreate",
		  	data: {_token: _token,body:bodyArray,name:name}
		}).done(function(data) {
			var url = '/storage/exports/'+data;
		});
	})

	//-----------------------------
	_token = $("meta[name='_token']").attr("content");
	$('body').on('click','#deleteDataTable',function(event){
		$.ajax({
			type: "POST",
		  	url: "repayment/ClearTable",
		  	data: {_token: _token}
		}).done(function(data) {
			message(data);
		});
	});
	//-----------------------------
});

function deleteClass(tr){
	tr.each(function(){
		if($(this).hasClass('activeTableTd')){
			$(this).removeClass('activeTableTd');
		}
	});
}
var int_generation = function(){
	now = new Date();
	integer=now.getFullYear()+''+now.getMonth()+''+now.getDate()+''+now.getHours()+''+now.getMinutes()+''+now.getSeconds()+''+now.getMilliseconds()
}
var message = function(data){
	id_close = 'close-'+int_generation()
	$('.message-box').append('<div class="alert alert-'+data["callback"]+' fade in"><a href="#" class="close" id="'+id_close+'" data-dismiss="alert" aria-label="close">&times;</a><strong>'+data["message_shot"]+'</strong> '+data["message"]+'</div>')
	if(data["callback"]=='danger'){
		setTimeout(function(){
			$('#'+id_close).trigger('click');
		}, 10000);
	}else{
		setTimeout(function(){
			$('#'+id_close).trigger('click');
		}, 2000);
	}
}

var sendMessage = function(callback,messageShot,messageVar){
	var messageArray = {'callback':callback,'message':messageVar,'message_shot':messageShot};
	message(messageArray);
}

var ajaxUpdateList = function(dataUpdate){
	url = dataUpdate;
	$.get( url, function( data ){
		$('.AjaxUpdateList[data-update="'+dataUpdate+'"]').empty().append(data);
		$('.selectpicker').selectpicker('refresh');
	});
}

function tableChecked(table){
	if (table === undefined) {
	    table = $('table');
	}
	table.on('click','td',function(){
		var trVar = $(this).parent('tr');
		var tdVar = trVar.find('td').first();
		
		var check = $(this).children('input:checkbox,input:radio').length;

		var checkVar = tdVar.children('input:checkbox,input:radio');
		
		var check_1 = $(this).children('input:checkbox,input:radio').length > 0;


		if($(checkVar).is("input:checkbox")){
			if ($(checkVar).prop('checked')){
				trVar.removeClass('ActiveTableTrConst');
				if(!check_1){
					$(checkVar).prop('checked',false); //если попали по чекбоксу или радио то не меняем их значение
				}
			}else{
				trVar.addClass('ActiveTableTrConst');
				if(!check_1){
					$(checkVar).prop('checked',true); //если попали по чекбоксу или радио то не меняем их значение
				}
			}
		} 
		else{ 
			if($(checkVar).is("input:radio")){
				if ($(checkVar).prop('checked')){
					if(!check_1){
						$(checkVar).prop('checked',false); //если попали по чекбоксу или радио то не меняем их значение
					}
				}else{
				if(!check_1){
					$(checkVar).prop('checked',true); //если попали по чекбоксу или радио то не меняем их значение
					}
				}
			}
			var table = $(this).closest('table');
			var tr = table.find('tr');
			deleteClass(tr);
			trVar.addClass('activeTableTd');
		}	
	
		

	})
}

function buttonLock(el,handler) {
	el.prop('disabled', handler);
}

