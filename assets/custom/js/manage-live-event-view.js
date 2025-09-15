$(document).ready(function () {
    if ($('.load_car_div').length > 0) {
        console.log(localStorage.getItem("activePage"), 'localStorage.getItem("activePage")')
        // filter_car(1);
        if (localStorage.getItem("activePage") === null) {
            localStorage.setItem('activePage', 1);
            filter_car(localStorage.getItem("activePage"))
        } else {
            filter_car(localStorage.getItem("activePage"))
        }
        $('#pagination-container').on('click', 'a', function (e) {
            e.preventDefault();
            var pageno = $(this).attr('data-ci-pagination-page');
            filter_car(pageno);

            var whsame = $('.whsame').width();
            $('.whsame').css({ 'height': whsame + 'px' });
        });
    }
});


function filter_car(pagno = 0, display) {

    var event_id = $('#event_id').val();
    var search_txt_val = $('.live_auction_search').val();
    var MakeID = $('#MakeID').val();
    var Model = $('#ModelID').val();
    var MfgYear = $('#MfgYear').val();
    //    alert(event_id);
    $.ajax({
        type: 'POST',
        url: ADMIN_URL + "live_event_view/filter_car/" + pagno,
        data: { event_id: event_id, search_txt_val: search_txt_val, MakeID: MakeID, Model: Model, MfgYearPar: MfgYear },
        dataType: 'json',
        async: false,
        // cache: false,
        // contentType: false,
        // processData: false,
        beforeSend: function () {
            $(".loader").css('display', 'block');
            $('body').css('opacity', '0.7');

        },
        success: function (response) {
            //console.log("response",response);
            // $('.product_listing').html('test');
            $('.load_car_div').html(response.html);
            $('#pagination-container').html("<p> " + response.links + "</p>");

            setTimeout(function () {

                // if($('.product-large-slider').length){
                $('.product-large-slider').slick({
                    fade: true,
                    arrows: true,
                    asNavFor: '.pro-nav',
                    autoplay: true,
                    autoplaySpeed: 2000,
                    arrows: false,
                    slidesToShow: 1,
                    slidesToScroll: 1
                });

                // product details slider nav active
                $('.pro-nav').slick({
                    slidesToShow: 4,
                    asNavFor: '.product-large-slider',
                    centerMode: false,
                    centerPadding: 0,
                    focusOnSelect: true,
                    vertical: false,
                    autoplay: true,
                    autoplaySpeed: 2000,
                    arrows: false,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-up"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-down"></i></button>',
                    responsive: [{
                        breakpoint: 500,
                        settings: {
                            slidesToShow: 3,
                        }
                    }]
                });
                $('.img-zoom').zoom();
                // }
            }, 500);


            var whsame = $('.whsame').width();
            $('.whsame').css({ 'height': whsame + 'px' });
        },
        error: function (xhr, textStatus, errorThrown) {
            // toster_message('There was an unknown error that occurred. You will need to refresh the page to continue working.');
        },
        complete: function () {
            $(".loader").css('display', 'none');
            $('body').css('opacity', '1');


        }
    });
}