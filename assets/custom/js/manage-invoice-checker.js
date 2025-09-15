$(document).ready(function () {

    /**
     * Auther : Ramiz Girach
     * Invoice History Popup Close on close icon of popup 06-02-2023 Ramiz
     */
    $(document).on("click", ".invoice_history_cancel_button", function (event) {
        $("#invoice_history").modal('hide');
    });

    /**
     * Auther : Ramiz Girach
     * Initializer datatable for invoice maker 23-01-2023 Ramiz
     */
    if ($('.invoice_datatable').length > 0) {
        var add_button = 0;
        var add_button_title = 'Add New';
        if (typeof $('.invoice_datatable').attr('data-add-button') != "undefined") {
            add_button = 1;
        }
        var $url = BASE_URL + $('.invoice_datatable').attr('data-control') + '/' + $('.invoice_datatable').attr('data-mathod');
        var $tableID = $('.invoice_datatable').attr('id');
        var sortable = [];
        if ($tableID == 'auction_table') {
            sortable = [{ "bSortable": false, "bSearchable": false }, { "bSortable": true, "bSearchable": false }, { "bSortable": true, "bSearchable": false }, { "bSortable": false, "bSearchable": false }, { "bSortable": false, "bSearchable": false }, { "bSortable": false, "bSearchable": false }, { "bSortable": false, "bSearchable": false },{ "bSortable": false, "bSearchable": false }]

        }

        var oTable = $('.invoice_datatable').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "pageLength": 12,
            scrollY: 200,
            scrollX: true,
            "sAjaxSource": $url,
            "aoColumns": sortable,
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
                    var $controller = $('.invoice_datatable').attr('data-control');
                    $(".dataTables_wrapper .toolbar").html('<div class="table-tools-actions"><a class="btn btn-primary " href="' + $controller + '/add" data-control="' + $controller + '">' + add_button_title + ' <i class="fa fa-plus"></i></a></div>');
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
    /**
     * Auther : Ramiz Girach
     * Initializer datatable for add invoice maker 23-01-2023 Ramiz
     */
    if ($('.invoice_add_datatable').length > 0) {
        var add_button = 0;
        var add_button_title = 'Add New';
        if (typeof $('.invoice_add_datatable').attr('data-add-button') != "undefined") {
            add_button = 1;
        }
        var $url = BASE_URL + $('.invoice_add_datatable').attr('data-control') + '/' + $('.invoice_add_datatable').attr('data-mathod');
        var $tableID = $('.invoice_add_datatable').attr('id');
        var sortable = [];
        sortable = [{ "bSortable": false, "bSearchable": false }, { "bSortable": true, "bSearchable": false }, { "bSortable": false, "bSearchable": false }, { "bSortable": false, "bSearchable": false }, { "bSortable": false, "bSearchable": false }, { "bSortable": false, "bSearchable": false }, { "bSortable": false, "bSearchable": false }, { "bSortable": false, "bSearchable": false }, { "bSortable": false, "bSearchable": false }]
        if ($tableID == 'auction_table') {


        }

        var oTable = $('.invoice_add_datatable').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "POST",
            "pageLength": 12,
            scrollY: 200,
            scrollX: true,
            "sAjaxSource": $url,
            "aoColumns": sortable,
            "sDom": "<'row'<'col-md-6'l <'toolbar'>><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
            "bLengthChange": false,
            "fnServerParams": function (aoData, fnCallback) {
                //                if ($('#BoardID').length > 0) {
                //                    aoData.push(  {"name": "BoardID", "value":  $('#BoardID').val() } );
                //                }

                if ($('#Bank').length > 0) {
                    aoData.push({ "name": "Bank", "value": $('#Bank').val() });
                }
                if ($('#State').length > 0) {
                    aoData.push({ "name": "State", "value": $('#State').val() });
                }
                if ($('#MakerID').length > 0) {
                    aoData.push({ "name": "MakerID", "value": $('#MakerID').val() });
                }
                if ($('#que_select_all').length > 0 && $('#que_select_all').is(':checked')) {
                    aoData.push({ "name": "que_select_all", "value": $('#que_select_all').val() });
                }
                if ($("input[name='car_id[]']").length > 0) {
                    var car_ids = $("input[name='car_id[]']").map(function () {
                        return $(this).val();
                    }).get().join(",");
                    aoData.push({
                        "name": "car_ids",
                        "value": car_ids
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
                    var $controller = $('.invoice_datatable').attr('data-control');
                    $(".dataTables_wrapper .toolbar").html('<div class="table-tools-actions"><a class="btn btn-primary " href="' + $controller + '/add" data-control="' + $controller + '">' + add_button_title + ' <i class="fa fa-plus"></i></a></div>');
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
                $(".invoice_id_chk").unbind("change");
                //After Refresh table id check all checked then all checkbox checked

                if ($('#car_select_all').is(":checked")) {
                    $('.invoice_id_chk').each(function () {
                        this.checked = true;
                    });
                } else {

                }
                $(".invoice_id_chk").change(function () {
                    var id = $(this).val();
                    if ($(this).is(':checked')) {
                        var html = '<div id="invoice_maker_id_' + id + '">' +
                            '<input type="hidden" name="car_id[]" value="' + id + '">' +
                            '</div>';
                        $("#selected_car_div").append(html);

                        // $("#rgp_" + id).parent().parent().css("background-color", '#d8dcde');

                    } else {
                        $('.merge_paper_div').addClass('hidden');
                        $("#invoice_maker_id_" + id).remove();
                        // $("#rgp_" + id).parent().parent().css("background-color", '');
                    }
                });

            },
            "oLanguage": { "sLengthMenu": "_MENU_ ", "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries" },
        });

    }


    /**
    * Auther : Ramiz Girach
    * Approved Invoice checker 03-02-2023 Ramiz
    */
    $(document).on("click", ".approved_invoice", function (event) {

        var $invoice_id = $(this).data('id');
        var $status = $(this).data('status');
        var $reason = $('.reason').val();
        $.ajax({
            type: 'POST',
            url: $(this).attr('data-action'),
            data: { invoice_id: $invoice_id, status: $status, reason: $reason },
            dataType: 'json',
            beforeSend: function () {
                $('.alert.alert-danger').slideUp(500).remove();
                $('.save_invoice').html('Please wait..!').attr('disabled', 'disabled');
            },
            success: function (returnData) {

                if (returnData.status == "ok") {
                    showSuccess(returnData.message);
                    //View Invoice Button href set
                    setTimeout(function () {
                        window.location.href = BASE_URL + 'invoice-checker';
                    }, 2000);

                } else {
                    var error_html = '';
                    if (typeof returnData.error != "undefined") {
                        $.each(returnData.error, function (idx, topic) {
                            error_html += '<li>' + topic + '</li>';
                        });
                    }
                    if (error_html != '') {
                        showErrorMessage(error_html);
                    } else {
                        showErrorMessage(returnData.message);
                    }
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
                $('.save_invoice').html('Save').removeAttr('disabled');
            }
        });

        return false;

    });

    /**
    * Auther : Ramiz Girach
    * Send For Correction Invoice checker 03-02-2023 Ramiz
    */
    $(document).on("click", ".send_for_correction_invoice", function (event) {

        var $invoice_id = $(this).data('id');
        var $status = $(this).data('status');
        var $reason = $('.reason').val();
        $.ajax({
            type: 'POST',
            url: $(this).attr('data-action'),
            data: { invoice_id: $invoice_id, status: $status, reason: $reason },
            dataType: 'json',
            beforeSend: function () {
                $('.alert.alert-danger').slideUp(500).remove();
                $('.save_invoice').html('Please wait..!').attr('disabled', 'disabled');
            },
            success: function (returnData) {

                if (returnData.status == "ok") {
                    showSuccess(returnData.message);
                    //View Invoice Button href set
                    setTimeout(function () {
                        window.location.href = BASE_URL + 'invoice-checker';
                    }, 2000);

                } else {
                    var error_html = '';
                    if (typeof returnData.error != "undefined") {
                        $.each(returnData.error, function (idx, topic) {
                            error_html += '<li>' + topic + '</li>';
                        });
                    }
                    if (error_html != '') {
                        showErrorMessage(error_html);
                    } else {
                        showErrorMessage(returnData.message);
                    }
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
            },
            complete: function () {
                $('.save_invoice').html('Save').removeAttr('disabled');
            }
        });

        return false;

    });

    $(document).on("click", ".view_status_history", function (event) {
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


    /**
   * Auther : Ramiz Girach
   * Download Only Loat Wise Vehicles Excel 06-02-2023 Ramiz
   */
    $(document).on("click", ".export_bank_invoice_btn", function (event) {

        var $maker_id = $(this).data('id')
        if ($maker_id != '') {
            $.ajax({
                type: 'POST',
                url: BASE_URL + 'invoice-checker/export-records',
                data: { maker_id: $maker_id },
                dataType: 'json',
                beforeSend: function () {
                    $('.export_bank_invoice_btn').html('Please Wait...').attr('disabled', 'disabled')
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
                    showErrorMessage('There was an unknown error that occurred. You will need to refresh the page to continue working.');
                },
                complete: function () {
                    $('.export_bank_invoice_btn').html('Export').removeAttr('disabled')
                }
            });

            return false;

        } else {
            showErrorMessage('Invoice Not Found');
        }

    });


});