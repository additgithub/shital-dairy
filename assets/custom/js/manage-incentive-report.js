$(document).ready(function () {
    $(document).on("click", ".view_status_history", function (event) {
        var data_id = $(this).attr('data-id');
        var controller = $(this).attr('data-control');
        var $url = 'view_status_history';
        if (data_id > 0) {
            $url = 'view_status_history/' + data_id;
        }
        $.ajax({
            type: 'POST',
            url: controller + '/' + $url,
            async: false,
            data: { pstdata: 1 },
            dataType: 'html',
            beforeSend: function () {
                $('#invoice_history .model_content_area').html('');
            },
            success: function (returnData) {
                $("#invoice_history").modal('show');
                $('#invoice_history .model_content_area').html(returnData);
            },
            error: function (xhr, textStatus, errorThrown) {
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
            }
        });

        return false;
    });

    $(document).on("click", ".invoice_history_cancel_button", function (event) {
        $("#invoice_history").modal('hide');
    });
});