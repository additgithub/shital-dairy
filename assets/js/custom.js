 $(document).ready(function () 
 {     


    $(window).load(function(){
        $('#header .header-block').tmStickUp({});  
    }); 

    /***** responsive menu ********/    
    $("#nav a.menu-icon").click(function(e) 
    {
          e.preventDefault();
          $("#nav > ul").slideToggle(300);
    });
    if($(window).width() <= 768)
    {
         $("#nav ul li").click(function() 
        { 
             if ($(this).hasClass('drop-down')){
               
            } else {
                $("#nav ul").fadeOut(300);
            }      
          
         });
    }

  
    // if($(window).width() <= 768)
    // {
    //     $("#nav ul li.drop-down").click(function(dropdown) 
    //     {
    //         dropdown.preventDefault();
    //           $("#nav ul.dropdown-menu").fadeOut(300);
    //          $(this + 'ul').fadeIn(300);
    //     });
        
    // }




    /*$(window).scroll(function() 
    {    
        var scroll = $(window).scrollTop();

        if (scroll >= 300) 
        {
            $(".header-block").addClass("isStuck");
            $(".header-block").addClass("fadeInDown");
            $(".header-block").addClass("wow");
            $(".header-block").addClass("animated");
        } 
        else
        {
            $(".header-block").removeClass("isStuck");
            $(".header-block").removeClass("fadeInDown");
            $(".header-block").removeClass("wow");
            $(".header-block").removeClass("animated");
        }
        
    });*/


    $('.bxslider').bxSlider({
        auto: true,
        pager: false,
        autoControls: false,
        speed: 1500,
        mode: 'fade',
        autoHover: true
    });

    $(".owl-carousel").owlCarousel(
     {
        items : 3,
        lazyLoad : true,        
        loop: true,
        autoplay:true,
        autoplayTimeout:3000,
        autoplayHoverPause:true,
        dots: true,
        animateIn: 'fadeIn',
        animateOut: 'fadeOut',
       /* autoHeight : true,*/
        nav : true,
        navText : ["<div class='left-navigation'></div>", "<div class='right-navigation'></div>"],
        responsiveClass:true,
        responsive:
        {
            0:{
                items:1
            },
            640:{
                items:1
            },
            641:{
                items:1
            },  
            1024:{
                items:3
            } 
        }      
    });

    
});

