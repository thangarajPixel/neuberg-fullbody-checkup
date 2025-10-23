
// Preloader
  $(window).on('load', function() {
    if ($('#preloader').length) {
      $('#preloader').delay(100).fadeOut('slow', function() {
        $(this).remove();
      });
    }
  });

  $('.counter').countUp();

  // Back to top button
  $(window).scroll(function () {
    var scroll = $(window).scrollTop();
    //console.log(scroll);
    if (scroll >= 300) {
      //console.log('a');
      $(".back-to-top").addClass("scrollfixed");
      $(".footer-sticky").addClass("active");
    } else {
      //console.log('a');
      $(".back-to-top").removeClass("scrollfixed");
      $(".footer-sticky").removeClass("active");
    }
  });

$(window).scroll(function() {    
    var scroll = $(window).scrollTop();
     //console.log(scroll);
    if (scroll >= 300) {
        //console.log('a');
        $("header").addClass("scrollfixed");
        $("body").addClass("padd-top");
    } else {
        //console.log('a');
        $("header").removeClass("scrollfixed");
        $("body").removeClass("padd-top");
    }
});


$(document).ready(function(){
  $(".packages-content p").mCustomScrollbar({
    axis:"y",
    theme:"dark"
  });
  
});


$(document).ready(function(){
  $('#banner-carousel').slick({
  slidesToShow: 1,
  dots:true,
  arrows: false,
  autoplay: true,
  autoplaySpeed: 3000,
  speed: 1200,
  });
});

$(document).ready(function(){
  $('.testimonials-carousel').slick({
  slidesToShow: 3,
  dots:false,
  arrows: true,
  autoplay: true,
  autoplaySpeed: 3000,
  speed: 1200,
  centerMode: true,
  responsive: [
      {
        breakpoint: 1200,
        settings: {
          slidesToShow: 2,
        }
      },
      {
        breakpoint: 960,
        settings: {
          slidesToShow: 1,
        }
      }
    ]
  });
});


$(document).ready(function(){
  $('.popular-tests-box').slick({
  slidesToShow: 4,
  dots:false,
  arrows: true,
  autoplay: false,
  autoplaySpeed: 3000,
  speed: 1200,
  responsive: [
      {
        breakpoint: 1200,
        settings: {
          slidesToShow: 3,
        }
      },
      {
        breakpoint: 960,
        settings: {
          slidesToShow: 2,
        }
      },
         {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
        }
      }
    ]
  });
});


$(document).ready(function(){
  $('.health_package_slider').slick({
  slidesToShow: 3,
  dots:false,
  arrows: true,
  autoplay: true,
  autoplaySpeed: 3000,
  speed: 1200,
  responsive: [
      {
        breakpoint: 1200,
        settings: {
          slidesToShow: 2,
        }
      },
         {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
        }
      }
    ]
  });
});

