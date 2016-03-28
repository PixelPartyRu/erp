$(document).ready(function(){
	radioChange();
	tabs();
	repaymentPushCheck();
	selectPickerImportChange('[name = "clientPayerCreate"]','btn-danger');
	selectPickerImportChange('[name = "debtorPayerCreate"]','btn-default');

	$('body').on('change','#importFile',function(){
		buttonLock($('#importBtn'),false);
		buttonLock($('#sendImportRepayment'),true);
	})
	
	$('body').on('click','#importBtn',function(){
		var file = document.getElementById("importFile").files[0];
		if (file != undefined){
			buttonLock($(this),true);
			buttonLock($('#sendImportRepayment'),false);
			
			var _token = $('[name="_token"]').attr('content');
	   		var reader = new FileReader();
	   		reader.readAsText(file,'CP1251');

	   		reader.onload = function(event) {
			    var contents = event.target.result;
			    $.ajax({
					type: "POST",
				  	url: "repayment/getImportFile",
				  	data: {_token:_token, contents: contents}
				}).done(function(data) {
					$('#importModalTableBody').html(data);
					$('.selectpicker').selectpicker('refresh');
					$('[name = "clientPayerCreate"]').change();
					$('[name = "debtorPayerCreate"]').change();
				});
			};

			reader.onerror = function(event) {
			    console.error("Ошибка" + event.target.error.code);
			};
		}else{
			sendMessage('warning','Внимание!','Выберите файл');
		}
			
	})

	$('body').on('change','[name="clientPayerCreate"]',function(){
		var clientId = $(this).val();
		var _token = $('[name="_token"]').attr('content');
		$.ajax({
			type: "POST",
		  	url: "repayment/getDebtor",
		  	data: {_token:_token, clientId: clientId}
		}).done(function(data) {
			var items = '';
			$('[name = "debtorPayerCreate"]').empty();
			items += '<option value="0">Выберите дебитора</option>';
			$.each( data, function(key,value) {
		  		items+= ('<option value="'+value.id+'">'+value.name+'</option>');
		  		console.log(value);
			});
			$('[name = "debtorPayerCreate"]').append(items);
			$('[name = "debtorPayerCreate"]').selectpicker('refresh');
		});
	})

	$('body').on('click','#modal-btn-success',function(){
		buttonLock($(this),true);
		var sendArray = [];
		var number = $('#paymentNumber').val();
		var date = $('#paymentDate').val();
		var sum = $('#paymentSum').val();
		var purpose_of_payment = $('#paymentTextarea').val();
		var clientId = $('[name="clientPayerCreate"]').val();
		var debtorId = $('[name="debtorPayerCreate"]').val();
		if ($('[name="radioCreate"]').is(':checked')){
			var radioChoice = 1;
		}else{
			var radioChoice = 0; 
		}
		var typeOfPayment = $('[name="debtorPayerCreateRadio"]').val();
		sendArray = {
			'number':number,
			'date':date,
			'sum':sum,
			'purpose_of_payment':purpose_of_payment,
			'clientId':clientId,
			'radioChoice':radioChoice,
			'typeOfPayment':typeOfPayment,
			'debtorId':debtorId
		};

		_token = $("meta[name='_token']").attr("content");
		$.ajax({
			type: "POST",
			headers: { 'X-CSRF-TOKEN': _token },
		  	url: "repayment/createStore",
		  	data: {_token:_token,sendArray:sendArray}
		}).done(function(data){	
			if (data['error'] == false){
				updateIndex();
				$('#createModal').modal('hide');				
			}else{
				buttonLock($('#modal-btn-success'),false);
			}
			message(data['data']);

		});

	})

	$('body').on('click','#createOpenModal',function(){
		$('#createModal').modal('show');
		buttonLock($('#modal-btn-success'),false);
	})

	//Провести погашение 
	$('body').on('click','#repaymentModalBtn',function(){
		var idVar = $('.fieldTable:checked').val();
		if (idVar != undefined){
			var _token = $('[name="_token"]').attr('content');
			$.ajax({
				type: "POST",
				headers: { 'X-CSRF-TOKEN': _token },
			  	url: "repayment/getRepayment",
			  	data: {_token:_token,id: idVar}
			}).done(function(data){	
				if (data['error'] == false){
					$('#repaymentModalContent').html(data['data']);
					$('#repaymentTabs .active a').click();
					$('#repaymentModal').modal('show'); 
				}else{
					message(data['data']);
				}
			});	
		}else{
			sendMessage('warning','Внимание! ','Выберить погашение');
		}
	});

	$('body').on('change','#paymentDebtor',function(){
		if ($(this).is(':checked')){
			$('.debtorPayerCreate').attr('disabled',false);
		}else{
			$('.debtorPayerCreate').attr('disabled',true);
		}
		$('.debtorPayerCreate').selectpicker('refresh');
	})

	$('body').on('click','.repaymentDelete',function(){
		var id = $(this).data('id');
		_token = $("meta[name='_token']").attr("content");
		$.ajax({
			type: "POST",
			headers: { 'X-CSRF-TOKEN': _token },
		  	url: "repayment/deleteRepayment",
		  	data: {_token:_token,id:id}
		}).done(function(data){	
			if (data['callback'] == 'success'){
				$('#deleteModal #delete-modal-id').val(data['id']);
				$('#deleteModal').modal('show'); 
			}else{
				message(data);
			}
		});
	})

	$('body').on('click','#delete-modal-send',function(){
		var id = $('#delete-modal-id').val();
		_token = $("meta[name='_token']").attr("content");
		$.ajax({
			type: "POST",
			headers: { 'X-CSRF-TOKEN': _token },
		  	url: "repayment/deleteConfirm",
		  	data: {_token:_token,id:id}
		}).done(function(data){	
			message(data['data']);
			$('#deleteModal').modal('hide'); 
			$('#repayment-table-content').html(data['view']);
		});
	});
	
});

function selectPickerImportChange(selector,style){
	$('body').on('change',selector,function(){
		if ($(this).val() == 0){
			$(this).selectpicker('setStyle', 'btn-success','remove');
			$(this).selectpicker('setStyle', style,'add');
		}else{
			$(this).selectpicker('setStyle', style, 'remove');
			$(this).selectpicker('setStyle', 'btn-success','add');
		}
	});
}

function radioChange(){
	$('body').on('click','#sendImportRepayment',function(){
		$(this).prop('disabled', true);

		var sendArray = [];
		$('.importModalTableBodyTr').each(function(index,value){
			var tdVar = $(this).find('td');
			var number = tdVar.eq(0).html();
			var date = tdVar.eq(1).data('val');
			var sum = tdVar.eq(2).data('val');
			var info = tdVar.eq(3).html().slice(11);
			var inn = tdVar.eq(4).html();
			var purpose_of_payment = tdVar.eq(5).html();

			var agentClient = tdVar.eq(6).find('[name = "clientPayerCreate"]').val();
			var agentDebtor = tdVar.eq(7).find('[name = "debtorPayerCreate"]').val();
			sendArray.push({
				'number':number,
				'date':date,
				'sum':sum,
				'info':info,
				'inn':inn,
				'purpose_of_payment':purpose_of_payment,
				'clientId':agentClient,
				'debtorId':agentDebtor
			});
		});
		_token = $("meta[name='_token']").attr("content");
		$.ajax({
			type: "POST",
			headers: { 'X-CSRF-TOKEN': _token },
		  	url: "repayment",
		  	data: {_token:_token,sendArray:sendArray}
		}).done(function(data){	
			if (data['error'] == false){
				updateIndex();
				$('#importModal').modal('hide');				
			}else{
				$('#sendImportRepayment').prop('disabled', false);
			}
			data['data'].forEach(function(item) {
			  	message(item);
			});
		});
	});
}

function updateIndex(){
	_token = $("meta[name='_token']").attr("content");
	$.ajax({
		type: "POST",
		headers: { 'X-CSRF-TOKEN': _token },
	  	url: "repayment/getIndexRepayment",
	  	data: {_token:_token}
	}).done(function(data){	
		$('#repayment-table-content').html(data);
	});
}

function tabs(){
	$('body').on('click','#repaymentTabs a',function(e){
		e.preventDefault();
  		$(this).tab('show');
  		var dataVar = $(this).data('id');
  		var repaymentVar = $('#repaymentId').val();
		var _token = $('[name="_token"]').attr('content');
  		$.ajax({
			type: "POST",
		  	url: "repayment/getDelivery",
		  	data: {_token:_token,dataVar:dataVar, repaymentId: repaymentVar}
		}).done(function(data){	
			$('#tableRepaymentContent').html(data);
			handler = $('#tabsType').val();
			tableModalClick(handler);
			updateBalance(repaymentVar);
			//console.log(data);
		});
	});
}

function updateBalance(repaymentVar){
	var _token = $('[name="_token"]').attr('content');
	$.ajax({
		type: "POST",
	  	url: "repayment/updateBalance",
	  	data: {_token:_token,repaymentId: repaymentVar}
	}).done(function(data){	
		$('#repaymentBalanceHidden').val(data);
		$('#repaymentModalBalance').val(data);
		$('#repaymentModalBalance').number(true,2,',',' ');
	});
}

function tableModalClick(type){
	tableVar = $('#tableModal');
	tableTrVar = $('#tableModal tr');
	tableChecked(tableVar);//layaut.js

	tableTrVar.on('click',function(){//balance
		$('#preloader').show();
		var inputVar = $(this).find('td').eq(0).find('input');
		var inputVarVal = inputVar.val();
		var _token = $('[name="_token"]').attr('content');
		var balance = 0;
		var repaymentSum = $(this).find('.repaymentSum');
		var repaymentSumHidden = $(this).find('.repaymentSumHidden');

		if (handler === 'delivery'){
			var url = "repayment/getDeliveryFirstPayment";
			var dataAjax = {_token:_token, dataId:inputVarVal};
		}else{
			if (handler === 'commission'){
				var url = "repayment/getCommissionData";
				var dataType = inputVar.data('type');
				var dataAjax = {_token:_token, dataId:inputVarVal,type:dataType};
			}else{
				var url = "repayment/getWaybillAmount";
				var dataAjax = {_token:_token, dataId:inputVarVal};
			}
		}

  		$.ajax({
			type: "POST",
		  	url: url,
		  	data: dataAjax,
		}).done(function(data){
			var dataVar = parseFloat(data).toFixed(2) * 1.00;
			balance = parseFloat($('#repaymentBalanceHidden').val()).toFixed(2) * 1.00;
			if (inputVar.prop('checked')){
				if (balance >= dataVar){
					balance -= dataVar;
					repaymentSum.html($.number(dataVar,2,',',' '));
					repaymentSumHidden.val(dataVar);
				}else{
					repaymentSum.html($.number(balance,2,',',' '));
					repaymentSumHidden.val(balance);
					balance = 0.00;
				}
			}else{
				balance = (parseFloat(balance) * 1.00) + (parseFloat(repaymentSumHidden.val()) * 1.00);
				repaymentSum.html($.number(0,2,',',' '));
				repaymentSumHidden.val(0);
			}	

			$('#repaymentBalanceHidden').val(balance);
			$('#repaymentModalBalance').val(balance);
			$('#repaymentModalBalance').number(true,2,',',' ');
			$('#preloader').hide();
		});
	});
}	

function repaymentPushCheck(){
	$('body').on('click','#repaymentModalPush',function(){
		buttonLock($(this),true);
		var deliveryIdArray = [];
		var repaymentId = $('#repaymentId').val();
		var handler = $('#tabsType').val();
		$('[name = deliveryChoice]:checked').each(function(){
			var id = $(this).val();
			var tr = $(this).closest('tr');
			var sum = tr.find('.repaymentSumHidden').val();
			if (sum > 0){
				if (handler === 'commission'){
					var type = $(this).data('type');
					deliveryIdArray.push({'delivery':id,'sum':sum,'type':type});
				}else{
					deliveryIdArray.push({'delivery':id,'sum':sum});
				}
			}
		});
		if (deliveryIdArray.length > 0){	
			repaymentPush(deliveryIdArray,repaymentId,handler);
		}else{
			sendMessage('warning','Внимание! ','Выберите поставку');
			buttonLock($(this),false);
		}
	});
}

function repaymentPush(delivery,repaymentId,handler){
	var _token = $('[name="_token"]').attr('content');
	$.ajax({
		type: "POST",
	  	url: "repayment/repayment",
	  	data: {_token:_token, delivery:delivery,repaymentId:repaymentId, handler:handler},
	}).done(function(data){	
		data['callback'].forEach(function(item) {
       		message(item);			
		});
		$('#repaymentModal').modal('hide');
		buttonLock($('#repaymentModalPush'),false);
		$('#repayment-table-content').html(data['view']);
	});
}

