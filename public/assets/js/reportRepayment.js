$('document').ready(function(){
	$('body').on('change','#filter-choice',function(){
		var value = $(this).val();
		var handler;
		if (value == 0){
			handler = true;
		}else{
			handler = false;
		}

		$('.filter-date-select').prop('disabled',handler);
	})

	$('body').on('click','#filter-send',function(){
		var data = $('#filter-form').serialize();

        $.ajax({
           	type: "GET",
           	url: 'reportRepayment',
           	data: data,
           	success: function(data){
		   		$('#report-repayment-table').html(data);
		   	}
		});
	});
});