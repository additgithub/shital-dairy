$(document).ready(function () {
    var clear_timer;
    $(document).on("submit", "form.car_image_frm", function (event) {
        var formData = new FormData($(this)[0]);
        var formId = $(this).attr('id');
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#file-progress-bar").width('0%');
                $('.alert.alert-danger').slideUp(500).remove();
                $('input[type="submit"]').val('Please wait..!').attr('disabled', 'disabled');
                $('#process').css('display', 'block');
            },
            success: function (returnData) {

                if (returnData.status == "ok") {
                    $('#total_data').text(returnData.total_line);
                    $('.file_name').val(returnData.file_name);
                    $('.location').val(returnData.location);
                    $('.uploaded_zip').val(returnData.uploaded_zip);

                    start_import();
                    console.log('Start Import - 26');
                    clear_timer = setInterval(get_import_data, 2000);
                } else {
                    var error_html = '';
                    if (formId == 'car_upload_frm') {
                        $("#car_excel_log_div").html(returnData.logs);
                    }

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
                $('input[type="submit"]').val('Submit').removeAttr('disabled');
                if (formId == 'buying_limit_frm') {
                    $('input[type="submit"]').val('Update Buying Limit').removeAttr('disabled');
                }
            }
        });

        return false;

    });
});


function start_import() {
    // var formData = new FormData($('#car_image_frm')[0]);
    var formData = new FormData($('#car_image_frm')[0]);
    $('#process').css('display', 'block');
    $.ajax({
        url: BASE_URL + "car-images/upload_images",
        type: 'POST',
        data: formData,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        success: function () {
            clearInterval(clear_timer);
            $('#process').css('display', 'none');
            $('#file').val('');
            $('#message').html('<div class="alert alert-success">Data Successfully Imported</div>');
            $('#import').attr('disabled', false);
            $('#import').val('Import');
        }
    })
}

function get_import_data() {
    $.ajax({
        url: BASE_URL + "car-images/get_process",
        success: function (data) {
            var total_data = $('#total_data').text();
            var width = Math.round((data.processed_files_count / total_data) * 100);
            $('#process_data').text(data.processed_files_count);
            $('.progress-bar').css('width', width + '%');
            if (width >= 100) {
                clearInterval(clear_timer);
                $('#process').css('display', 'none');
                $('#file').val('');
                $('#message').html('<div class="alert alert-success">Data Successfully Imported</div>');
                $('#import').attr('disabled', false);
                $('#import').val('Import');
            }
        }
    })
}