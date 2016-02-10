$(function () {
    $('body').on('click','#filterUpdate',function () {
        var DebtorId = $('#filterDebtorInn').val();
        var ClientId = $('#filterClientInn').val();
        var Status = $('#filterStatus').val();
        var _token = $('[name="_token"]').attr('content');
        $.ajax({
            type: "POST",
            url: "chargeCommission/getFilterData",
            data: {_token: _token, DebtorId: DebtorId, ClientId: ClientId, Status: Status}
        }).done(function (data) {
            if (data['callback'] == 'success'){
                $('#client-table tbody').html(data['view']);
            }else{
                message(data);
            }
            //console.log(data);
        });

    });
});