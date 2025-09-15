$(document).ready(function() {
    $(document).on("click", "#add_another_btn", function (event) {
        var count = $('#ExtraValue').val();
        var controller = $(this).attr('data-method');
        $.ajax({
            type: 'POST',
            url: controller+'/add_extra_location',
            async: false,
            data: {count:count},
            dataType: 'json',
            success: function (returnData) {
                if (returnData.status == "ok") {
                    $('#ExtraValue').attr('value',returnData.count);
                    $('.location_extra').append(returnData.data);
                    
                    $(".tag-select2").select2({
                        placeholder: function () {
                            $(this).data('placeholder');
                        },
                        tags: true
                    });
                    // $("select.select2").select2({});
                    $('.js-data-example-ajax').select2({
                        placeholder: 'select city/state',
                        minimumInputLength: 5,
                        ajax: {
                            url: BASE_URL + 'yard/get_pincode',
                            dataType: 'json',
                            delay: 250,
                            processResults: function (data) {
                                console.log("data", data);
                                return {
                                    results: data.results
                                };
                            },
                            // cache: true
                        }
                    });

                    $('select.select2').select2();
                }
            },
        });
        return false;
    });

    $(document).on("click","#delete_location_row",function (event) {
        // alert();
        var id = $(this).attr('data-id');
        $('.'+id).remove();
    });

    $(document).on("change","#fileinfo",function (event) {
        var formData = new FormData($('.default_form')[0]);
        // console.log(formData);
        $.ajax({
            type: 'POST',
            url: BASE_URL + 'yard/upload_pan',
            data: formData,
            dataType: 'json',
            cache : false,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('.upload_pan_span').html('Uploading...')
            },
            success: function (returnData) {
                if (returnData.status == "ok") {
                    $('#view_pan').attr('href',returnData.fullurl).show();

                    $('#pan_url').attr('value',returnData.filename);
                    $('.upload_pan_span').addClass('text-success')
                    $('.upload_pan_span').html('PAN Uploaded')
                    showSuccess(returnData.message);
                } else {
                    showErrorMessage(returnData.message);
                }
            },
            // error: function (xhr, textStatus, errorThrown) {
            //     toster_message('There was an unknown error that occurred. You will need to refresh the page to continue working.', 'error', 'error');
            // },
            complete: function () {
                // $('input[type="submit"]').val('Submit').removeAttr('disabled');
            }
        });
    });

    $(document).on("click",'.verify_pan',function (event) {
        var Panno = $('.pan_textbox').val();
        $('.verify_pan').attr('disabled','disabled');
        $.ajax({
            type: 'POST',
            url: BASE_URL+'yard/verify_pan',
            data: {Panno: Panno},
            async: false,
            dataType: 'json',
            beforeSend: function () {
                $('#pan_verify').val('Please wait..!').attr('disabled', 'disabled');
            },
            success: function (returnData) {
                if (returnData.status == "ok") {
                    $('.PANNumber').attr('value',returnData.PanNumber);
                    $('#PanName').attr('value',returnData.name);
                    $('#Response').attr('value',returnData.response);
                    showSuccess(returnData.message);
                    $('#pan_verify').addClass('text-success');
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
                    $('#final_panno').attr('value','');
                    $('#pan_verify').text('Verify');
                }
            }
        });
    });

    $(document).on("click","#generate_otp",function (event) {
        var MobileNo = $('#MobileNo').val();
        var OwnerName = $('#OwnerName').val();
        $('#generate_otp').attr('disabled','disabled');
        $.ajax({
            type: 'POST',
            url: BASE_URL+'yard/send_otp',
            data: {MobileNo: MobileNo,OwnerName:OwnerName},
            async: false,
            dataType: 'json',
            beforeSend: function () {
                $('#pan_verify').val('Please wait..!').attr('disabled', 'disabled');
            },
            success: function (returnData) {
                if (returnData.status == "ok") {
                    $('#Generate_otp').attr('value',returnData.OTP);
                    showSuccess(returnData.message);
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
                    $('#final_panno').attr('value','');
                    $('#pan_verify').text('Verify');
                }
            }
        });
    });

    $(document).on("change", ".Pincode", function (event) {
        var data_id = $(this).attr('data-id');
        var PincodeID = $(this).val();
        $.ajax({
            type: 'POST',
            url: BASE_URL + 'yard/get_pincode_state',
            data: { PincodeID: PincodeID },
            dataType: 'json',
            success: function (returnData) {
                if (returnData.status == "ok") {
                    $('#State'+data_id).html(returnData.state);
                    $('#City'+data_id).html(returnData.city);
                }
            },
            // error: function (xhr, textStatus, errorThrown) {
            //     toster_message('There was an unknown error that occurred. You will need to refresh the page to continue working.', 'error', 'error');
            // },
            complete: function () {
                // $('input[type="submit"]').val('Submit').removeAttr('disabled');
            }
        });
    });

     $(document).on("change", ".yard_banner_feature", function (event) {
        // alert();
        var data_id = $(this).attr('data-id');

        var controller = $(this).attr('data-control');

        var base_url = $(this).attr('data-url');
        if (data_id > 0) {
            $url = 'activated/' + data_id;
        }
        $.ajax({
            type: 'POST',
            url: base_url+controller + '/' + $url,
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
});