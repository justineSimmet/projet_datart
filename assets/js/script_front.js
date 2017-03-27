/*-----------------------------------------------------------------------------
ACTIONS SUR LE BOUTON LANGUE
------------------------------------------------------------------------------*/
$(document).ready(function(){
	
  $(".dropdown").on("hide.bs.dropdown", function(){
    $(".btn").html('Dropdown');
  });
  $(".dropdown").on("show.bs.dropdown", function(){
    $(".btn").html('Dropdown');
  });
});



$("body").scroll(function(){
    $(".container").animate({
        height: 'toggle'
    });
});  

/*var positionElementInPage = $(".nav").offset().top;
$(window).scroll(
	function() {
		if ($(window).scrollTop() &gt;= positionElementInPage) {
			// fixed
			$(".navbar_container").addClass("floatable");
		} else {
			// relative
			$(".navbar_container").removeClass("floatable");
		}
	}
);*/