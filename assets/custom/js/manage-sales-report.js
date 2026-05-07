$(document).ready(function () {
    $(document).on("click", "a.btn_report_download", function (event) {
        event.preventDefault();
        var url = $(this).attr('data-url');

        var customer_id = $(this).attr('data-id');
        var month   = $("#month").val();

        var $url = url + "?customer_id=" + customer_id + "&month=" + month;
        
        $.ajax({
            type: 'GET',
            url: $url,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                hideShowLoader();
            },
            success: function (returnData) {
                hideShowLoader('hide');
                window.open(returnData.data, "_blank");
            },
            error: function (xhr, textStatus, errorThrown) {
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
            }
        });

        return false;
    });

    $(document).on("click", "a.send_whatsapp", function (event) {
        event.preventDefault();
        var url = $(this).attr('data-url');

        var customer_id = $(this).attr('data-id');
        var month   = $("#month").val();

        var $url = url + "?customer_id=" + customer_id + "&month=" + month+"&is_whatsapp=1";
        
        $.ajax({
            type: 'GET',
            url: $url,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                hideShowLoader();
            },
            success: function (returnData) {
                if (returnData.status === 'ok') {
                    showSuccess(returnData.message);
                } else {
                    showErrorMessage(returnData.message);
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
                hideShowLoader('hide');
            }
        });

        return false;
    });
});