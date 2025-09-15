$(document).ready(function () {
    if ($('.exiry_p_tag').length > 0) {
        alert('hi');
        $(".exiry_p_tag").each(function (index) {
            // Set the date we're counting down to
            var EndTime = $(this).data('expiry-date');
            alert(EndTime)
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
                    document.getElementById("demo").innerHTML = days + "d " + hours + "h " +
                        minutes + "m " + seconds + "s ";
                }

                if (minutes <= 30 && hours == 0 && days == 0) {
                    $(this).removeClass('hidden')
                    // $('.event_h4').addClass('h4_event')
                    // document.getElementById("expiry_demo").innerHTML = minutes + ":" + seconds;
                    $(this).html(minutes + ":" + seconds)

                } else {
                    $(this).addClass('hidden')
                    // $('.exiry_p_tag').addClass('hidden')

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
            console.log(index + ": " + $(this).text());
        });
    }
});