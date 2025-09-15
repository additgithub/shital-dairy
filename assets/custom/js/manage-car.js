$(document).ready(function () {

    $(document).on("click", ".car_detail_cancel_button", function (event) {
        $("#add_car_details").modal('hide');
        setTimeout(function () {
            get_car_image_details($('.CarID').val());
            get_feedback_details($('.FeedbackID').val());
        }, 500);
    });

    function get_feedback_details(FeedbackID) {
        if (FeedbackID > 0) {
            $.ajax({
                type: 'POST',
                url: 'feedback/get_feedback_details',
//                async: false,
                data: {FeedbackID: FeedbackID},
                dataType: 'html',
                beforeSend: function () {

                },
                success: function (returnData) {

                    $("#car_details").modal('show');
                    $('#car_details .model_content_area').html(returnData);
                    init_car_detail_datatable();
                },
                error: function (xhr, textStatus, errorThrown) {
                },
                complete: function () {

                }
            });

            return false;
        }
    }

    $(document).on("click", ".feedback_details", function (event) {

        var $ts = $(this);
        var data_id = $ts.attr('data-id');

        $.ajax({
            type: 'POST',
            url: 'feedback/get_feedback_details',
//                async: false,
            data: {FeedbackID: data_id},
            dataType: 'html',
            beforeSend: function () {
                $('#car_details .model_content_area').html('');
                // $('#product_details .model_content_area').html('');
            },
            success: function (returnData) {

                $("#car_details").modal('show');
                $('#car_details .model_content_area').html(returnData);
                //init_car_detail_datatable();
            },
            error: function (xhr, textStatus, errorThrown) {
            },
            complete: function () {

            }
        });

        return false;

    });

    function get_car_loan_details(CarLoanID) {
        if (CarLoanID > 0) {
            $.ajax({
                type: 'POST',
                url: 'car_loan_request/get_car_loan_details',
//                async: false,
                data: {CarLoanID: CarLoanID},
                dataType: 'html',
                beforeSend: function () {

                },
                success: function (returnData) {

                    $("#car_details").modal('show');
                    $('#car_details .model_content_area').html(returnData);
                    init_car_detail_datatable();
                },
                error: function (xhr, textStatus, errorThrown) {
                },
                complete: function () {

                }
            });

            return false;
        }
    }

    $(document).on("click", ".car_loan_details", function (event) {

        var $ts = $(this);
        var data_id = $ts.attr('data-id');

        $.ajax({
            type: 'POST',
            url: 'car_loan_request/get_car_loan_details',
//                async: false,
            data: {CarLoanID: data_id},
            dataType: 'html',
            beforeSend: function () {
                $('#car_details .model_content_area').html('');
                // $('#product_details .model_content_area').html('');
            },
            success: function (returnData) {

                $("#car_details").modal('show');
                $('#car_details .model_content_area').html(returnData);
                //init_car_detail_datatable();
            },
            error: function (xhr, textStatus, errorThrown) {
            },
            complete: function () {

            }
        });

        return false;

    });


    $(document).on("click", ".open_my_car_image_form", function (event) {
        var data_id = $(this).attr('data-id');
        var controller = $(this).attr('data-control');
        var CarID = $('.CarID').val();
        var $url = 'add_car_images';
        if (data_id > 0) {
            $url = 'edit_car_images/' + data_id;
        }
        $.ajax({
            type: 'POST',
            url: controller + '/' + $url,
            async: false,
            data: {pstdata: 1, CarID: CarID},
            dataType: 'html',
            beforeSend: function () {
//                $('#display_update_form').html('<div class="loader_wrapper"><div class="loader"></div></div>');
//                $('#add_edit_form').show();
//                $("#product_details .close").click()
            },
            success: function (returnData) {
                $('#car_details').modal('hide')
                setTimeout(function () {
                    $("#add_car_details").modal('show');
                    $('#add_car_details .model_content_area').html(returnData);
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

    $(document).on("click", ".car_details", function (event) {

        var $ts = $(this);
        var data_id = $ts.attr('data-id');

        $.ajax({
            type: 'POST',
            url: 'car/get-car-image-details',
//                async: false,
            data: {CarID: data_id},
            dataType: 'html',
            beforeSend: function () {
                $('#car_details .model_content_area').html('');
                // $('#product_details .model_content_area').html('');
            },
            success: function (returnData) {

                $("#car_details").modal('show');
                $('#car_details .model_content_area').html(returnData);
                init_car_detail_datatable();
            },
            error: function (xhr, textStatus, errorThrown) {
            },
            complete: function () {

            }
        });

        return false;

    });
    $(document).on("click", ".dealer_car_details", function (event) {

        var $ts = $(this);
        var data_id = $ts.attr('data-id');

        $.ajax({
            type: 'POST',
            url: 'dealer-car-list/get-car-image-details',
//                async: false,
            data: {CarID: data_id},
            dataType: 'html',
            beforeSend: function () {
                $('#car_details .model_content_area').html('');
                // $('#product_details .model_content_area').html('');
            },
            success: function (returnData) {

                $("#car_details").modal('show');
                $('#car_details .model_content_area').html(returnData);
                init_car_detail_datatable();
            },
            error: function (xhr, textStatus, errorThrown) {
            },
            complete: function () {

            }
        });

        return false;

    });
});


function init_car_detail_datatable() {
    if ($('.car_detail_datatable').length > 0) {
        $('.car_detail_datatable').dataTable().fnDestroy();
        var add_button = 0;
        var add_button_title = 'Add New';
        if (typeof $('.car_detail_datatable').attr('data-add-button') != "undefined") {
            add_button = 1;
        }
        var $url = $('.car_detail_datatable').attr('data-control') + '/' + $('.car_detail_datatable').attr('data-mathod');

        var oTable_sub = $('.car_detail_datatable').dataTable({
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
                if ($('#CarID').length > 0) {
                    aoData.push({"name": "CarID", "value": $('#CarID').val()});
                }
            },
            "fnInitComplete": function () {
                $('.tooltip-top a').tooltip();
                $('select').select2({
                    minimumResultsForSearch: -1
                });
                if (add_button == 1) {
                    var $controller = $('.car_detail_datatable').attr('data-control');
//                    $(".car_detail_datatable .dataTables_wrapper .toolbar").html('<div class="table-tools-actions"><a class="btn btn-primary open_my_product_detail_form" href="javascript:;" data-control="' + $controller + '">' + add_button_title + ' <i class="fa fa-plus"></i></a></div>');
                }
                $('.search_mq').on('change', function () {
                    oTable_sub.fnDraw();
                });
            },
            "oLanguage": {"sLengthMenu": "_MENU_ ", "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"},
        });

    }
}

function get_car_image_details(CarID) {
    if (CarID > 0) {
        $.ajax({
            type: 'POST',
            url: 'car/get-car-image-details',
//                async: false,
            data: {CarID: CarID},
            dataType: 'html',
            beforeSend: function () {

            },
            success: function (returnData) {

                $("#car_details").modal('show');
                $('#car_details .model_content_area').html(returnData);
                init_car_detail_datatable();
            },
            error: function (xhr, textStatus, errorThrown) {
            },
            complete: function () {

            }
        });

        return false;
    }
}