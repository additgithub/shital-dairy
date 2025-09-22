function get_updated_datatable() {

    $('#add_edit_form').slideUp(500, function () {
        // $("#display_update_form").scrollTop();
        $('#display_update_form').html('');
    });

    if ($(".dataTables_paginate li.active a").length > 0)
        $(".dataTables_paginate li.active a").trigger("click");
    else
        $(".dataTable th:eq(0)").trigger("click");
}

$(document).on("click", ".remove_image", function (event) {
    var data_id = $(this).children("img").attr('data-id');
    $(this).css('opacity', '0.3');
    $('#delete_image').val($('#delete_image').val() + data_id + ',');
    $(this).removeClass('remove_image');
    $(this).addClass('add_image');
});

$(document).ready(function () {

    $(document).on('click', '.open_return_popup', function () {
        if($('#display_update_form .cancel_button').length>0){
            $('#display_update_form .cancel_button').click();
            $('#display_update_form').empty();
        }
        let id = $(this).data('id'); // order ID
        let controller = $(this).data('control'); // controller name from PHP
        console.log("Controller:", controller); // Debugging line

        // Show modal
        $('#returnModal').modal('show');

        // Optional: load content dynamically
        $.ajax({
            url: BASE_URL + controller + "/get_return_form/" + id, // <-- make a controller method
            type: "GET",
            success: function (response) {
                $('#returnModalContent').html(response);
            },
            error: function () {
                $('#returnModalContent').html('<p class="text-danger">Failed to load content.</p>');
            }
        });
    });

    $('#returnModal').on('hidden.bs.modal', function () {
        $('#returnModalContent').empty();
    });


    var $panel = $('.menu-list').find('.active');
    if ($panel.length > 0) {

        var container = $('.page-sidebar-wrapper');
        var position = $panel.offset().top
            - (container.offset().top + 100)
            + container.scrollTop();
        $('.page-sidebar-wrapper').animate({
            scrollTop: position
        }, 500);
    }

    $(".bank_ins").on('change', function () {
        var val = $(this).val()
        if (val == 'b') {
            $('.bank_div').removeClass('hidden')
        } else {
            $('#Bank').select2('val', '')
            $('.bank_div').addClass('hidden')

        }
    });
    $(".uploadpan").on('change', function () {
        alert('48')
        var formData = new FormData($('#bid_edit_frm')[0]);
        $.ajax({
            type: 'POST',
            url: BASE_URL + 'user/submit-pan-form',
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('.upload_pan_span').html('Uploading...')
            },
            success: function (returnData) {
                if (returnData.status == "ok") {
                    $('.upload_pan_span').addClass('text-success')
                    $('.upload_pan_span').html('PAN Uploaded')
                    setTimeout(function () {
                        window.location.reload()
                    }, 5000);
                } else {
                    var error_html = '';
                    if (typeof returnData.message != "undefined") {
                        $('#span-error-pan').html(returnData.message);
                    }
                    $('.upload_pan_span').html('')
                    setTimeout(function () {
                        $('#span-error-pan').html('');
                    }, 5000);
                }


            },
            error: function (xhr, textStatus, errorThrown) {
                toster_message('There was an unknown error that occurred. You will need to refresh the page to continue working.', 'error', 'error');
            },
            complete: function () {
                $('input[type="submit"]').val('Submit').removeAttr('disabled');
            }
        });
    });

    $("form").attr('autocomplete', 'off');

    $(document).on("click", "a.toggle_sidebar", function (event) {
        $('body').toggleClass('opens');
    });
    $(document).on("click", "a.export-bn", function (event) {
        var $controller = $(this).data('controller');
        var $param = '';
        if ($('#status').length > 0) {
            $param = 'status=' + $('#status').val();
        }
        if ($('#FilterBuyerNameList').length > 0) {
            $param += '&FilterBuyerNameList=' + $('#FilterBuyerNameList').val();
        }
        if ($('#payment_status').length > 0) {
            $param += '&payment_status=' + $('#payment_status').val();
        }
        if ($('#start_date').length > 0) {
            $param += '&start_date=' + $('#start_date').val();
        }
        if ($('#end_date').length > 0) {
            $param += '&end_date=' + $('#end_date').val();
        }
        if ($('#event_id').length > 0) {
            $param += '&event_id=' + $('#event_id').val();
        }
        if ($('#source_type').length > 0) {
            $param += '&source_type=' + $('#source_type').val();
        }
        if ($('#business_rpt_date').length > 0) {
            $param += '&filter=' + $('#business_rpt_date').val();
        }
        var $url = BASE_URL + $controller + '/export-records/?' + $param;
        window.location.href = $url;

    });
    $(document).on("click", "a.export-user-bn", function (event) {
        var $controller = $(this).data('controller');
        var $method = $(this).data('method');
        var $param = '';
        if ($('#status').length > 0) {
            $param = 'status=' + $('#status').val();
        }
        if ($('#FilterBuyerNameList').length > 0) {
            $param += '&FilterBuyerNameList=' + $('#FilterBuyerNameList').val();
        }
        if ($('#payment_status').length > 0) {
            $param += '&payment_status=' + $('#payment_status').val();
        }
        if ($('#start_date').length > 0) {
            $param += '&start_date=' + $('#start_date').val();
        }
        if ($('#end_date').length > 0) {
            $param += '&end_date=' + $('#end_date').val();
        }
        if ($('#event_id').length > 0) {
            $param += '&event_id=' + $('#event_id').val();
        }
        if ($('#source_type').length > 0) {
            $param += '&source_type=' + $('#source_type').val();
        }
        var $url = BASE_URL + $controller + '/' + $method + '/?' + $param;
        window.location.href = $url;

    });

    $('.status_change').click(function () {
        $('.status_change').removeClass('active')
        $(this).addClass('active')
        $('#status').val($(this).data('value'))
        get_updated_datatable();
    })
    $('.Search_bulk_fulfillment').click(function () {

        get_updated_datatable();
        get_updated_fullfilment_count();
    })
    $('.Reset_bulk_fulfillment').click(function () {
        $('.auctionID').val('');
        $('.buyerCode').val('');
        $('.sellerCode').val('');
        $('.LANnumber').val('');
        get_updated_datatable();
    })
    $('#display_update_form select').select2();
    $('.error_div').change(function () {
        $('.error_div').html('')
    });
    var start = new Date();
    var end = new Date(new Date().setYear(start.getFullYear() + 1));
    $('.its_date_field').daterangepicker({
        // singleDatePicker: true,
        format: 'DD-MM-YYYY',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
        // calender_style: "picker_1"
    }, function (start, end, label) {
        //  $('.start_date').val(start)
        //  $('.end_date').val(end)
        $('#start_date').val(start.format('YYYY-MM-DD'));
        $('#end_date').val(end.format('YYYY-MM-DD'));
    });


    $(document).on("change", "#FilterStateNameList", function (event) {
        //        $("#SubCategory").html('<option value="">Select Sub Category</option>');
        var StateID = $(this).val();
        $.ajax({
            type: 'POST',
            url: BASE_URL + 'auction/getCityNameList',
            data: {
                StateID: StateID
            },
            dataType: 'json',
            success: function (returnData) {
                if (returnData.status == "ok") {
                    $("#CityNameList").html('');
                    $('#CityNameList').html('<option value=""></option>');
                    $("#CityNameList").select2("val", "");
                    var output = returnData.data;
                    var temp = [];
                    $.each(output, function (key, value) {
                        temp.push({
                            v: value,
                            k: key
                        });
                    });
                    temp.sort(function (a, b) {
                        if (a.v > b.v) {
                            return 1
                        }
                        if (a.v < b.v) {
                            return -1
                        }
                        return 0;
                    });
                    $.each(temp, function (key, obj) {
                        $("#CityNameList").append('<option value="' + obj.k + '">' + obj.v + '</option>');
                    });
                    // $.each(returnData.data, function (idx, topic) {
                    //     $("#CityNameList").append('<option value="' + idx + '">' + topic + '</option>');
                    // });
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
                $('input[type="submit"]').val('Submit').removeAttr('disabled');
            }
        });
        return false;
    });
    $(document).on("change", "#FilterMultipleStateNameList", function (event) {
        //        $("#SubCategory").html('<option value="">Select Sub Category</option>');
        var StateID = $(this).val();
        $.ajax({
            type: 'POST',
            url: BASE_URL + 'auction-monitor/getCityNameList',
            data: {
                StateID: StateID
            },
            dataType: 'json',
            success: function (returnData) {
                if (returnData.status == "ok") {
                    $("#CityNameList").html('');
                    // $('#CityNameList').html('<option value=""></option>');
                    // $("#CityNameList").select2("val", "");
                    var output = returnData.data;
                    var temp = [];
                    $.each(output, function (key, value) {
                        temp.push({
                            v: value,
                            k: key
                        });
                    });
                    temp.sort(function (a, b) {
                        if (a.v > b.v) {
                            return 1
                        }
                        if (a.v < b.v) {
                            return -1
                        }
                        return 0;
                    });
                    $.each(temp, function (key, obj) {
                        $("#CityNameList").append('<option value="' + obj.k + '">' + obj.v + '</option>');
                    });
                    // $.each(returnData.data, function (idx, topic) {
                    //     $("#CityNameList").append('<option value="' + idx + '">' + topic + '</option>');
                    // });
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
                $('input[type="submit"]').val('Submit').removeAttr('disabled');
            }
        });
        return false;
    });

    $(document).on("change", ".product_feature", function (event) {
        var data_id = $(this).attr('data-id');

        var controller = $(this).attr('data-control');

        if (data_id > 0) {
            $url = 'featured/' + data_id;
        }
        $.ajax({
            type: 'POST',
            url: controller + '/' + $url,
            async: false,
            data: 'pstdata=1',
            dataType: 'json',
            //            beforeSend: function () {
            //                $('#display_update_form').html('<div class="loader_wrapper"><div class="loader"></div></div>');
            //                $('#add_edit_form').show();
            //            },
            success: function (returnData) {
                if (returnData.status == "ok") {
                    showSuccess(returnData.message);
                } else {
                    showErrorMessage(returnData.message);
                }

            },
            error: function (xhr, textStatus, errorThrown) {
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
            }
        });

        return false;

    });

    $(document).on("change", ".banner_feature", function (event) {
        var data_id = $(this).attr('data-id');

        var controller = $(this).attr('data-control');
        var method = $(this).attr('data-method');

        if (data_id > 0) {
            $url = method + '/' + data_id;
        }
        $.ajax({
            type: 'POST',
            url: controller + '/' + $url,
            async: false,
            data: 'pstdata=1',
            dataType: 'json',
            //            beforeSend: function () {
            //                $('#display_update_form').html('<div class="loader_wrapper"><div class="loader"></div></div>');
            //                $('#add_edit_form').show();
            //            },
            success: function (returnData) {
                if (returnData.status == "ok") {
                    showSuccess(returnData.message);
                } else {
                    showErrorMessage(returnData.message);
                }

            },
            error: function (xhr, textStatus, errorThrown) {
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
            }
        });

        return false;

    });
    $(document).on("change", ".user_admin_subscribe_feature", function (event) {
        var data_id = $(this).attr('data-id');

        var controller = $(this).attr('data-control');

        if (data_id > 0) {
            $url = 'subscribed/' + data_id;
        }
        $.ajax({
            type: 'POST',
            url: BASE_URL + controller + '/' + $url,
            async: false,
            data: 'pstdata=1',
            dataType: 'json',
            //            beforeSend: function () {
            //                $('#display_update_form').html('<div class="loader_wrapper"><div class="loader"></div></div>');
            //                $('#add_edit_form').show();
            //            },
            success: function (returnData) {
                if (returnData.status == "ok") {
                    showSuccess(returnData.message);
                    setTimeout(function () {
                        window.location.reload()
                    }, 300);
                } else {
                    showErrorMessage(returnData.message);
                }

            },
            error: function (xhr, textStatus, errorThrown) {
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
            }
        });

        return false;

    });

    $(document).on("click", ".btn.approve_feature", function (event) {
        var $ts = $(this);
        var data_id = $ts.attr('data-id');
        var data_value = $ts.attr('data-value');
        var method = $ts.attr('data-method');
        var $url = 'user/' + method + "/" + data_id;

        $.ajax({
            type: 'POST',
            url: $url,
            async: false,
            data: { value: data_value },
            dataType: 'json',
            beforeSend: function () {
                $('.dataTables_processing').css('visibility', 'visible');
            },
            success: function (returnData) {
                if (returnData.status == "ok") {
                    showSuccess(returnData.message);
                    get_updated_datatable();
                } else {
                    showErrorMessage(returnData.message);
                }

            },
            error: function (xhr, textStatus, errorThrown) {
                new PNotify({ title: 'Error', text: 'There was an unknown error that occurred. You will need to refresh the page to continue working.', type: 'error', styling: 'bootstrap3' });
            },
            complete: function () {
                $('.dataTables_processing').css('visibility', 'hidden');

            }
        });

        return false;

    });

    $(document).on("click", ".btn.bid_approve_feature", function (event) {
        var $ts = $(this);
        var bid_id = $ts.attr('data-bid-id');
        var car_id = $ts.attr('data-car-id');
        var event_id = $ts.attr('data-event-id');
        var value = $ts.attr('data-value');
        var method = $ts.attr('data-method');
        var user_id = $(this).attr('data-user-id');
        var controller = $(this).attr('data-controller');
        var $url = BASE_URL + controller + '/' + method;

        $.ajax({
            type: 'POST',
            url: BASE_URL + controller + '/' + method,
            async: false,
            data: { CarID: car_id, UserID: user_id, bid_id: bid_id },
            dataType: 'html',
            beforeSend: function () {
                $('html, body').animate({
                    scrollTop: $("#add_edit_form").offset().top
                }, 2000);
                $('#display_update_form').html('<div class="loader_wrapper"><div class="loader"></div></div>');
                $('#add_edit_form').show();
            },
            success: function (returnData) {
                setTimeout(function () {
                    //$("#display_update_form").scrollTop();
                    $('#display_update_form').html(returnData);
                    $("select.select2").select2();
                }, 500);
                $('#display_update_form select').select2();
                $('.load_hide').hide();

            },
            error: function (xhr, textStatus, errorThrown) {
                $('#add_edit_form').slideUp(500, function () {
                    $('#display_update_form').html('');

                });
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
            }
        });

        return false;

        $.ajax({
            type: 'POST',
            url: BASE_URL + $url,
            async: false,
            data: { value: value, car_id: car_id, event_id: event_id },
            dataType: 'json',
            beforeSend: function () {
                //                $('.dataTables_processing').css('visibility', 'visible');
            },
            success: function (returnData) {
                if (returnData.status == "ok") {
                    showSuccess(returnData.message);
                    window.location.reload();
                    //                    $ts.attr("disabled", "disabled");
                    //                    $('.bid_approve_feature').css('btn-success','hidden');
                    //                    get_updated_datatable();
                } else {
                    showErrorMessage(returnData.message);
                }

            },
            error: function (xhr, textStatus, errorThrown) {
                // new PNotify({ title: 'Error', text: 'There was an unknown error that occurred. You will need to refresh the page to continue working.', type: 'error', styling: 'bootstrap3' });
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
                //                $('.dataTables_processing').css('visibility', 'hidden');
                //                $ts.attr("disabled", "disabled");
            }
        });

        return false;

    });

    $(document).on("click", ".btn.bid_cancel_form", function (event) {

        var data_id = $(this).attr('data-id');
        var user_id = $(this).attr('data-user-id');
        var bid_id = $(this).attr('data-bid-id');
        //        alert(user_id);
        var controller = $(this).attr('data-control');
        var method = $(this).attr('data-method');
        $.ajax({
            type: 'POST',
            url: BASE_URL + controller + '/' + method,
            async: false,
            data: { CarID: data_id, UserID: user_id, bid_id: bid_id },
            dataType: 'html',
            beforeSend: function () {
                $('html, body').animate({
                    scrollTop: $("#add_edit_form").offset().top
                }, 2000);
                $('#display_update_form').html('<div class="loader_wrapper"><div class="loader"></div></div>');
                $('#add_edit_form').show();
            },
            success: function (returnData) {
                setTimeout(function () {
                    //$("#display_update_form").scrollTop();
                    $('#display_update_form').html(returnData);
                    $("select.select2").select2();
                }, 500);
                $('#display_update_form select').select2();
                $('.load_hide').hide();

            },
            error: function (xhr, textStatus, errorThrown) {
                $('#add_edit_form').slideUp(500, function () {
                    $('#display_update_form').html('');

                });
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
            }
        });

        return false;

    });
    $(document).on("click", ".btn.incentive_approve_feature", function (event) {
        var $ts = $(this);
        var referal_code_id = $ts.attr('data-referal-code-id');
        var referal_incentive_id = $ts.attr('data-referal-incentive-id');
        var method = $ts.attr('data-method');
        var controller = $ts.attr('data-controller');
        var $url = controller + '/' + method;

        $.ajax({
            type: 'POST',
            url: BASE_URL + $url,
            async: false,
            data: { referal_code_id: referal_code_id, referal_incentive_id: referal_incentive_id },
            dataType: 'json',
            beforeSend: function () {
                //                $('.dataTables_processing').css('visibility', 'visible');
            },
            success: function (returnData) {
                if (returnData.status == "ok") {
                    showSuccess(returnData.message);
                    setTimeout(function () {
                        $('.open_rsd_form_form[data-id="' + referal_code_id + '"]').get(0).click();
                    }, 300);
                    console.log('230')

                } else {
                    showErrorMessage(returnData.message);
                }

            },
            error: function (xhr, textStatus, errorThrown) {
                new PNotify({ title: 'Error', text: 'There was an unknown error that occurred. You will need to refresh the page to continue working.', type: 'error', styling: 'bootstrap3' });
            },
            complete: function () {
                //                $('.dataTables_processing').css('visibility', 'hidden');
                //                $ts.attr("disabled", "disabled");
            }
        });



    });
    $(document).on("click", ".btn.approve_auction", function (event) {
        var $ts = $(this);
        var data_id = $ts.attr('data-id');
        var data_value = $ts.attr('data-value');
        var method = $ts.attr('data-method');
        var $url = 'bank_auction_list/' + method + "/" + data_id;

        $.ajax({
            type: 'POST',
            url: $url,
            async: false,
            data: { value: data_value },
            dataType: 'json',
            beforeSend: function () {
                $('.dataTables_processing').css('visibility', 'visible');
            },
            success: function (returnData) {
                if (returnData.status == "ok") {
                    showSuccess(returnData.message);
                    get_updated_datatable();
                } else {
                    showErrorMessage(returnData.message);
                }

            },
            error: function (xhr, textStatus, errorThrown) {
                new PNotify({ title: 'Error', text: 'There was an unknown error that occurred. You will need to refresh the page to continue working.', type: 'error', styling: 'bootstrap3' });
            },
            complete: function () {
                $('.dataTables_processing').css('visibility', 'hidden');

            }
        });

        return false;

    });
    $(document).on("click", ".btn.send_for_approval", function (event) {
        var $ts = $(this);
        var data_id = $ts.attr('data-id');
        var data_value = $ts.attr('data-value');
        var method = $ts.attr('data-method');
        var $url = 'auction/' + method + "/" + data_id;

        $.ajax({
            type: 'POST',
            url: $url,
            async: false,
            data: { value: data_value },
            dataType: 'json',
            beforeSend: function () {
                $('.dataTables_processing').css('visibility', 'visible');
            },
            success: function (returnData) {
                if (returnData.status == "ok") {
                    showSuccess(returnData.message);
                    get_updated_datatable();
                } else {
                    showErrorMessage(returnData.message);
                }

            },
            error: function (xhr, textStatus, errorThrown) {
                new PNotify({ title: 'Error', text: 'There was an unknown error that occurred. You will need to refresh the page to continue working.', type: 'error', styling: 'bootstrap3' });
            },
            complete: function () {
                $('.dataTables_processing').css('visibility', 'hidden');

            }
        });

        return false;

    });
    $(document).on("click", ".btn.inquiry_approve_feature", function (event) {
        var $ts = $(this);
        var data_id = $ts.attr('data-id');
        var data_value = $ts.attr('data-value');
        var method = $ts.attr('data-method');
        var $url = 'car-inquiry/' + method + "/" + data_id;

        $.ajax({
            type: 'POST',
            url: $url,
            async: false,
            data: { value: data_value },
            dataType: 'json',
            beforeSend: function () {
                $('.dataTables_processing').css('visibility', 'visible');
            },
            success: function (returnData) {
                if (returnData.status == "ok") {
                    showSuccess(returnData.message);
                    get_updated_datatable();
                } else {
                    showErrorMessage(returnData.message);
                }

            },
            error: function (xhr, textStatus, errorThrown) {
                new PNotify({ title: 'Error', text: 'There was an unknown error that occurred. You will need to refresh the page to continue working.', type: 'error', styling: 'bootstrap3' });
            },
            complete: function () {
                $('.dataTables_processing').css('visibility', 'hidden');

            }
        });

        return false;

    });

    $("select.select2").select2();

    $(document).on("click", "a.export-event-bid", function (event) {
        var $controller = 'auction';
        var data_id = $(this).attr('data-id');
        var $validation_error = '';


        //        var $param = 'start_date=' + $('#start_date').val();
        //        $param += '&end_date=' + $('#end_date').val();
        //        var $url = BASE_URL + $controller + '/loan_request_export/?' + $param;

        var $url = BASE_URL + $controller + '/event_bid_export/' + data_id;
        //            window.location.href = $controller + '/pdf-download/?'+$param;
        window.location.href = $url;
        //            window.open($url, '_blank');

    });

    if ($('.common_datatable').length > 0) {
        var add_button = 0;
        var add_button_title = 'Add New';
        if (typeof $('.common_datatable').attr('data-add-button') != "undefined") {
            add_button = 1;
        }
        var $url = BASE_URL + $('.common_datatable').attr('data-control') + '/' + $('.common_datatable').attr('data-mathod');

        var oTable = $('.common_datatable').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "pageLength": 12,
            scrollY: 200,
            scrollX: true,
            "sAjaxSource": $url,

            "sDom": "<'row'<'col-md-6'l <'toolbar'>><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
            "bLengthChange": false,
            "fnServerParams": function (aoData, fnCallback) {
                //                if ($('#BoardID').length > 0) {
                //                    aoData.push(  {"name": "BoardID", "value":  $('#BoardID').val() } );
                //                }
                if ($('#end_date').length > 0) {
                    aoData.push({ "name": "end_date", "value": $('#end_date').val() });
                }
                if ($('#start_date').length > 0) {
                    aoData.push({ "name": "start_date", "value": $('#start_date').val() });
                }
                if ($('#filter').length > 0) {
                    aoData.push({ "name": "filter", "value": $('#filter').val() });
                }
                if ($('#event_id').length > 0) {
                    aoData.push({ "name": "event_id", "value": $('#event_id').val() });
                }
                if ($('#status').length > 0) {
                    aoData.push({ "name": "status", "value": $('#status').val() });
                }
                if ($('#FilterStateNameList').length > 0) {
                    aoData.push({ "name": "State", "value": $('#FilterStateNameList').val() });
                }
                if ($('#CityNameList').length > 0) {
                    aoData.push({ "name": "City", "value": $('#CityNameList').val() });
                }
                if ($('#auction_type').length > 0) {
                    aoData.push({ "name": "AuctionType", "value": $('#auction_type').val() });
                }
                if ($('.auctionID').length > 0) {
                    aoData.push({ "name": "auctionID", "value": $('.auctionID').val() });
                }
                if ($('#buyerCode').length > 0) {
                    aoData.push({ "name": "buyerCode", "value": $('#buyerCode').val() });
                }
                if ($('#LANnumber').length > 0) {
                    aoData.push({ "name": "LANnumber", "value": $('#LANnumber').val() });
                }
                if ($('#Bank').length > 0) {
                    aoData.push({ "name": "Bank", "value": $('#Bank').val() });
                }
                if ($('#VehicleTypeID').length > 0) {
                    aoData.push({ "name": "VehicleTypeID", "value": $('#VehicleTypeID').val() });
                }
                if ($('#que_select_all').length > 0 && $('#que_select_all').is(':checked')) {
                    aoData.push({ "name": "que_select_all", "value": $('#que_select_all').val() });
                }
                if ($('#source_type').length > 0) {
                    aoData.push({ "name": "source_type", "value": $('#source_type').val() });
                }
                if ($('#SourceName').length > 0) {
                    aoData.push({ "name": "SourceID", "value": $('#SourceName').val() });
                }
                if ($("input[name='event_id[]']").length > 0) {
                    var auction_ids = $("input[name='event_id[]']").map(function () {
                        return $(this).val();
                    })
                        .get()
                        .join(",");
                    aoData.push({
                        "name": "auction_ids",
                        "value": auction_ids
                    });
                }
                if ($("input[name='bid_id[]']").length > 0) {
                    var bid_ids = $("input[name='bid_id[]']").map(function () {
                        alert($(this).val())
                        return $(this).val();
                    })
                        .get()
                        .join(",");
                    aoData.push({
                        "name": "bid_ids",
                        "value": bid_ids
                    });
                }
            },
            "fnInitComplete": function () {
                $('.tooltip-top a').tooltip();

                $('select').select2();
                if (add_button == 1) {
                    var $controller = $('.common_datatable').attr('data-control');
                    $(".dataTables_wrapper .toolbar").html('<div class="table-tools-actions"><a class="btn btn-primary open_my_form_form" href="javascript:;" data-control="' + $controller + '">' + add_button_title + ' <i class="fa fa-plus"></i></a></div>');
                }
                $('.search_mq').on('change', function () {
                    oTable.fnDraw();
                });
                $('.date_filter').datepicker({

                    format: "yyyy-mm-dd",
                    startView: 1,
                    autoclose: true,
                    todayHighlight: true
                }).on('changeDate', function (ev) {
                    oTable.fnDraw();
                });
            },
            "fnDrawCallback": function () {

                $('.tooltip-top a').tooltip();
                $(".event_id_chk").unbind("change");
                $(".event_id_chk").change(function () {
                    var id = $(this).val();
                    if ($(this).is(':checked')) {
                        var html = '<div id="event_id_' + id + '">' +
                            '<input type="hidden" name="event_id[]" value="' + id + '">' +
                            '</div>';
                        $("#event_div").append(html);

                        // $("#rgp_" + id).parent().parent().css("background-color", '#d8dcde');

                    } else {
                        $('.merge_paper_div').addClass('hidden');
                        $("#event_id_" + id).remove();
                        // $("#rgp_" + id).parent().parent().css("background-color", '');
                    }
                });
                $(".bid_id_chk").unbind("change");
                $(".bid_id_chk").change(function () {
                    var id = $(this).val();
                    if ($(this).is(':checked')) {
                        var html = '<div id="bid_id_' + id + '">' +
                            '<input type="hidden" name="bid_id[]" value="' + id + '">' +
                            '</div>';
                        $("#bid_div").append(html);

                        // $("#rgp_" + id).parent().parent().css("background-color", '#d8dcde');

                    } else {
                        $('.merge_paper_div').addClass('hidden');
                        $("#bid_id_" + id).remove();
                        // $("#rgp_" + id).parent().parent().css("background-color", '');
                    }
                });

            },
            "oLanguage": { "sLengthMenu": "_MENU_ ", "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries" },
        });

    }
    if ($('.auction_datatable').length > 0) {
        var add_button = 0;
        var add_button_title = 'Add New';
        if (typeof $('.auction_datatable').attr('data-add-button') != "undefined") {
            add_button = 1;
        }
        var $url = BASE_URL + $('.auction_datatable').attr('data-control') + '/' + $('.auction_datatable').attr('data-mathod');
        var $tableID = $('.auction_datatable').attr('id');
        var sortable = [];
        if ($tableID == 'auction_table') {
            sortable = [{ "bSortable": false, "bSearchable": false }, { "bSortable": true, "bSearchable": false }, { "bSortable": true, "bSearchable": false }, { "bSortable": false, "bSearchable": false }, { "bSortable": false, "bSearchable": false }, { "bSortable": false, "bSearchable": false }, { "bSortable": false, "bSearchable": false }]

        }

        var oTable = $('.auction_datatable').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "pageLength": 12,
            scrollY: 200,
            scrollX: true,
            "sAjaxSource": $url,
            "aoColumns": sortable,
            "sDom": "<'row'<'col-md-6'l <'toolbar'>><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
            "bLengthChange": true,
            "fnServerParams": function (aoData, fnCallback) {
                //                if ($('#BoardID').length > 0) {
                //                    aoData.push(  {"name": "BoardID", "value":  $('#BoardID').val() } );
                //                }
                if ($('#end_date').length > 0) {
                    aoData.push({ "name": "end_date", "value": $('#end_date').val() });
                }
                if ($('#start_date').length > 0) {
                    aoData.push({ "name": "start_date", "value": $('#start_date').val() });
                }
                if ($('#filter').length > 0) {
                    aoData.push({ "name": "filter", "value": $('#filter').val() });
                }
                if ($('#event_id').length > 0) {
                    aoData.push({ "name": "event_id", "value": $('#event_id').val() });
                }
                if ($('#status').length > 0) {
                    aoData.push({ "name": "status", "value": $('#status').val() });
                }
                if ($('#FilterStateNameList').length > 0) {
                    aoData.push({ "name": "State", "value": $('#FilterStateNameList').val() });
                }
                if ($('#CityNameList').length > 0) {
                    aoData.push({ "name": "City", "value": $('#CityNameList').val() });
                }
                if ($('#auction_type').length > 0) {
                    aoData.push({ "name": "AuctionType", "value": $('#auction_type').val() });
                }
                if ($('.auctionID').length > 0) {
                    aoData.push({ "name": "auctionID", "value": $('.auctionID').val() });
                }
                if ($('#buyerCode').length > 0) {
                    aoData.push({ "name": "buyerCode", "value": $('#buyerCode').val() });
                }
                if ($('#LANnumber').length > 0) {
                    aoData.push({ "name": "LANnumber", "value": $('#LANnumber').val() });
                }
                if ($('#Bank').length > 0) {
                    aoData.push({ "name": "Bank", "value": $('#Bank').val() });
                }
                if ($('#VehicleTypeID').length > 0) {
                    aoData.push({ "name": "VehicleTypeID", "value": $('#VehicleTypeID').val() });
                }
                if ($('#que_select_all').length > 0 && $('#que_select_all').is(':checked')) {
                    aoData.push({ "name": "que_select_all", "value": $('#que_select_all').val() });
                }
                if ($("input[name='event_id[]']").length > 0) {
                    var auction_ids = $("input[name='event_id[]']").map(function () {
                        return $(this).val();
                    })
                        .get()
                        .join(",");
                    aoData.push({
                        "name": "auction_ids",
                        "value": auction_ids
                    });
                }
                if ($("input[name='bid_id[]']").length > 0) {
                    var bid_ids = $("input[name='bid_id[]']").map(function () {
                        alert($(this).val())
                        return $(this).val();
                    })
                        .get()
                        .join(",");
                    aoData.push({
                        "name": "bid_ids",
                        "value": bid_ids
                    });
                }
            },
            "fnInitComplete": function () {
                $('.tooltip-top a').tooltip();

                $('select').select2();
                if (add_button == 1) {
                    var $controller = $('.auction_datatable').attr('data-control');
                    $(".dataTables_wrapper .toolbar").html('<div class="table-tools-actions"><a class="btn btn-primary open_my_form_form" href="javascript:;" data-control="' + $controller + '">' + add_button_title + ' <i class="fa fa-plus"></i></a></div>');
                }
                $('.search_mq').on('change', function () {
                    oTable.fnDraw();
                });
                $('.date_filter').datepicker({
                    format: "dd-mm-yyyy",
                    // startView: 1,
                    autoclose: true,
                    todayHighlight: true
                }).on('changeDate', function (ev) {
                    oTable.fnDraw();
                });
            },
            "fnDrawCallback": function () {

                $('.tooltip-top a').tooltip();
                $(".event_id_chk").unbind("change");
                $(".event_id_chk").change(function () {
                    var id = $(this).val();
                    if ($(this).is(':checked')) {
                        var html = '<div id="event_id_' + id + '">' +
                            '<input type="hidden" name="event_id[]" value="' + id + '">' +
                            '</div>';
                        $("#event_div").append(html);

                        // $("#rgp_" + id).parent().parent().css("background-color", '#d8dcde');

                    } else {
                        $('.merge_paper_div').addClass('hidden');
                        $("#event_id_" + id).remove();
                        // $("#rgp_" + id).parent().parent().css("background-color", '');
                    }
                });
                $(".bid_id_chk").unbind("change");
                $(".bid_id_chk").change(function () {
                    var id = $(this).val();
                    if ($(this).is(':checked')) {
                        var html = '<div id="bid_id_' + id + '">' +
                            '<input type="hidden" name="bid_id[]" value="' + id + '">' +
                            '</div>';
                        $("#bid_div").append(html);

                        // $("#rgp_" + id).parent().parent().css("background-color", '#d8dcde');

                    } else {
                        $('.merge_paper_div').addClass('hidden');
                        $("#bid_id_" + id).remove();
                        $("#rgp_" + id).parent().parent().css("background-color", '');
                    }
                });

            },
            "oLanguage": { "sLengthMenu": "_MENU_ ", "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries" },
        });

    }

    $('#que_select_all').on('click', function () {
        if (this.checked) {
            $('.event_id_chk').each(function () {
                this.checked = true;
            });
        } else {
            $('.event_id_chk').each(function () {
                this.checked = false;
            });
        }
    });
    if ($('.common_whole_datatable').length > 0) {
        var add_button = 0;
        var add_button_title = 'Add New';
        if (typeof $('.common_whole_datatable').attr('data-add-button') != "undefined") {
            add_button = 1;
        }
        var $url = BASE_URL + $('.common_whole_datatable').attr('data-control') + '/' + $('.common_whole_datatable').attr('data-mathod');
        var oTable_1 = $('.common_whole_datatable').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            // "pageLength": 500,
            "iDisplayLength": 500,
            "sAjaxSource": $url,
            "sDom": "<'row'<'col-md-6'l <'toolbar'>><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
            "bLengthChange": false,
            "fnServerParams": function (aoData, fnCallback) {
                //                if ($('#BoardID').length > 0) {
                //                    aoData.push(  {"name": "BoardID", "value":  $('#BoardID').val() } );
                //                }
                if ($('#end_date').length > 0) {
                    aoData.push({ "name": "end_date", "value": $('#end_date').val() });
                }
                if ($('#start_date').length > 0) {
                    aoData.push({ "name": "start_date", "value": $('#start_date').val() });
                }
                if ($('#filter').length > 0) {
                    aoData.push({ "name": "filter", "value": $('#filter').val() });
                }
                if ($('#event_id').length > 0) {
                    aoData.push({ "name": "event_id", "value": $('#event_id').val() });
                }
                if ($('#status').length > 0) {
                    aoData.push({ "name": "status", "value": $('#status').val() });
                }
                if ($('#FilterStateNameList').length > 0) {
                    aoData.push({ "name": "State", "value": $('#FilterStateNameList').val() });
                }
                if ($('#FilterMultipleStateNameList').length > 0) {
                    aoData.push({ "name": "State", "value": $('#FilterMultipleStateNameList').val() });
                }
                if ($('#CityNameList').length > 0) {
                    aoData.push({ "name": "City", "value": $('#CityNameList').val() });
                }
                if ($('#auction_type').length > 0) {
                    aoData.push({ "name": "AuctionType", "value": $('#auction_type').val() });
                }
                if ($('.auctionID').length > 0) {
                    aoData.push({ "name": "auctionID", "value": $('.auctionID').val() });
                }
                if ($('#buyerCode').length > 0) {
                    aoData.push({ "name": "buyerCode", "value": $('#buyerCode').val() });
                }
                if ($('#sellerCode').length > 0) {
                    aoData.push({ "name": "sellerCode", "value": $('#sellerCode').val() });
                }
                if ($('#LANnumber').length > 0) {
                    aoData.push({ "name": "LANnumber", "value": $('#LANnumber').val() });
                }
                if ($("input[name='event_id[]']").length > 0) {
                    var auction_ids = $("input[name='event_id[]']").map(function () {
                        return $(this).val();
                    })
                        .get()
                        .join(",");
                    aoData.push({
                        "name": "auction_ids",
                        "value": auction_ids
                    });
                }
                if ($("input[name='bid_id[]']").length > 0) {
                    var bid_ids = $("input[name='bid_id[]']").map(function () {
                        alert($(this).val())
                        return $(this).val();
                    })
                        .get()
                        .join(",");
                    aoData.push({
                        "name": "bid_ids",
                        "value": bid_ids
                    });
                }
            },
            "fnInitComplete": function () {
                $('.tooltip-top a').tooltip();

                $('select').select2();
                if (add_button == 1) {
                    var $controller = $('.common_datatable').attr('data-control');
                    $(".dataTables_wrapper .toolbar").html('<div class="table-tools-actions"><a class="btn btn-primary open_my_form_form" href="javascript:;" data-control="' + $controller + '">' + add_button_title + ' <i class="fa fa-plus"></i></a></div>');
                }
                $('.search_mq').on('change', function () {
                    oTable_1.fnDraw();
                });
                $('.date_filter').datepicker({
                    format: "yyyy-mm-dd",
                    startView: 1,
                    autoclose: true,
                    todayHighlight: true
                }).on('changeDate', function (ev) {
                    oTable_1.fnDraw();
                });
            },
            "fnDrawCallback": function () {
                if ($('.exiry_p_tag').length > 0) {
                    $(".exiry_p_tag").each(function (index) {
                        // Set the date we're counting down to
                        var EventID = $(this).data('event-id');
                        var EndTime = $(this).data('expiry-date');
                        var CutoffTime = $(this).data('cutoff-date');
                        var countDownDate = new Date(EndTime).getTime();
                        var CutoffcountDownDate = new Date(CutoffTime).getTime();
                        // var countDownDate = new Date("<?php echo '2022-07-13 16:43:09' ?>").getTime();
                        //alert(countDownDate);
                        // Update the count down every 1 second
                        var x = setInterval(function (event) {

                            // Get today's date and time
                            var now = new Date().getTime();

                            // Find the distance between now and the count down date
                            var distance = countDownDate - now;
                            var Cutoffdistance = CutoffcountDownDate - now;

                            // Time calculations for days, hours, minutes and seconds
                            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                            var expr_minutes = Math.floor((countDownDate % (1000 * 60 * 60)) / (1000 * 60));

                            // Output the result in an element with id="demo"
                            if (distance > 0) {
                                $('.p_tag_event_' + EventID).html(days + "d " + hours + "h " + minutes + "m " + seconds + "s ")
                            }

                            if (minutes <= 30 && hours == 0 && days == 0) {
                                $(this).removeClass('hidden')
                                $('.p_tag_event_' + EventID).html(minutes + ":" + seconds)

                            } else {
                                $(this).addClass('hidden')

                            }

                            // If the count down is over, write some text 
                            if (Cutoffdistance < 0) {
                                // clearInterval(x);
                                // $('#AUCTIONCLOSED').modal({
                                //     backdrop: 'static',
                                //     keyboard: false
                                // }, 'show');
                                // $('.closedAuctionName').html('<?php echo $event_name->EventName; ?>');

                            }
                        }, 1000);
                    });
                }
                $('.tooltip-top a').tooltip();
                $(".event_id_chk").unbind("change");
                $(".event_id_chk").change(function () {
                    var id = $(this).val();
                    if ($(this).is(':checked')) {
                        var html = '<div id="event_id_' + id + '">' +
                            '<input type="hidden" name="event_id[]" value="' + id + '">' +
                            '</div>';
                        $("#event_div").append(html);

                        $("#rgp_" + id).parent().parent().css("background-color", '#d8dcde');

                    } else {
                        $('.merge_paper_div').addClass('hidden');
                        $("#event_id_" + id).remove();
                        $("#rgp_" + id).parent().parent().css("background-color", '');
                    }
                });
                $(".bid_id_chk").unbind("change");
                $(".bid_id_chk").change(function () {
                    var id = $(this).val();
                    if ($(this).is(':checked')) {
                        var html = '<div id="bid_id_' + id + '">' +
                            '<input type="hidden" name="bid_id[]" value="' + id + '">' +
                            '</div>';
                        $("#bid_div").append(html);

                        $("#rgp_" + id).parent().parent().css("background-color", '#d8dcde');

                    } else {
                        $('.merge_paper_div').addClass('hidden');
                        $("#bid_id_" + id).remove();
                        $("#rgp_" + id).parent().parent().css("background-color", '');
                    }
                });

            },
            "oLanguage": { "sLengthMenu": "_MENU_ ", "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries" },
        });

    }

    $(document).on("click", ".btn.open_my_form_form", function (event) {

        var data_id = $(this).attr('data-id');
        var controller = $(this).attr('data-control');
        var event_id = $('.event_id').val();
        var method = $(this).attr('data-method');
        if (method) {
            var $url = method;

        } else {

            var $url = 'add';
        }
        if (data_id > 0) {
            $url = 'edit/' + data_id;
        }
        $.ajax({
            type: 'POST',
            url: BASE_URL + controller + '/' + $url,
            async: false,
            data: { event_id: event_id },
            dataType: 'html',
            beforeSend: function () {
                $('html, body').animate({
                    scrollTop: $("#add_edit_form").offset().top
                }, 2000);
                $('#display_update_form').html('<div class="loader_wrapper"><div class="loader"></div></div>');
                $('#add_edit_form').show();
            },
            success: function (returnData) {
                setTimeout(function () {
                    //$("#display_update_form").scrollTop();
                    $('#display_update_form').html(returnData);
                    $("select.select2").select2();
                }, 500);

                //                if ($("#section_start_time").length > 0) {
                //                    $("#section_start_time").timepicker();
                //                    $("#section_end_time").timepicker();
                //                }

                //                $("#display_update_form :file").filestyle({buttonText: "&nbsp; Upload", buttonName: "btn-primary", placeholder: "No file selected", buttonBefore: true});

                $('#display_update_form select').select2();
                $('.load_hide').hide();

                var start = new Date();
                console.log(start, 'start')
                // set end date to max one year period:
                var end = new Date(new Date().setYear(start.getFullYear() + 1));



                //                $('.its_time_field').timepicker({
                //                    timeFormat: 'h:mm p',
                //                    interval: 05,
                //                    minTime: '6',
                //                    maxTime: '11:00 PM',
                ////                    defaultTime: '6',
                //                    startTime: '6:00 AM',
                //                    dynamic: false,
                //                    dropdown: true,
                //                    scrollbar: true
                //                });



            },
            error: function (xhr, textStatus, errorThrown) {
                $('#add_edit_form').slideUp(500, function () {
                    $('#display_update_form').html('');

                });
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
            }
        });

        return false;

    });
    $(document).on("click", ".btn.open_my_withdraw_form", function (event) {

        var data_id = $(this).attr('data-id');
        var controller = $(this).attr('data-control');
        var event_id = $('.event_id').val();
        var method = $(this).attr('data-method');
        var action = $(this).attr('data-action');
        if (method) {
            var $url = method;

        } else {

            var $url = 'add';
        }
        if (data_id > 0) {
            $url = 'edit/' + data_id;
        }
        $.ajax({
            type: 'POST',
            url: BASE_URL + controller + '/' + $url,
            async: false,
            data: { event_id: event_id, action: action },
            dataType: 'html',
            beforeSend: function () {
                $('html, body').animate({
                    scrollTop: $("#add_edit_form").offset().top
                }, 2000);
                $('#display_update_form').html('<div class="loader_wrapper"><div class="loader"></div></div>');
                $('#add_edit_form').show();
            },
            success: function (returnData) {
                setTimeout(function () {
                    //$("#display_update_form").scrollTop();
                    $('#display_update_form').html(returnData);
                    $("select.select2").select2();
                }, 500);

                //                if ($("#section_start_time").length > 0) {
                //                    $("#section_start_time").timepicker();
                //                    $("#section_end_time").timepicker();
                //                }

                //                $("#display_update_form :file").filestyle({buttonText: "&nbsp; Upload", buttonName: "btn-primary", placeholder: "No file selected", buttonBefore: true});

                $('#display_update_form select').select2();
                $('.load_hide').hide();

                var start = new Date();
                console.log(start, 'start')
                // set end date to max one year period:
                var end = new Date(new Date().setYear(start.getFullYear() + 1));



                //                $('.its_time_field').timepicker({
                //                    timeFormat: 'h:mm p',
                //                    interval: 05,
                //                    minTime: '6',
                //                    maxTime: '11:00 PM',
                ////                    defaultTime: '6',
                //                    startTime: '6:00 AM',
                //                    dynamic: false,
                //                    dropdown: true,
                //                    scrollbar: true
                //                });



            },
            error: function (xhr, textStatus, errorThrown) {
                $('#add_edit_form').slideUp(500, function () {
                    $('#display_update_form').html('');

                });
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
            }
        });

        return false;

    });
    $(document).on("click", ".btn.open_popup_form", function (event) {

        var data_id = $(this).attr('data-id');
        var controller = $(this).attr('data-control');
        var event_id = $('.event_id').val();
        var method = $(this).attr('data-method');
        var view_type = $(this).attr('data-view-type');
        if (method) {
            var $url = method;
        } else {
            var $url = 'get_history';
        }
        if (data_id > 0) {
            $url = method + '/' + data_id;
        }
        $.ajax({
            type: 'POST',
            url: BASE_URL + controller + '/' + $url,
            async: false,
            data: { event_id: event_id, view_type: view_type },
            dataType: 'html',
            beforeSend: function () {

                $('#fullfillment .modal-body').html('');
            },
            success: function (returnData) {
                setTimeout(function () {
                    //$("#display_update_form").scrollTop();
                    $('#fullfillment').modal('show');
                    $('#fullfillment .modal-body').html(returnData);
                    $("select.select2").select2();
                }, 500);

                //                if ($("#section_start_time").length > 0) {
                //                    $("#section_start_time").timepicker();
                //                    $("#section_end_time").timepicker();
                //                }

                //                $("#display_update_form :file").filestyle({buttonText: "&nbsp; Upload", buttonName: "btn-primary", placeholder: "No file selected", buttonBefore: true});

                $('#display_update_form select').select2();
                $('.load_hide').hide();

                var start = new Date();
                console.log(start, 'start')
                // set end date to max one year period:
                var end = new Date(new Date().setYear(start.getFullYear() + 1));



                //                $('.its_time_field').timepicker({
                //                    timeFormat: 'h:mm p',
                //                    interval: 05,
                //                    minTime: '6',
                //                    maxTime: '11:00 PM',
                ////                    defaultTime: '6',
                //                    startTime: '6:00 AM',
                //                    dynamic: false,
                //                    dropdown: true,
                //                    scrollbar: true
                //                });



            },
            error: function (xhr, textStatus, errorThrown) {
                $('#fullfillment').modal('hide');
                $('#fullfillment .modal-body').html('');
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
            }
        });

        return false;

    });
    $(document).on("click", ".btn.open_rsd_form_form", function (event) {

        var data_id = $(this).attr('data-id');
        var controller = $(this).attr('data-control');
        var event_id = $('.event_id').val();
        var method = $(this).attr('data-method');
        if (method) {
            var $url = method;

        }
        if (data_id > 0) {
            $url = method + '/' + data_id;
        }
        $.ajax({
            type: 'POST',
            url: controller + '/' + $url,
            async: false,
            data: { event_id: event_id },
            dataType: 'html',
            beforeSend: function () {
                $('html, body').animate({
                    scrollTop: $("#add_edit_form").offset().top
                }, 2000);
                $('#display_update_form').html('<div class="loader_wrapper"><div class="loader"></div></div>');
                $('#add_edit_form').show();
            },
            success: function (returnData) {
                setTimeout(function () {
                    //$("#display_update_form").scrollTop();
                    $('#display_update_form').html(returnData);
                    $("select.select2").select2();
                }, 500);

                //                if ($("#section_start_time").length > 0) {
                //                    $("#section_start_time").timepicker();
                //                    $("#section_end_time").timepicker();
                //                }

                //                $("#display_update_form :file").filestyle({buttonText: "&nbsp; Upload", buttonName: "btn-primary", placeholder: "No file selected", buttonBefore: true});

                $('#display_update_form select').select2();
                $('.load_hide').hide();

                //                $('.its_date_field').daterangepicker({
                //                    singleDatePicker: true,
                //                    format: 'DD-MM-YYYY',
                //                    calender_style: "picker_1"
                //                }, function (start, end, label) {
                //
                //                });

                //                $('.its_time_field').timepicker({
                //                    timeFormat: 'h:mm p',
                //                    interval: 05,
                //                    minTime: '6',
                //                    maxTime: '11:00 PM',
                ////                    defaultTime: '6',
                //                    startTime: '6:00 AM',
                //                    dynamic: false,
                //                    dropdown: true,
                //                    scrollbar: true
                //                });



            },
            error: function (xhr, textStatus, errorThrown) {
                $('#add_edit_form').slideUp(500, function () {
                    $('#display_update_form').html('');

                });
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
            }
        });

        return false;

    });
    $(document).on("click", ".btn.commission_form", function (event) {

        var data_id = $(this).attr('data-id');
        var controller = $(this).attr('data-control');
        var method = $(this).attr('data-method');
        $.ajax({
            type: 'POST',
            url: controller + '/' + method,
            async: false,
            data: { ClientID: data_id },
            dataType: 'html',
            beforeSend: function () {
                $('html, body').animate({
                    scrollTop: $("#add_edit_form").offset().top
                }, 2000);
                $('#display_update_form').html('<div class="loader_wrapper"><div class="loader"></div></div>');
                $('#add_edit_form').show();
            },
            success: function (returnData) {
                setTimeout(function () {
                    //$("#display_update_form").scrollTop();
                    $('#display_update_form').html(returnData);
                    $("select.select2").select2();
                }, 500);
                $('#display_update_form select').select2();
                $('.load_hide').hide();

            },
            error: function (xhr, textStatus, errorThrown) {
                $('#add_edit_form').slideUp(500, function () {
                    $('#display_update_form').html('');

                });
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
            }
        });

        return false;

    });
    $(document).on("click", ".btn.payment_details_form", function (event) {

        var data_id = $(this).attr('data-id');
        var user_id = $(this).attr('data-user-id');
        var bid_id = $(this).attr('data-bid-id');
        //        alert(user_id);
        var controller = $(this).attr('data-control');
        var method = $(this).attr('data-method');
        $.ajax({
            type: 'POST',
            url: BASE_URL + controller + '/' + method,
            async: false,
            data: { CarID: data_id, UserID: user_id, bid_id: bid_id },
            dataType: 'html',
            beforeSend: function () {
                $('html, body').animate({
                    scrollTop: $("#add_edit_form").offset().top
                }, 2000);
                $('#display_update_form').html('<div class="loader_wrapper"><div class="loader"></div></div>');
                $('#add_edit_form').show();
            },
            success: function (returnData) {
                setTimeout(function () {
                    //$("#display_update_form").scrollTop();
                    $('#display_update_form').html(returnData);
                    $("select.select2").select2();
                }, 500);
                $('#display_update_form select').select2();
                $('.load_hide').hide();

            },
            error: function (xhr, textStatus, errorThrown) {
                $('#add_edit_form').slideUp(500, function () {
                    $('#display_update_form').html('');

                });
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
            }
        });

        return false;

    });

    if ($('.event_car_datatable').length > 0) {
        var add_button = 0;
        var add_button_title = 'Add New';
        if (typeof $('.event_car_datatable').attr('data-add-button') != "undefined") {
            add_button = 1;
        }
        var $url = BASE_URL + $('.event_car_datatable').attr('data-control') + '/' + $('.event_car_datatable').attr('data-mathod');
        var oTable = $('.event_car_datatable').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "pageLength": 12,

            "sAjaxSource": $url,
            "sDom": "<'row'<'col-md-6'l <'toolbar'>><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
            "bLengthChange": false,
            "fnServerParams": function (aoData, fnCallback) {
                //                if ($('#BoardID').length > 0) {
                //                    aoData.push(  {"name": "BoardID", "value":  $('#BoardID').val() } );
                //                }
                if ($('#end_date').length > 0) {
                    aoData.push({ "name": "end_date", "value": $('#end_date').val() });
                }
                if ($('#start_date').length > 0) {
                    aoData.push({ "name": "start_date", "value": $('#start_date').val() });
                }
                if ($('#filter').length > 0) {
                    aoData.push({ "name": "filter", "value": $('#filter').val() });
                }
            },
            "fnInitComplete": function () {
                $('.tooltip-top a').tooltip();

                $('select').select2({
                    minimumResultsForSearch: -1
                });
                if (add_button == 1) {
                    var $controller = $('.event_car_datatable').attr('data-control');
                    var event_id = $('.event_car_datatable').attr('data-event-id');
                    $(".dataTables_wrapper .toolbar").html('<div class="table-tools-actions"><a class="btn btn-primary open_my_car_form" href="javascript:;" data-event-id="' + event_id + '" data-control="' + $controller + '">' + add_button_title + ' <i class="fa fa-plus"></i></a></div>');
                }
                $('.search_mq').on('change', function () {
                    oTable.fnDraw();
                });
                $('.date_filter').datepicker({
                    format: "yyyy-mm-dd",
                    startView: 1,
                    autoclose: true,
                    todayHighlight: true
                }).on('changeDate', function (ev) {
                    oTable.fnDraw();
                });
            },
            "fnDrawCallback": function () {
                $('.tooltip-top a').tooltip();

            },
            "oLanguage": { "sLengthMenu": "_MENU_ ", "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries" },
        });

    }

    $(document).on("click", ".btn.open_my_car_form", function (event) {

        var data_id = $(this).attr('data-id');
        var event_id = $(this).attr('data-event-id');
        var controller = $(this).attr('data-control');
        var $url = 'add';
        if (data_id > 0) {
            $url = 'edit/' + data_id;
        }
        $.ajax({
            type: 'POST',
            url: BASE_URL + controller + '/' + $url,
            async: false,
            data: { event_id: event_id },
            dataType: 'html',
            beforeSend: function () {
                $('html, body').animate({
                    scrollTop: $("#add_edit_form").offset().top
                }, 2000);
                $('#display_update_form').html('<div class="loader_wrapper"><div class="loader"></div></div>');
                $('#add_edit_form').show();
            },
            success: function (returnData) {
                setTimeout(function () {
                    //$("#display_update_form").scrollTop();
                    $('#display_update_form').html(returnData);
                    $("select.select2").select2();
                }, 500);

                //                if ($("#section_start_time").length > 0) {
                //                    $("#section_start_time").timepicker();
                //                    $("#section_end_time").timepicker();
                //                }

                //                $("#display_update_form :file").filestyle({buttonText: "&nbsp; Upload", buttonName: "btn-primary", placeholder: "No file selected", buttonBefore: true});

                $('#display_update_form select').select2();
                $('.load_hide').hide();

                //                $('.its_date_field').daterangepicker({
                //                    singleDatePicker: true,
                //                    format: 'DD-MM-YYYY',
                //                    calender_style: "picker_1"
                //                }, function (start, end, label) {
                //
                //                });

                //                $('.its_time_field').timepicker({
                //                    timeFormat: 'h:mm p',
                //                    interval: 05,
                //                    minTime: '6',
                //                    maxTime: '11:00 PM',
                ////                    defaultTime: '6',
                //                    startTime: '6:00 AM',
                //                    dynamic: false,
                //                    dropdown: true,
                //                    scrollbar: true
                //                });



            },
            error: function (xhr, textStatus, errorThrown) {
                $('#add_edit_form').slideUp(500, function () {
                    $('#display_update_form').html('');

                });
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
            }
        });

        return false;

    });

    $(document).on("click", ".cancel_button", function (event) {
        $('#add_edit_form').slideUp(500, function () {
            $('#display_update_form').html('');
        });
        return false;
    });

    $(document).on("submit", "form.default_form", function (event) {

        var formData = new FormData($(this)[0]);
        var formId = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,

            beforeSend: function () {
                $('.alert.alert-danger').slideUp(500).remove();
                $('input[type="submit"]').val('Please wait..!').attr('disabled', 'disabled');
            },
            success: function (returnData) {

                if (returnData.status == "ok") {
                    if (formId == 'car_image_frm') {
                        showSuccessCustom(returnData.message)
                    } else {

                        showSuccess(returnData.message);
                    }
                    if (formId == 'commission_frm') {
                        $('#add_edit_form').slideUp(500, function () {
                            $('#display_update_form').html('');
                        });
                    } else if (formId == 'user_rights_frm') {
                        $("#user_rights_details").modal('hide');

                    } else if (formId == 'bid_edit_frm' || formId == 'bulk_fullfill_frm' || formId == 'car_upload_excel_frm') {
                        setTimeout(function () {
                            window.location.reload();
                        }, 300);

                    } else if (formId == 'commission_popup_frm') {
                        $("#add_client_details").modal('hide');
                        setTimeout(function () {
                            get_client_commission_details($('.ClientID').val());
                            //            get_feedback_details($('.FeedbackID').val());
                        }, 500);
                    }
                    if (formId == 'buying_limit_frm') {
                        $('#' + formId).trigger("reset");
                        $('#user_buying_limit').trigger('click')
                    }
                    else if (formId == 'home_cms_frm' || formId == 'car_image_frm' || formId == 'car_noc_frm') {
                        if (formId == 'car_image_frm') {
                            if (returnData.file_error == 1) {
                                var error_html = '';
                                if (typeof returnData.error_in_folder != "undefined") {

                                    $.each(returnData.error_in_folder, function (idx, topic) {
                                        error_html += '<li>' + topic + '</li>';
                                    });
                                }
                                $('.error_div').html(error_html)
                            } else {
                                // setTimeout(function () {
                                //     window.location.reload();
                                // }, 300);
                            }

                        } else {
                            setTimeout(function () {
                                window.location.reload();
                            }, 300);
                        }
                    } else {
                        get_updated_datatable();
                    }

                } else {
                    var error_html = '';
                    if (formId == 'car_upload_frm' || formId == 'car_upload_excel_frm') {
                        if (returnData.invalidDataFlag == 1) {
                            var $url = BASE_URL + returnData.controller + '/downloadInvalidFile';
                            // window.location.href = $url;
                            window.open($url, '_blank');
                        } else {
                            $("#car_excel_log_div").html(returnData.logs);
                        }
                    }

                    if (typeof returnData.error != "undefined") {
                        $.each(returnData.error, function (idx, topic) {
                            error_html += '<li>' + topic + '</li>';
                        });
                    }
                    if (error_html != '') {
                        showErrorMessage(error_html);
                    } else {
                        let formattedMessage = returnData.message;
                        console.log(returnData.message);
                        // Add line break after each Gujarati word (or space-separated word)
                        if (/[^\x00-\x7F]+/.test(formattedMessage)) { // Detect non-ASCII text (e.g., Gujarati)
                            formattedMessage = formattedMessage.replace(/\s+/g, ' ');
                        }
                        console.log(formattedMessage);

                        if (formId == 'car_image_frm') {
                            showErrorMessageCustom(formattedMessage);
                        } else {
                            showErrorMessage(formattedMessage);
                        }
                    }
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
                $('input[type="submit"]').val('Submit').removeAttr('disabled');
                if (formId == 'buying_limit_frm') {
                    $('input[type="submit"]').val('Update Buying Limit').removeAttr('disabled');
                }
            }
        });

        return false;

    });

    $(document).on("click", ".btn.delete_btn", function (event) {

        $('a.remove_clicked').removeClass('remove_clicked')
        $(this).addClass('remove_clicked');
        var $ts = $(this);
        $.alert.open('confirm', 'Are you sure you want to delete this?', function (button) {
            if (button == 'yes') {
                $('form').attr('autocomplete', 'off');
                $.alert.open({
                    type: 'prompt',
                    title: 'Admin Password',
                    inputtype: 'password',
                    content: 'Please enter the password',
                    callback: function (pass_btn, value) {
                        if (pass_btn == 'ok') {

                            var data_id = $ts.attr('data-id');
                            var method = $ts.attr('data-method');
                            var $url = 'remove/' + method;
                            $.ajax({
                                type: 'POST',
                                url: $url,
                                async: false,
                                data: { id: data_id, pass: value },
                                dataType: 'json',
                                beforeSend: function () {
                                    $('.dataTables_processing').css('visibility', 'visible');
                                },
                                success: function (returnData) {
                                    if (returnData.status == 'ok') {
                                        $.alert.open({ type: 'info', content: returnData.message });
                                        $ts.closest("tr").remove();
                                        get_updated_datatable();
                                    } else {
                                        $.alert.open({ type: 'error', content: returnData.message });
                                    }
                                },
                                error: function (xhr, textStatus, errorThrown) {
                                    new PNotify({ title: 'Error', text: 'There was an unknown error that occurred. You will need to refresh the page to continue working.', type: 'error', styling: 'bootstrap3' });
                                },
                                complete: function () {
                                    $('.dataTables_processing').css('visibility', 'hidden');

                                }
                            });

                        }
                    }
                });


            }
        });
        return false;

    });
    $(document).on("click", ".btn.delete_car_image", function (event) {

        $('a.remove_clicked').removeClass('remove_clicked')
        $(this).addClass('remove_clicked');
        var $ts = $(this);
        var data_id = $ts.attr('data-id');
        var method = $ts.attr('data-method');
        var $url = 'car-images/' + method;
        $.ajax({
            type: 'POST',
            url: $url,
            async: false,
            data: { id: data_id },
            dataType: 'json',
            beforeSend: function () {
                $('.dataTables_processing').css('visibility', 'visible');
            },
            success: function (returnData) {
                if (returnData.status == 'ok') {
                    $.alert.open({ type: 'info', content: returnData.message });
                    $ts.closest("tr").remove();
                    get_updated_datatable();
                } else {
                    $.alert.open({ type: 'error', content: returnData.message });
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                new PNotify({ title: 'Error', text: 'There was an unknown error that occurred. You will need to refresh the page to continue working.', type: 'error', styling: 'bootstrap3' });
            },
            complete: function () {
                $('.dataTables_processing').css('visibility', 'hidden');

            }
        });
        return false;

    });

    $(document).on("click", ".btn.delete_client_commission", function (event) {

        var $ts = $(this);
        var r = confirm("Are you sure you want to delete this?");
        if (r == true) {
            var data_id = $ts.attr('data-id');
            var method = $ts.attr('data-method');
            var $url = 'client/' + method;
            $.ajax({
                type: 'POST',
                url: $url,
                async: false,
                data: { id: data_id },
                dataType: 'json',
                beforeSend: function () {
                    $('.dataTables_processing').css('visibility', 'visible');
                },
                success: function (returnData) {
                    if (returnData.status == 'ok') {
                        $ts.closest("tr").remove();
                        get_updated_datatable();
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    new PNotify({ title: 'Error', text: 'There was an unknown error that occurred. You will need to refresh the page to continue working.', type: 'error', styling: 'bootstrap3' });
                },
                complete: function () {
                    $('.dataTables_processing').css('visibility', 'hidden');

                }
            });
        }

        return false;

    });

    $(document).on("click", ".generate-transaction-report", function (event) {
        var $ts = $(this);
        var data_id = $ts.attr('data-id');
        var $url = BASE_URL + 'transaction-report/export_records/' + data_id;
        window.location.href = $url;
    });

    $(document).on("click", ".generate-acr-report", function (event) {
        var $ts = $(this);
        var data_id = $ts.attr('data-id');
        var $url = BASE_URL + 'auction/generate_acr_report';

        var $controller = 'auction';
        var data_id = $(this).attr('data-id');
        var $validation_error = '';


        //        var $param = 'start_date=' + $('#start_date').val();
        //        $param += '&end_date=' + $('#end_date').val();
        //        var $url = BASE_URL + $controller + '/loan_request_export/?' + $param;

        var $url = BASE_URL + 'auction/generate_acr_report/' + data_id;
        //            window.location.href = $controller + '/pdf-download/?'+$param;
        window.location.href = $url;

        // $.ajax({
        //     type: 'POST',
        //     url: $url,
        //     async: false,
        //     data: {auction_id: data_id},
        //     dataType: 'json',
        //     beforeSend: function () {
        //         $('.dataTables_processing').css('visibility', 'visible');
        //     },
        //     success: function (returnData) {
        //         if (returnData.status == "ok") {
        //             showSuccess(returnData.message);
        //             get_updated_datatable();
        //         } else {
        //             showErrorMessage(returnData.message);
        //         }

        //     },
        //     error: function (xhr, textStatus, errorThrown) {
        //         new PNotify({title: 'Error', text: 'There was an unknown error that occurred. You will need to refresh the page to continue working.', type: 'error', styling: 'bootstrap3'});
        //     },
        //     complete: function () {
        //         $('.dataTables_processing').css('visibility', 'hidden');

        //     }
        // });

        // return false;

    });

    $(document).on("click", ".generate-acr-admin-report", function (event) {
        var $ts = $(this);
        var data_id = $(this).attr('data-id');
        var $validation_error = '';
        var $url = BASE_URL + 'auction/generate_acr_admin_report/' + data_id;
        window.location.href = $url;
    });
    $(document).on("click", ".generate-acr-summery-report", function (event) {
        var $ts = $(this);
        var data_id = $(this).attr('data-id');
        var $validation_error = '';
        var $url = BASE_URL + 'auction/generate_acr_summery_report/' + data_id;
        window.location.href = $url;
    });
    $(document).on("click", ".overall_admin_acr_report", function (event) {
        var cnt = $("input[name='event_id[]']").length;
        var $tr = $(this);
        if ($('#que_select_all').is(':checked')) {
            $.ajax({
                type: 'POST',
                url: 'auction/generate_overall_acr_admin_report',
                //                async: false,
                dataType: 'json',
                data: $("#filter_auction_frm").serialize(),
                beforeSend: function () {
                    $("#custom .model_content_area").html("");
                    $tr.text('Processing...');
                    $tr.attr('disabled', 'disabled');
                },
                success: function (data) {
                    var $a = $("<a>");
                    $a.attr("href", data.file);
                    $("body").append($a);
                    $a.attr("download", data.filename);
                    $a[0].click();
                    $a.remove();


                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log()
                    toster_message('There was an unknown error that occurred. You will need to refresh the page to continue working.', 'Error', 'error');
                },
                complete: function () {
                    $tr.text('Admin');
                    $tr.removeAttr('disabled', 'disabled');
                }
            });
        } else {
            if (cnt == 0) {
                showErrorMessage('Select atleast one event.');
            } else {
                $.ajax({
                    type: 'POST',
                    url: 'auction/generate_overall_acr_admin_report',
                    //                async: false,
                    dataType: 'json',
                    data: $("#export_frm").serialize(),
                    beforeSend: function () {
                        $("#custom .model_content_area").html("");
                        $tr.text('Processing...');
                        $tr.attr('disabled', 'disabled');
                    },
                    success: function (data) {
                        var $a = $("<a>");
                        $a.attr("href", data.file);
                        $("body").append($a);
                        $a.attr("download", data.filename);
                        $a[0].click();
                        $a.remove();


                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log()
                        toster_message('There was an unknown error that occurred. You will need to refresh the page to continue working.', 'Error', 'error');
                    },
                    complete: function () {
                        $tr.text('Admin');
                        $tr.removeAttr('disabled', 'disabled');
                    }
                });
            }
        }


        // var $ts = $(this);
        // var data_id = $(this).attr('data-id');
        // var $validation_error = '';
        // var $url = BASE_URL + 'auction/generate_acr_summery_report/' + data_id;
        // window.location.href = $url;

    });
    $(document).on("click", ".overall_bank_acr_report", function (event) {
        var cnt = $("input[name='event_id[]']").length;
        if (cnt == 0) {
            showErrorMessage('Select atleast one event.');
        } else {
            $.ajax({
                type: 'POST',
                url: 'auction/generate_overall_acr_bank_report',
                //                async: false,
                dataType: 'json',
                data: $("#export_frm").serialize(),
                beforeSend: function () {
                    $("#custom .model_content_area").html("");
                },
                success: function (data) {
                    var $a = $("<a>");
                    $a.attr("href", data.file);
                    $("body").append($a);
                    $a.attr("download", data.filename);
                    $a[0].click();
                    $a.remove();


                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log()
                    toster_message('There was an unknown error that occurred. You will need to refresh the page to continue working.', 'Error', 'error');
                },
                complete: function () {

                }
            });
        }

    });
    $(document).on("click", ".overall_summary_acr_report", function (event) {
        var cnt = $("input[name='event_id[]']").length;
        if (cnt == 0) {
            showErrorMessage('Select atleast one event.');
        } else {
            $.ajax({
                type: 'POST',
                url: 'auction/generate_overall_acr_summary_report',
                //                async: false,
                dataType: 'json',
                data: $("#export_frm").serialize(),
                beforeSend: function () {
                    $("#custom .model_content_area").html("");
                },
                success: function (data) {
                    var $a = $("<a>");
                    $a.attr("href", data.file);
                    $("body").append($a);
                    $a.attr("download", data.filename);
                    $a[0].click();
                    $a.remove();


                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log()
                    toster_message('There was an unknown error that occurred. You will need to refresh the page to continue working.', 'Error', 'error');
                },
                complete: function () {

                }
            });
        }

    });
    $(document).on("click", "#user_buying_limit", function (event) {
        var user_id = $(".user_id").val();
        if (user_id == '') {
            showErrorMessage('Enter User ID.');
        } else {
            $.ajax({
                type: 'POST',
                url: BASE_URL + 'user-buying-limit/get_user_details',
                //                async: false,
                dataType: 'html',
                data: { user_id: user_id },
                beforeSend: function () {
                    $("#user_details").html("");
                    $(".alot_buying_limit").addClass("hidden");
                    $('.BuyingLimitUserID').val(user_id)
                },
                success: function (data) {
                    $(".alot_buying_limit").removeClass("hidden");
                    $('#user_details').html(data)
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log()
                    toster_message('There was an unknown error that occurred. You will need to refresh the page to continue working.', 'Error', 'error');
                },
                complete: function () {

                }
            });
        }

    });

    $(document).on("click", ".invoice_history_cancel_button", function (event) {
        $("#plan_history").modal('hide');
    });
    $(document).on("click", ".incentive_history_cancel_button", function (event) {
        $("#invoice_history").modal('hide');
    });
    $(document).on("click", ".view_subscription_history", function (event) {
        var data_id = $(this).attr('data-id');
        var controller = $(this).attr('data-control');
        var $url = 'view_history';
        if (data_id > 0) {
            $url = 'view_history/' + data_id;
        }
        $.ajax({
            type: 'POST',
            url: controller + '/' + $url,
            async: false,
            data: { pstdata: 1 },
            dataType: 'html',
            beforeSend: function () {
                $('#plan_history .model_content_area').html('');
            },
            success: function (returnData) {
                $("#plan_history").modal('show');
                $('#plan_history .model_content_area').html(returnData);
            },
            error: function (xhr, textStatus, errorThrown) {
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
            }
        });

        return false;
    });

    $(document).on("click", ".referal-link", function (event) {
        var data_id = $(this).attr('data-id');
        var controller = $(this).attr('data-method');
        var $url = 'referal_link';
        $.ajax({
            type: 'POST',
            url: controller + '/' + $url,
            async: false,
            data: { id: data_id },
            dataType: 'json',
            // cache: false,
            // contentType: false,
            // processData: false,
            beforeSend: function () {
            },
            success: function (returnData) {
                var elem = document.createElement("textarea");
                document.body.appendChild(elem);
                elem.value = returnData.data;
                elem.select();
                document.execCommand("copy");
                document.body.removeChild(elem);
                showSuccess('Link copied to clipboard successfully.');
            },
            error: function (xhr, textStatus, errorThrown) {
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
            }
        });

        return false;
    });


});

function get_updated_fullfilment_count() {
    //     if ($('.auctionID').length > 0) {
    //         aoData.push({ "name": "auctionID", "value": $('.auctionID').val() });
    //     }
    //     if ($('#buyerCode').length > 0) {
    //         aoData.push({ "name": "buyerCode", "value": $('#buyerCode').val() });
    //     }
    //     if ($('#LANnumber').length > 0) {
    //         aoData.push({ "name": "LANnumber", "value": $('#LANnumber').val() });
    //     }
    // if ($('#status').length > 0) {
    //         aoData.push({ "name": "status", "value": $('#status').val() });
    //     }
    // if ($('#sellerCode').length > 0) {
    //         aoData.push({ "name": "sellerCode", "value": $('#sellerCode').val() });
    //     }
    $.ajax({
        type: 'POST',
        url: BASE_URL + 'auction-bulk-fulfillment/get_updated_count',
        //                async: false,
        dataType: 'html',
        data: { auctionID: $('.auctionID').val(), buyerCode: $('#buyerCode').val(), LANnumber: $('#LANnumber').val(), status: $('#status').val(), sellerCode: $('#sellerCode').val() },
        beforeSend: function () {

        },
        success: function (data) {
            $('.update_count_div').html(data)
        },
        error: function (xhr, textStatus, errorThrown) {
            console.log()
            toster_message('There was an unknown error that occurred. You will need to refresh the page to continue working.', 'Error', 'error');
        },
        complete: function () {

        }
    });
}

function progress_bar_process(percentage, timer) {
    $('.progress-bar').css('width', percentage + '%');
    if (percentage > 100) {
        clearInterval(timer);
        $('#sample_form')[0].reset();
        $('#process').css('display', 'none');
        $('.progress-bar').css('width', '0%');
        $('#save').attr('disabled', false);
        $('#success_message').html("<div class='alert alert-success'>Data Saved</div>");
        setTimeout(function () {
            $('#success_message').html('');
        }, 5000);
    }
}
