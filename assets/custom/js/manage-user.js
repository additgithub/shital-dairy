$(document).ready(function () {

    $(document).on("click", ".user_details", function (event) {

        var $ts = $(this);
        var data_id = $ts.attr('data-id');

        $.ajax({
            type: 'POST',
            url: 'user/get_details',
//                async: false,
            data: {UserID: data_id},
            dataType: 'html',
            beforeSend: function () {
                $('#user_details .model_content_area').html('');
                // $('#product_details .model_content_area').html('');
            },
            success: function (returnData) {

                $("#user_details").modal('show');
                $('#user_details .model_content_area').html(returnData);
                init_property_detail_datatable();
            },
            error: function (xhr, textStatus, errorThrown) {
            },
            complete: function () {

            }
        });

        return false;

    });
    $(document).on("click", ".user_rights_details", function (event) {

        var $ts = $(this);
        var data_id = $ts.attr('data-id');

        $.ajax({
            type: 'POST',
            url: 'user/get_user_rights_details',
//                async: false,
            data: {UserID: data_id},
            dataType: 'html',
            beforeSend: function () {
                $('#user_rights_details .model_content_area').html('');
                // $('#product_details .model_content_area').html('');
            },
            success: function (returnData) {

                $("#user_rights_details").modal('show');
                $('#user_rights_details .model_content_area').html(returnData);
                init_property_detail_datatable();
            },
            error: function (xhr, textStatus, errorThrown) {
            },
            complete: function () {

            }
        });

        return false;

    });
    $(document).on("click", ".user_righst_detail_cancel_button", function (event) {
        $("#user_rights_details").modal('hide');
//        setTimeout(function () {
//            get_client_commission_details($('.ClientID').val());
////            get_feedback_details($('.FeedbackID').val());
//        }, 500);
    });
    
});


