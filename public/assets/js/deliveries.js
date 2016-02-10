$(document).ready(function(){
	popapVerificationClick();//получить description связи по поставке
	$('body').on('click','#popupOpen',function(){
		var dataVar=[];
		var checkedVar = $('.verification:checked');
		if (checkedVar.length > 0){
			checkedVar.each(function(){
				dataVar.push($(this).data('id'));
			});
			var _token = $('input[name=_token]').val();
			$.ajax({
				type: "POST",
			  	url: "delivery/getPopapDelivery",
			  	data: {_token: _token,data:dataVar}
			}).done(function(data) {
				$('#popup-table').append(data);
				$('#verificationModal').modal('show');
			});
			

		}else{
			sendMessage('warning','Внимание!','Выберите поставку');
		}
	});//open

	$('body').on('click','.verificationModalClose',function(){
		$('#popup-table').html('');
		$('#verificationModal').modal('hide'); 
	});//close

	$('body').on('click','#verificationBtn',function(){
		verification('verification');
		$('.verificationModalClose').click();
	});//verification

	$('body').on('click','#notVerificationBtn',function(){
		verification('notVerification');
		$('.verificationModalClose').click();
	});//not verefication

	$('body').on('click','#financeBtn',function(){
		var financeArray = [];
		verification('finance');
		financeArray = getCheckedArray(); 
		pushToFinance(financeArray);
		$('.verificationModalClose').click();
		
	});//finance

	//Filter
	$('#filterDateRadioRegistry').change(function(){
		if ($(this).val() != 0){
			$('.deliveryRadioDate').prop('disabled', false);
		}else{
			$('.deliveryRadioDate').prop('disabled', true);
		}
	});

	$('#filterUpdate').on('click',function(){

		var deliveryFilterStatusArray = ['Зарегистрирована','Не верифицирована','Верифицирована','К финансированию','Отклонена','Профинансирована'];
		var varArray = getCheckedFilterArray($('.deliveryFilterStatus'));
		if (varArray.length != 0){
			deliveryFilterStatusArray = varArray;
		}

		if ($('#filterState').val() != '0'){
			var deliveryFilterState = [$('#filterState').val()];
		}else{
			var deliveryFilterState = [true,false];
		}

		if ($('#filterRegistry').val() != '0'){
			var deliveryFilterRegitry = [$('#filterRegistry').val()];
		}else{
			var deliveryFilterRegitry = getData($('#filterRegistry option'));
		}

		if ($('#filterClient').val() != '0'){
			var deliveryFilterClient = [$('#filterClient').val()];
		}else{
			var deliveryFilterClient = getData($('#filterClient option'));
		}

		if ($('#filterDebtor').val() != '0'){
			var deliveryFilterDebtor = [$('#filterDebtor').val()];
		}else{
			var deliveryFilterDebtor = getData($('#filterDebtor option'));
		}

		var deliveryFilterDateChoice;
		var deliveryFilterDateStart;
		var deliveryFilterDateFinish;
		var deliveryFilterDateHandler = false;
		if ($('#filterDateRadioRegistry').val() != 0){
			deliveryFilterDateHandler = true;
			deliveryFilterDateChoice = $('#filterDateRadioRegistry').val();

			deliveryFilterDateStart = $('#filterDateRadioDateStart').val();
			deliveryFilterDateFinish = $('#filterDateRadioDateFinish').val();
		}
		if ((varArray == 0) && ($('#filterState').val() == 0) && 
			($('#filterRegistry').val() == 0) && ($('#filterClient').val() == 0) 
			&& ($('#filterDebtor').val() == 0) && ($('#filterDateRadioRegistry').val() == 0)){
			sendMessage('warning','Внимание!','Выберите критерий поиска');
		}else{
			var csrf_token = $('[name="_token"]').attr('content');
			var _token = $('input[name=_token]').val();
			$.ajax({
				type: "GET",
			  	url: "delivery/getFilterData",
			  	data: {deliveryFilterStatusArray: deliveryFilterStatusArray,deliveryFilterState: deliveryFilterState, deliveryFilterRegitry: deliveryFilterRegitry, deliveryFilterClient: deliveryFilterClient, deliveryFilterDebtor: deliveryFilterDebtor,deliveryFilterDateChoice: deliveryFilterDateChoice,deliveryFilterDateHandler: deliveryFilterDateHandler,deliveryFilterDateStart: deliveryFilterDateStart,deliveryFilterDateFinish:deliveryFilterDateFinish}
			}).done(function(data) {
				$('#deliveryTableTemplate').html(data);
				//console.log(data);
			});
		}
	});
	$('body').on('change','.deliveryFilter',function(){

		var deliveryFilterStatusArray = ['Зарегистрирована','Не верифицирована','Верифицирована','К финансированию','Отклонена','Профинансирована'];
		var varArray = getCheckedFilterArray($('.deliveryFilterStatus'));
		if (varArray.length != 0){
			deliveryFilterStatusArray = varArray;
		}

		if ($('#filterState').val() != '0'){
			var deliveryFilterState = [$('#filterState').val()];
		}else{
			var deliveryFilterState = [true,false];
		}

		if ($('#filterRegistry').val() != '0'){
			var deliveryFilterRegitry = [$('#filterRegistry').val()];
		}else{
			var deliveryFilterRegitry = getData($('#filterRegistry option'));
		}

		if ($('#filterClient').val() != '0'){
			var deliveryFilterClient = [$('#filterClient').val()];
		}else{
			var deliveryFilterClient = getData($('#filterClient option'));
		}

		if ($('#filterDebtor').val() != '0'){
			var deliveryFilterDebtor = [$('#filterDebtor').val()];
		}else{
			var deliveryFilterDebtor = getData($('#filterDebtor option'));
		}

		var deliveryFilterDateChoice;
		var deliveryFilterDateStart;
		var deliveryFilterDateFinish;
		var deliveryFilterDateHandler = false;
		if ($('#filterDateRadioRegistry').val() != 0){
			deliveryFilterDateHandler = true;
			deliveryFilterDateChoice = $('#filterDateRadioRegistry').val();

			deliveryFilterDateStart = $('#filterDateRadioDateStart').val();
			deliveryFilterDateFinish = $('#filterDateRadioDateFinish').val();
		}

		if ((varArray == 0) && ($('#filterState').val() == 0) && 
			($('#filterRegistry').val() == 0) && ($('#filterClient').val() == 0) 
			&& ($('#filterDebtor').val() == 0) && ($('#filterDateRadioRegistry').val() == 0)){
			$('#search_count').html('<strong>Найдено поставок: 0');
		}else{
			var csrf_token = $('[name="_token"]').attr('content');
			var _token = $('input[name=_token]').val();
			$.ajax({
				type: "GET",
			  	url: "delivery/getFilterData",
			  	data: {count:true, deliveryFilterStatusArray: deliveryFilterStatusArray,deliveryFilterState: deliveryFilterState, deliveryFilterRegitry: deliveryFilterRegitry, deliveryFilterClient: deliveryFilterClient, deliveryFilterDebtor: deliveryFilterDebtor,deliveryFilterDateChoice: deliveryFilterDateChoice,deliveryFilterDateHandler: deliveryFilterDateHandler,deliveryFilterDateStart: deliveryFilterDateStart,deliveryFilterDateFinish:deliveryFilterDateFinish}
			}).done(function(count) {
				$('#search_count').html('<strong>Найдено поставок: '+count+'</strong>');
				//console.log(data);
			});
		}
	});
	
	
	$("#checkAll_checkbox").click( function() {
            if(this.checked){
                $('.verification').prop("checked","checked");
            } else {
                $('.verification').removeAttr("checked");
				}
       });
	 $("#verificationPopup_all").click( function() {
            if(this.checked){
                $('.verificationPopup').prop("checked","checked");
            } else {
                $('.verificationPopup').removeAttr("checked");
				}
       }); 

       //ModalConfirmDelete
       $('#modalConfirm').on('click',function(){
       		var verificationArray = getVerificationClassArray();	
       		if (verificationArray.length > 0){
       			$('#importModalDelete').modal('show');   
       		}else{
       			sendMessage('warning','Внимание!','Выберите поставку для удаления');
       		}
       }) 
       //ModalDelete
       $('#modalDelete').on('click',function(){
       		var deleteArray = getVerificationClassArray();
       		_token = $("meta[name='_token']").attr("content");	
       		$.ajax({
				type: "POST",
				headers: { 'X-CSRF-TOKEN': _token },
			  	url: "delivery/deliveryDelete",
			  	data: {deleteArray: deleteArray,_token: _token}
			}).done(function(data) {
				$('#modalDeleteClose').click();

				data.forEach(function(item) {
           			message(item);			
				});
				$('#filterUpdate').click();
			});
       })

       //ConfirmOk
       $('#confirm').on('click',function(){
       		location.reload();
       })    
});

function popapVerificationClick(){
	$('body').on('click','#popup-table>tr',function(){
		var inputVar = $(this).find('td input');
		var idInput = inputVar.data('id');
		$.ajax({
			type: "GET",
		  	url: "delivery/getDescription",
		  	data: {idInput: idInput}
		}).done(function(data) {
			$('#verificationComents').val(data);
		});
	});
}

function getCheckedFilterArray(filter){
	var arrayVar = [];
	filter.each(function(){
		if ($(this).prop('checked')){
			arrayVar.push($(this).val());
		}
	});

	return arrayVar;
}

function getVerificationClassArray(){
	var verificationArray = [];
	$('.verification:checked').each(function(){
		verificationArray.push($(this).data('id'));
	});

	return verificationArray;
}

function getCheckedArray(){
	var verificationArray = [];
	$('.verificationPopup').each(function(i){
		if ($(this).prop('checked')){
			verificationArray.push($(this).data('id'));
		};
	});

	return verificationArray;
}

function verification(handler){
	var verificationArray = [];
	verificationArray = getCheckedArray();
	if (verificationArray.length != 0){
		$.ajax({
			type: "GET",
		  	url: "delivery/verification",
		  	data: {handler: handler, verificationArray: verificationArray}
		}).done(function(data) {
			data.forEach(function(item) {
           		message(item);			
			});
			$('#filterUpdate').click();
		});
	}
}

function pushToFinance(arrayFinance){
	if (arrayFinance.length != 0){
		_token = $("meta[name='_token']").attr("content");	
		$.ajax({
			type: "POST",
			headers: { 'X-CSRF-TOKEN': _token },
		  	url: "finance",
		  	data: {arrayFinance: arrayFinance,_token:_token}
		}).done(function(data) {
			//console.log(data);
			//location.reload();
			$('#filterUpdate').click();
		});
	}
}

function checkAll(obj) {
  'use strict';
  // Получаем NodeList дочерних элементов input формы: 
  var items = obj.table.getElementsByTagName("input"), 
      len, i;
  // Здесь, увы цикл по элементам формы:
  for (i = 0, len = items.length; i < len; i += 1) {
    // Если текущий элемент является чекбоксом...
    if (items.item(i).type && items.item(i).type === "checkbox") {
      // Дальше логика простая: если checkbox "Выбрать всё" - отмечен            
      if (obj.checked) {
        // Отмечаем все чекбоксы...
        items.item(i).checked = true;
      } else {
        // Иначе снимаем отметки со всех чекбоксов:
        items.item(i).checked = false;
      }       
    }
  }
}
function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}
function getData(handler){
	var arrayVar = [];
	handler.each(function(){
		if ($(this).val() != '0'){
			arrayVar.push($(this).val());
		}
	});

	return arrayVar;
}