$(document).ready(function () {
    $(document).on("click", ".btn.open_rights_form", function (event) {

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
            $url = 'rights_form/' + data_id;
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

});
