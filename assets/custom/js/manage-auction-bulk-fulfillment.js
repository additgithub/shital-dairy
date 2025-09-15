$(document).ready(function () {

    var start_start = new Date();
    // set end date to max one year period:
    var start = new Date();
    var end = new Date(new Date().setYear(start.getFullYear() + 1));
    $('.start_Date').datetimepicker({
        startDate: start,
        endDate: end,
        format: 'dd-mm-yyyy HH:ii P',
        showMeridian: true,
        autoclose: true
    });
    $('.see_logs').click(function () {
        var BidID = $(this).data('id');
        if($('.see_logs_'+BidID).hasClass('hidden')){
            $(this).html('Hide Logs')
            $('.see_logs_'+BidID).removeClass('hidden')
        }else{
            $(this).html('See Logs')
            $('.see_logs_'+BidID).addClass('hidden')
        }
    });
    $('.cancel_bulk_fullfilment_button').click(function () {
        $('#fullfillment').modal('hide');
        $('#fullfillment .modal-body').html('');
        get_updated_datatable();
    });
    $('.bid_status').change(function () {
        var $BidID = $(this).data('id');
        if ($(this).val() == 'follow-up') {
            $('.tr_follow-up_' + $BidID).removeClass('hidden')
            $('.tr_dropped_' + $BidID).addClass('hidden')
            $('.tr_fulfill_' + $BidID).addClass('hidden')
        } else if ($(this).val() == 'dropped') {
            $('.tr_follow-up_' + $BidID).addClass('hidden')
            $('.tr_dropped_' + $BidID).removeClass('hidden')
            $('.tr_fulfill_' + $BidID).addClass('hidden')
        } else if ($(this).val() == 'fulfill') {
            $('.tr_follow-up_' + $BidID).addClass('hidden')
            $('.tr_dropped_' + $BidID).addClass('hidden')
            $('.tr_fulfill_' + $BidID).removeClass('hidden')
        } else {
            $('.tr_follow-up_' + $BidID).addClass('hidden')
            $('.tr_dropped_' + $BidID).addClass('hidden')
            $('.tr_fulfill_' + $BidID).addClass('hidden')
        }
    });

    $('.drop_reason').change(function () {
        var $BidID = $(this).data('id');
        if ($(this).val() == 'dispute') {
            $('.is_forfeit_' + $BidID).addClass('hidden');
        } else if ($(this).val() == 'backout') {
            $('.is_forfeit_' + $BidID).removeClass('hidden');
        } else {
            $('.is_forfeit_' + $BidID).addClass('hidden');
        }
    });

    $('.bid_chkbox').click(function () {
        var BidID = $(this).data('id');
        if ($(this).is(':checked')) {
            $('.status_' + BidID).removeAttr('disabled');
        } else {
            
            $('.tr_follow-up_' + BidID).addClass('hidden')
            $('.tr_dropped_' + BidID).addClass('hidden')
            $('.tr_fulfill_' + BidID).addClass('hidden')
            $('.status_'+BidID).select2("val", "");
            $('.status_' + BidID).attr('disabled', 'disabled');
        }
    })

});


