$(document).ready(function () {
     $("#month_year").datepicker({
        format: "M-yyyy",
        viewMode: "months", 
        minViewMode: "months"

//       }).on('changeDate', function(ev){
//        var selected = new Date(ev.format());
//        var minDate = new Date(selected.getFullYear(), selected.getMonth(), 1);
//        var maxDate =  new Date(selected.getFullYear(), selected.getMonth() + 1, 0);
//         $('#start_date').datepicker('option', 'minDate', minDate);
//         $('#end_date').datepicker('option', 'maxDate', maxDate);
//    });
    });
    $(document).on("click", "a.export-commission-report", function (event) {
        var $controller = 'commission-report';

        var $validation_error = '';


//alert($('#PlanID').val());
        var $param = 'Client=' + $('#ClientID').val();
        $param += '&Event=' + $('#EventID').val();

        if ($validation_error != "") {
            toster_message_error($validation_error, 'Error', 'error');
        } else {
            var $url = BASE_URL + $controller + '/export-records/?' + $param;
//            window.location.href = $controller + '/pdf-download/?'+$param;
//alert($url);
            window.location.href = $url;
//            window.open($url, '_blank');
        }

    });
    if ($('.common_report_datatable').length > 0) {
        var add_button = 0;
        var add_button_title = 'Add New';
        if (typeof $('.common_report_datatable').attr('data-add-button') != "undefined") {
            add_button = 1;
        }
        var DisplayLength = 10;
        if($('.common_report_datatable').attr('data-control') == 'sr-business-report'){
            DisplayLength = 50;
        }
//        var client=$('#ClientID').val();
//            alert(client);
        var $url = BASE_URL + $('.common_report_datatable').attr('data-control') + '/' + $('.common_report_datatable').attr('data-mathod');
        var oTable = $('.common_report_datatable').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "searching": false,
            "sServerMethod": "POST",
            "sAjaxSource": $url,
            "sDom": "<'row'<'col-md-6'l <'toolbar'>><'col-md-6'f>r>t<'row'<'col-md-12'p i>>",
            "bLengthChange": false,
            "iDisplayLength":DisplayLength,
            "fnServerParams": function (aoData, fnCallback) {
                if ($('#ClientID').length > 0) {
                    aoData.push(  {"name": "Client", "value":  $('#ClientID').val() } );
                }
                if ($('#month_year').length > 0) {
                    aoData.push({"name": "month_year", "value": $('#month_year').val()});
                }
                if ($('#filter').length > 0) {
                    aoData.push(  {"name": "filter", "value":  $('#filter').val() } );
                }
                if ($('#EventID').length > 0) {
                    aoData.push(  {"name": "Event", "value":  $('#EventID').val() } );
                }
                if ($('#start_date').length > 0) {
                    aoData.push({"name": "start_date", "value": $('#start_date').val()});
                }
                if ($('#end_date').length > 0) {
                    aoData.push({"name": "end_date", "value": $('#end_date').val()});
                }
                if ($('#status').length > 0) {
                    aoData.push({"name": "status", "value": $('#status').val()});
                }
                if ($('#FilterBuyerNameList').length > 0) {
                    aoData.push({"name": "buyer_id", "value": $('#FilterBuyerNameList').val()});
                }
                if ($('#payment_status').length > 0) {
                    aoData.push({"name": "payment_status", "value": $('#payment_status').val()});
                }
                if ($('#source_type').length > 0) {
                    aoData.push({"name": "source_type", "value": $('#source_type').val()});
                }
                if ($('#business_rpt_date').length > 0) {
                    aoData.push({"name": "selected_date", "value": $('#business_rpt_date').val()});
                }
            },
            
            "fnInitComplete": function () {
                $('.tooltip-top a').tooltip();
                $('select').select2({
                });
                if (add_button == 1) {
                    var $controller = $('.common_report_datatable').attr('data-control');
                    $(".dataTables_wrapper .toolbar").html('<div class="table-tools-actions"><a class="btn btn-primary open_my_product_detail_form" href="javascript:;" data-control="' + $controller + '">' + add_button_title + ' <i class="fa fa-plus"></i></a></div>');
                }
                $('#source_type').on('change', function () {
                    oTable.fnDraw();
                });
                $('.search_mq').on('change', function () {
                    oTable.fnDraw();
                });
                $('#month_year').change(function() {
                    oTable.fnDraw();
                });
                $('#ClientID').change(function() {
                    oTable.fnDraw();
                });
                $('#EventID').change(function() {
                    oTable.fnDraw();
                });
                $('.business_rpt_filter').datepicker({
                    format: "dd-mm-yyyy",
                    startView: 1,
                    autoclose: true,
                    todayHighlight: true,
                    endDate: new Date(),
                }).on('changeDate', function (ev) {
                    oTable.fnDraw();
                });
            },

            "fnDrawCallback": function () {
                if($('.common_report_datatable').attr('data-control') == 'sr-business-report'){
                    get_sbr_total_row();
                }
            },
            "oLanguage": {"sLengthMenu": "_MENU_ ", "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"},
        });

    }

    function get_sbr_total_row(){
        var date = $('#business_rpt_date').val();
        var type = $('#source_type').val();
        $.ajax({
            type: 'POST',
            url: BASE_URL + $('.common_report_datatable').attr('data-control') + '/total_row',
            async: false,
            data: {date:date, type:type},
            dataType: 'json',
            success: function (returnData) {
                $(".common_report_datatable").append(returnData.data);
            }
        });
   }
   
    
    $(document).on("click", ".view_order_detail", function (event) {
        var data_id = $(this).attr('data-id');
        var controller = $(this).attr('data-control');
        var product_id = $('.product_id').val();
        var $url = 'add_product_details';
        if (data_id > 0) {
            $url = 'edit_product_details/' + data_id;
        }
        $.ajax({
            type: 'POST',
            url: BASE_URL + controller + '/' + $url,
            async: false,
            data: {pstdata:1, product_id:product_id},
            dataType: 'html',
            beforeSend: function () {
//                $('#display_update_form').html('<div class="loader_wrapper"><div class="loader"></div></div>');
//                $('#add_edit_form').show();
//                $("#product_details .close").click()
            },
            success: function (returnData) {
                    $('#product_details').modal('hide')
                setTimeout(function () {
                        $("#add_product_details").modal('show');
                    $('#add_product_details .model_content_area').html(returnData);
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

    $("#sbr_Input").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".sbr_tbl tbody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});