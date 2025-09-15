$(document).ready(function () {

    $(document).on("click", ".client_detail_cancel_button", function (event) {
        $("#add_client_details").modal('hide');
        setTimeout(function () {
            get_client_commission_details($('.ClientID').val());
//            get_feedback_details($('.FeedbackID').val());
        }, 500);
    });

    $(document).on("click", ".open_my_client_commission_form", function (event) {
        var data_id = $(this).attr('data-id');
//        alert(data_id);
        var controller = $(this).attr('data-control');
        var ClientID = $('.ClientID').val();
        var $url = 'add_client_commissions';
        if (data_id > 0) {
            $url = 'edit_client_commissions/' + data_id;
        }
//        alert($url);
        $.ajax({
            type: 'POST',
            url: controller + '/' + $url,
            async: false,
            data: {pstdata: 1, ClientID: ClientID},
            dataType: 'html',
            beforeSend: function () {
//                $('#display_update_form').html('<div class="loader_wrapper"><div class="loader"></div></div>');
//                $('#add_edit_form').show();
//                $("#product_details .close").click()
            },
            success: function (returnData) {
                $('#client_details').modal('hide')
                setTimeout(function () {
                    $("#add_client_details").modal('show');
                    $('#add_client_details .model_content_area').html(returnData);
                }, 500);
                $('#display_update_form select').select2();
                $('.load_hide').hide();
            },
            error: function (xhr, textStatus, errorThrown) {
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
            }
        });

        return false;
    });

    $(document).on("click", ".client_details", function (event) {

        var $ts = $(this);
        var data_id = $ts.attr('data-id');

        $.ajax({
            type: 'POST',
            url: 'client/get_client_commission_details',
//                async: false,
            data: {ClientID: data_id},
            dataType: 'html',
            beforeSend: function () {
                $('#client_details .model_content_area').html('');
                // $('#product_details .model_content_area').html('');
            },
            success: function (returnData) {

                $("#client_details").modal('show');
                $('#client_details .model_content_area').html(returnData);
                init_client_detail_datatable();
            },
            error: function (xhr, textStatus, errorThrown) {
            },
            complete: function () {

            }
        });

        return false;

    });
    
});


function init_client_detail_datatable() {
    if ($('.client_detail_datatable').length > 0) {
        $('.client_detail_datatable').dataTable().fnDestroy();
        var add_button = 0;
        var add_button_title = 'Add New';
        if (typeof $('.client_detail_datatable').attr('data-add-button') != "undefined") {
            add_button = 1;
        }
        var $url = $('.client_detail_datatable').attr('data-control') + '/' + $('.client_detail_datatable').attr('data-mathod');

        var oTable_sub = $('.client_detail_datatable').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "sAjaxSource": $url,
            "sDom": "<'row'<'col-md-6'l <'toolbar'>><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
            "bLengthChange": false,
            "bAutoWidth": false,
//            "bDestroy": true,
            "fnServerParams": function (aoData, fnCallback) {
//                if ($('#BoardID').length > 0) {
//                    aoData.push(  {"name": "BoardID", "value":  $('#BoardID').val() } );
//                }
                if ($('#ClientID').length > 0) {
                    aoData.push({"name": "ClientID", "value": $('#ClientID').val()});
                }
            },
            "fnInitComplete": function () {
                $('.tooltip-top a').tooltip();
                $('select').select2({
                    minimumResultsForSearch: -1
                });
                if (add_button == 1) {
                    var $controller = $('.client_detail_datatable').attr('data-control');
//                    $(".client_detail_datatable .dataTables_wrapper .toolbar").html('<div class="table-tools-actions"><a class="btn btn-primary open_my_product_detail_form" href="javascript:;" data-control="' + $controller + '">' + add_button_title + ' <i class="fa fa-plus"></i></a></div>');
                }
                $('.search_mq').on('change', function () {
                    oTable_sub.fnDraw();
                });
            },
            "oLanguage": {"sLengthMenu": "_MENU_ ", "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"},
        });

    }
}

function get_client_commission_details(ClientID) {
    if (ClientID > 0) {
        $.ajax({
            type: 'POST',
            url: 'client/get_client_commission_details',
//                async: false,
            data: {ClientID: ClientID},
            dataType: 'html',
            beforeSend: function () {

            },
            success: function (returnData) {

                $("#client_details").modal('show');
                $('#client_details .model_content_area').html(returnData);
                init_client_detail_datatable();
            },
            error: function (xhr, textStatus, errorThrown) {
            },
            complete: function () {

            }
        });

        return false;
    }
}