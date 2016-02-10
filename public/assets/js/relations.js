  $(function() {
  	if($('.alert').size()>0){
  		$('.panel-heading:eq(0)').trigger('click');
  	}
$( 'body' ).on('change','select[name="client_id"]',function() {
    var id = $( this ).val();
	var url = '/client/'+id;
	var icon = $('#relationsCD .panel-heading i');
	if (id==0) {
		var items = '<option value="0">Договоров нет</option>';
		$('#agreement_id').prop( "disabled", true );
		$('#agreement_id').empty();
		$('#agreement_id').append(items);
		$('#agreement_id').selectpicker('refresh');
	}else{
		$.getJSON(url, function(data){
		 	//var items = '<select id="agreement" name="agreement_id" id="agreement_selected" class="selectpicker"><option value="0">Выбрать договор</option>';
		  	var items = '';
			if (data['agreements'].length>0) {
				items += '<option value="0">Выберите договор</option>';
		  		$.each( data['agreements'], function(key,value ) {
		  		items+= ('<option value="'+value.id+'">'+value.code+'</option>')
				});
				//items+='</select>'
				$('#agreement_id').prop( "disabled", false );
		  	}else{
		  		//items='<span id="agreement">у клиента нет договоров</span>';
				items += '<option value="0">Договоров нет</option>';
				$('#agreement_id').prop( "disabled", true );
		  	};
		  	$('#regress').remove();
  			$('#date_of_gen_dogovor').remove();
		  	$('#agreement').remove();
			$('#agreement_id').empty();
			$('#agreement_id').append(items);
			$('#agreement_id').selectpicker('refresh');
			//$('#relation_selectors').show();
		});
	}

  })
  /*
  .on('change','select[name="agreement_id"]',function() {
  	var id = $( this ).val();
	var url = '/agreement/'+id;
  		$.getJSON(url, function(data){
  			//console.log(data['agreement'])
  			var regress = ''
  			if(data['agreement'].type == true){
  				regress = 'с регрессом'
  			}else {
  				regress = 'без регресса'
  			}
  			$('#regress').remove();
  			$('#date_of_gen_dogovor').remove();
		 	var items = '<span id="regress"> '+regress+' </span><span id="date_of_gen_dogovor"> '+data['agreement'].created_at.split(" ")[0]+' </span>';
		  	$('#relation_selectors').append(items);
		});
  })*/
 }); 
$(function() {

$("#original_documents_select").change(function() {
  var sel = $(this).find("option:selected").val();
  if(sel != 2){
  $("#original_documents_value").hide();
  }else{
  $("#original_documents_value").show();
  }
});

$('#filterUpdate').on('click',function(){
	var DebtorInn = $('#filterDebtorInn').val();
	var ClientInn = $('#filterClientInn').val();
	var Active = $('#filterActive').val();
	var NoActive = $('#filterNoActive').prop('checked');
	var _token = $('[name="_token"]').attr('content');
	$.ajax({
			type: "POST",
		  	url: "relation/getFilterData",
		  	data: {_token:_token, DebtorInn: DebtorInn,ClientInn: ClientInn, Active:Active, NoActive:NoActive}
		}).done(function(data) {
			$('#client-table').html(data);
			//console.log(data);
		});

});

  
});

