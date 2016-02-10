$(document).ready(function(){
	openFinanceClick();//Открыть поставки
	$('#financeFormSum').number(true,2,',',' ');
	financeChoiceClick();

	//Окно закрытия
	$('.popapClose').on('click',function(){
		$('#popup-table').html('');
		$('#popapModal').modal('hide');
	})
	//Окно подтверждения
	$('body').on('click','#financeSuccess',function(){
		var dataVar = [];
		$('.financeChoice:checked').each(function(i){
			dataVar.push($(this).data('id'));
		// 
		});
		_token = $("meta[name='_token']").attr("content");	
		$.ajax({
			type: "POST",
			headers: { 'X-CSRF-TOKEN': _token },
		  	url: "finance/getFinances",
		  	data: {data:dataVar,_token:_token}
		}).done(function(data) {
		  	if(data['callback']=='success'){
		  		$('#popup-table').append(data['view']);
		  		$('#popapModal').modal('show');
       		}
       		else{
       			message(data);			
			}
		});
	});
	//Подтвердить
	$('#financingSuccessBtn').on('click',function(){
		var financeArray = [];
		
		$('.financeChoicePopup:checked').each(function(i){
			financeArray.push($(this).data('id'));
		});

		if (financeArray.length > 0){
			var financingDate = $('#financingSuccessDate').val();
			_token = $("meta[name='_token']").attr("content");	
			$.ajax({
				type: "POST",
				headers: { 'X-CSRF-TOKEN': _token },
			  	url: "finance/financingSuccess",
			  	data: {financeArray: financeArray,financingDate: financingDate,_token:_token}
			}).done(function(data) {
				$('.popapClose').click();

				data.forEach(function(item) {
				  	if(item['callback']=='success'){
	           			message(item);
	           			if (item['type'] == true){
							createCommission(item['data']);//Начисление коммиссии
	           			}
	           		}
	           		else{
	           			message(item);			
					}
				});
				$('.filterUpdate').change();
			});
		}else{
			sendMessage('warning','Внимание!','Выберите финансирование');
		}
	});

	//filter
	$('.filterUpdate').on('change', function(){
		var filterArrayStatus = [];
		var filterArrayType = [];

		filterStatus = $('#financeFormStatus').val();
		filterArrayType = getCheckedCheckbox('.filterCheckboxType',filterArrayType);
		_token = $("meta[name='_token']").attr("content");	
		$.ajax({
			type: "POST",
		  	url: "finance/filter",
		  	data: {_token:_token, filterStatus: filterStatus,filterArrayType: filterArrayType}
		}).done(function(data){
			$('#finance-table').html(data);
			$('#financeFormSum').val($('#sum').val());
			openFinanceClick();
			financeChoiceClick();
			$('#financeFormSumHidden').val(0);
			$('#financeFormSum').val(0);
			$('#financeFormSum').number(true,2,',',' ');
		});
	});
});

function createCommission(finance){
	_token = $("meta[name='_token']").attr("content");	
	$.ajax({
		type: "POST",
		headers: { 'X-CSRF-TOKEN': _token },
	  	url: "chargeCommission",
	  	data: {finance: finance,_token:_token}
	}).done(function(data) {
		//console.log(data);
	});
}

function getCheckedCheckbox(filter,array){
	$(filter).each(function(){
		if ($(this).prop('checked')){
			array.push($(this).val());
		};
	});
	return array;
}

function openFinanceClick(){
	$('body').on('click','.deliveryOpenModal',function(){

		financeFormId = $(this).data('id');
		_token = $("meta[name='_token']").attr("content");	
		$.ajax({
			type: "POST",
			headers: { 'X-CSRF-TOKEN': _token },
		  	url: "finance/getDeliveries",
		  	data: {financeFormId: financeFormId,_token:_token}
		}).done(function(data){
			$('#popup-table-delivery').html(data);
			$('#popapDeliveryModal').modal('show');
		});

	})

	$('body').on('click','.popapDeliveryClose',function(){
		$('#popup-table-delivery').html('');
		$('#popapDeliveryModal').modal('hide');
	})
}

function financeChoiceClick(){
	$('.financeTrClick').on('click',function(){
		var financeFormSum = parseFloat($('#financeFormSumHidden').val());
		var chechboxVar = $(this).find('td').eq(0).find('input');
		var financeFormId = chechboxVar.data('id');
		var handler;
		
		_token = $("meta[name='_token']").attr("content");	
		$.ajax({
			type: "POST",
			headers: { 'X-CSRF-TOKEN': _token },
		  	url: "finance/getSum",
		  	data: {financeFormId: financeFormId,_token:_token}
		}).done(function(data) {
			var financeSum = parseFloat(data);
			if (chechboxVar.prop('checked')){
				handler = true;
			}else{
				handler = false;
			}
			if (handler){
				var sum = financeFormSum + financeSum;
				$('#financeFormSumHidden').val(sum); 
				$('#financeFormSum').val(sum);
			}else{
				var minus = financeFormSum - financeSum;
				$('#financeFormSumHidden').val(minus); 
				$('#financeFormSum').val(minus);
			}
			$('#financeFormSum').number(true,2,',',' ');	
		});

	});
}
