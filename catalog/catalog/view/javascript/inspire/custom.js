
 /* loader */
$(document).ready(function(){
    var o = $('#page-preloader');
    if (o.length > 0) {
        $(window).on('load', function() {
            $('#page-preloader').removeClass('visible');
        });
    }
});

//go to top
$(document).ready(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('#scroll').fadeIn();
        } else {
            $('#scroll').fadeOut();
        }
    });
    $('#scroll').click(function () {
        $("html, body").animate({scrollTop: 0}, 600);
        return false;
    });
});


/* responsive menu */
 function openNav() {
    $('body').addClass("active");
    document.getElementById("mySidenav").style.width = "250px";
    jquery('#mySidenav').addCss("display","block");
}
function closeNav() {
    $('body').removeClass("active");
    document.getElementById("mySidenav").style.width = "0";
    jquery('#mySidenav').removeCss("display","none");
}

// /* left column responsive */
// function wbFilters(){

//   if ($(window).width() <= 767) {
//     $('#column-left').appendTo('#content');
//   } else {
//     $('#column-left').appendTo('#column-left');
//   }
// }
// $(document).ready(function(){ isFilters(); });
// $(window).resize(function(){ isFilters(); });

/* pageloader */
$(window).load(function() {
  $("#loading").delay(500).fadeOut(500);
  $("#loading-center").click(function() {
  $("#loading").fadeOut(500);
  })
})


