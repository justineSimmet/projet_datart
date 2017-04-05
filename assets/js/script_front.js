/*-----------------------------------------------------------------------------
ACTIONS SUR LE BOUTON LANGUE
------------------------------------------------------------------------------*/
$(document).ready(function(){
	
  $(".dropdown").on("hide.bs.dropdown", function(){
    $(".btn").html;
  });
  $(".dropdown").on("show.bs.dropdown", function(){
    $(".btn").html;
  });
});


/*-----------------------------------------------------------------------------
qd clique sur la langue change la langue
------------------------------------------------------------------------------*/

// var langDispo = []
// $(window).onclick(function(){

// });

$("#changeLang li").on("click", function(){
 
    // On récupère le code de la langue choisie par l'utilisateur
    var lang = $(this).attr("data-langue");
 
    // On fait appel à AJAX pour changer de langue
    $.ajax({
        method: "POST", // Le type d'envoie, dans ce cas POST
        url: "lang_management.php", // La page qui vas recevoir l'envoie
        data: {language: lang}, // Les données à envoyer (la langue choisie)
        success: function(){
            // Si la requète a été envoyé avec succès, on recharge la page
            window.location.reload();
        }
    });
 
});

/*-----------------------------------------------------------------------------
Quand scrolldown nav disparait et qd scrollup reapparait 
------------------------------------------------------------------------------*/

;(function(){
    var previousScroll = 0;
    var nav = document.getElementsByTagName("nav");

    window.addEventListener("scroll", function(e){
       var currentScroll = window.scrollY;
       var isDown = currentScroll > previousScroll;

       if ( isDown ){
          $("nav").hide();  
       }
       else if ( !isDown ){ 
       	  $("nav").show();
       }
       previousScroll = currentScroll;
    });
}()); 


/*------------------------------------------------------------

---------------------------------------------------------------*/


$(document).ready(function(){

  $("body").on("click", function(){
      $("nav").fadeToggle();
  });


});