/*-----------------------------------------------------------------------------
ACTIONS SUR LE MENU HAMBURGER DE LA NAVIGATION
------------------------------------------------------------------------------*/

$('#open-nav').click(function(){
	$('#main-nav-menu').show();
	$('#background-nav').show();
	$(this).hide();
	$('#close-nav').show();
});

$('#close-nav').click(function(){
	$('#main-nav-menu').hide();
	$('#background-nav').hide();
	$(this).hide();
	$('#open-nav').show();
});


/*-----------------------------------------------------------------------------
GESTION LIEN ACTIFS
------------------------------------------------------------------------------*/

// Regarde le href de chaque lien pour trouver si il est similaire
// à l'adresse de la page en cours.
// Si c'est le cas, il rajoute au lien la classe .active-nav-link
// Ensuite, il regarde si le lien ciblé possède un menu imbriqué
// de classe .nav-submenu. Si c'est le cas, il va faire apparaître le 
// lien imbriqué caché et cacher le picto + du lien parent.
$(function() {
	var locationPath = window.location.pathname.split( '/' ).pop();
	$("#main-nav-menu a").each(function(){
		if($(this).attr("href") == locationPath){
			$(this).addClass("active-nav-link");
			if( $(this).next('ul').hasClass('nav-submenu') ){
				var plus = $(this).children('.link-plus');
				var submenu = $(this).next('ul');
				plus.hide();
				submenu.show();
			}
      	}
    });
});

// Vérifie si la classe .active-nav-link est positionnée sur un lien imbriqué.
// Si c'est le cas, le picto + du lien parent est caché tandis que le lien 
// imbriqué est montré.
$(function() {
	$(".nav-submenu li a").each(function(){
		if ($(this).hasClass("active-nav-link")) {
			var plus = $(this).parent().parent().parent().find('.link-plus') ;
			var submenu = $(this).parent().parent();
			plus.hide();
			submenu.show();
		}
	});	
});



/*-----------------------------------------------------------------------------
FORMULAIRE DE CREATION D'UTILISATEURS - CONTROLES & ACTIONS
A AJUSTER EN FONCTION DU FORMULAIRE CREE
------------------------------------------------------------------------------*/

//- Récupère et normalise les 3 premières lettres du prénom saisies par l'utilisateur
//- Retourne le résultat dans l'input login.
//-L'expression régulière utilisée supprime tout les caractères qui ne sont pas des lettres
	function getNameForm(){
		var strName = $('#public_name').val();
		strName = strName.toLowerCase().replace(/[^a-z]/g, "").slice(0,3);
		return strName;
	};

//- Récupère et normalise les 42 premières lettres du nom saisies par l'utilisateur
//- Retourne le résultat et l'ajoute dans l'input login.
	function getSurnameForm(){
		var strSurname = $('#public_surname').val();
		strSurname = strSurname.toLowerCase().replace(/[^a-z]/g, "").slice(0,42);
		return strSurname;
	};

//- Au chargement de la page, chaque fois qu'il y a un focus sur un élément du formulaire user, le login se met à jours.
//- Le login est "vidé" puis prend la dernière valeur retournée par les fonctions de récupération du nom et prénom.
$(function(){
	$('.user-form *').focus(function(){
		$('#login').val('');
		$('#login').val(getNameForm() + getSurnameForm());
	})
});

$(document).ready(function(){

/*-----------------------------------------------------------------------------
MISE EN PLACE DU DATEPICKER JQUERI UI SUR LES CHAMPS DATE
------------------------------------------------------------------------------*/
	$.datepicker.setDefaults($.datepicker.regional['fr']);

	$('#begin_date').on('focusin', function(){
		$(this).datepicker({
			showAnim: 'clip',
			showOtherMonths: true,
			selectOtherMonths: true,
			minDate: 0
		});
	});

	$('#end_date').on('focusin', function(){
		var beginDate = $('#begin_date').val();
		$(this).val(beginDate);
		var dateArray = beginDate.split('/');
		$(this).datepicker({
			showAnim: 'clip',
			showOtherMonths: true,
			selectOtherMonths: true,
			minDate: new Date(dateArray[2],dateArray[1],dateArray[0])
		});
	});

	$('#datepicker').on('focus', function(){
		var beginDate = $('#begin_date').val();
		var endDate = $('#end_date').val();
		var beginArray = beginDate.split('/');
		var endArray = endDate.split('/');
		$(this).datepicker({
			showAnim: 'clip',
			showOtherMonths: true,
			selectOtherMonths: true,
			minDate: new Date(beginArray[2],beginArray[1],beginArray[0]-3),
			maxDate: new Date(endArray[2],endArray[1],endArray[0]-3),
		});
	})

/**************************************************
** SELECTION D'ARTISTES DANS UNE LISTE
****************************************************/
// INPUT DE RECHERCHE DANS LA LISTE DES ARTISTES ENREGISTREE
	var artistList = [];
	$('#recordedArtists li').each(function(){
		artistList.push($(this).text());
	});
	$('#selectedArtists li').each(function(){
		artistList.push($(this).text());
	});

	//Widget JQuery UI qui reprend la liste des artistes dispos
	$( "#searchArtist" ).autocomplete({
    	source: artistList,
    	select: function( event, ui ) {
    		$("#searchArtist").val(ui.item.label);
    		var result = ui.item.label.toLowerCase();
    		$('#recordedArtists li').each(function() {
			var text = $(this).text().toLowerCase();
	    		if (text.indexOf(result) == 0) {
	    			$(this).show();
	    		}
	    		else{
	        		$(this).hide();
	        	}
	    	})
	    	$('#selectedArtists li').each(function() {
			var text = $(this).text().toLowerCase();
	    		if (text.indexOf(result) == 0) {
	    			$(this).show();
	    		}
	    		else{
	        		$(this).hide();
	        	}
	    	})
      	}
    });

    $( "#searchArtist" ).focusout(function(){
    	$('#recordedArtists li').each(function(){
			$(this).show();
		});
		$('#selectedArtists li').each(function(){
			$(this).show();
		});
    })

//MISE EN FORME - CLASSEMENT PAR ORDRE ALPHABETIQUE
	function orderList(selector) {
	    $(selector).children("li").sort(function(a, b) {
	        var upA = $(a).text().toUpperCase();
	        var upB = $(b).text().toUpperCase();
	        return (upA < upB) ? -1 : (upA > upB) ? 1 : 0;
	    }).appendTo(selector);
	}
	orderList("#recordedArtists");
	orderList("#selectedArtists");

    $( "#recordedArtists" ).sortable({
      connectWith: "#selectedArtists",
      cursor: "move",
      update: function( event, ui ) {
      	$('#searchArtist').val('');
      	$('#recordedArtists li').each(function(){
			$(this).show();
		});
		$('#selectedArtists li').each(function(){
			$(this).show();
		});
      	orderList("#selectedArtists");
      	orderList("#recordedArtists");
      }
    });

    $( "#selectedArtists" ).sortable({
      connectWith: "#recordedArtists",
      cursor: "move",
      update: function( event, ui ) {
      	$('#recordedArtists li').each(function(){
			$(this).show();
		});
		$('#selectedArtists li').each(function(){
			$(this).show();
		});
      	orderList("#recordedArtists");
      	orderList("#selectedArtists");
      }
    });




/**********************************************
** EXECUTION REQUETE AJAX SI UN UTILISATEUR A
** ETE AJOUTE EN BD CORRECTEMENT 
************************************************/

	//Détecte la présence d'une div avec l'id user-added
	//Si la div existe le script de add_user.php s'éxécute
	//avec les données data envoyées en POST.
	//La requête envoie une réponse (retour) sous forme d'un tableau JSon.
	//Selon le contenu de la réponse, des div différentes s'affichent.
	if($('#user-added').length == 1){
		$.ajax({
			url: 'add_user.php',
			method: 'POST',
			data : {
				username : userData.name,
				usersurname : userData.surname,
				usermail : userData.email,
				userlogin : userData.login,
				adminname : adminData.name,
				adminsurname : adminData.surname,
				adminmail : adminData.email
			},
			success: function(retour){
				var obj = JSON.parse(retour);
				if (obj.response == 'success') {
					var divSuccess ='<div class="alert alert-success alert-dismissable">'
					+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
					+'<strong>Un email de confirmation a été envoyé.</strong>'
					+'</div>';

					$('#alert-area').append(divSuccess);	
				}
				else{
					var divError ='<div class="alert alert-danger alert-dismissable">'
					+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
					+'<strong>Un email de confirmation n\'a pas pu être envoyé.</strong> Merci de vérifier l\'état de votre connexion'
					+'</div>';

					$('#alert-area').append(divError);
				}
			},
		});
	};

/**********************************************
** EXECUTION REQUETE AJAX SI UN UTILISATEUR A
** ETE UPDATE EN BD CORRECTEMENT 
************************************************/

//Détecte la présence d'une div avec l'id user-edited
	//Si la div existe le script de update_user.php s'éxécute
	//avec les données data envoyées en POST.
	//La requête envoie une réponse (retour) sous forme d'un tableau JSon.
	//Selon le contenu de la réponse, des div différentes s'affichent.
	
	if($('#user-edited').length == 1){
		$.ajax({
			url: 'update_user.php',
			method: 'POST',
			data : {
				username : targetUserData.name,
				usersurname : targetUserData.surname,
				usermail : targetUserData.email,
				userlogin : targetUserData.login,
				adminname : adminData.name,
				adminsurname : adminData.surname,
				adminmail : adminData.email
			},
			success: function(retour){
				var obj = JSON.parse(retour);
				if (obj.response == 'success') {
					var divSuccess ='<div class="alert alert-success alert-dismissable">'
					+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
					+'<strong>Un email de confirmation a été envoyé.</strong>'
					+'</div>';

					$('#alert-area').append(divSuccess);	
				}
				else{
					var divError ='<div class="alert alert-danger alert-dismissable">'
					+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
					+'<strong>Un email de confirmation n\'a pas pu être envoyé.</strong> Merci de vérifier l\'état de votre connexion'
					+'</div>';

					$('#alert-area').append(divError);
				}
			},
		});
	};

/**********************************************
** EXECUTION REQUETE AJAX SI UN MOT DE PASSE
** UTILISATEUR A ETE RESET CORRECTEMENT 
************************************************/

	if($('#user-password').length == 1){
		$.ajax({
			url: 'reset_password.php',
			method: 'POST',
			data : {
				username : targetUserData.name,
				usersurname : targetUserData.surname,
				usermail : targetUserData.email,
				userlogin : targetUserData.login,
				adminname : adminData.name,
				adminsurname : adminData.surname,
				adminmail : adminData.email
			},
			success: function(retour){
				var obj = JSON.parse(retour);
				if (obj.response == 'success') {
					var divSuccess ='<div class="alert alert-success alert-dismissable">'
					+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
					+'<strong>Un email de confirmation a été envoyé.</strong>'
					+'</div>';

					$('#alert-area').append(divSuccess);	
				}
				else{
					var divError ='<div class="alert alert-danger alert-dismissable">'
					+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
					+'<strong>Un email de confirmation n\'a pas pu être envoyé.</strong> Merci de vérifier l\'état de votre connexion'
					+'</div>';

					$('#alert-area').append(divError);
				}
			},
		});
	};
	
/**************************************************
** ACTIONS SUR LA LISTE DEROULANTE DES ACTIONS USERS
***************************************************/
	//Si une option est sélectionnée dans une liste déroulante actionUser
	//et que ça valeur est update, on récupère la valeur de data-id dans userId.
	//La valeur de userId est envoyée en POST via une requête ajax vers la page actuelle
	//Je modifie l'élément nécéssaire (la modal ou le formulaire) via la reponse effectuée
	//par le serveur.
	$('.actionUser').on('change', function(){
		if ($(this).val() == 'update'){
			var userId = $(this).children('option:selected').attr("data-id");
			$.post('users_management.php',{ targetUser : userId}, function(response){
				$('#formArea').html($(response).find('#formArea').html());
			});
		 }
		else if($(this).val() == 'delete'){
			var userId = $(this).children('option:selected').attr("data-id");
			$.post('users_management.php',{ targetUser : userId} , function(response){
				$("#modalDeleteUser").html($(response).find("#modalDeleteUser").html());
				$("#modalDeleteUser").modal('show');
			});
		 };			
	});


/***************************************************
** ACTIONS SUR LA LISTE DEROULANTE DES ACTIONS EXHIBIT
****************************************************/
	$('.actionExhibit').on('change', function(){
		if ($(this).val() == 'update' || $(this).val() == 'show'){
			var exhibitId = $(this).children('option:selected').attr("data-id");
			window.location.replace('exhibit_zoom.php?exhibit='+exhibitId);
		}
		else if($(this).val() == 'hide'){
			var targetId = $(this).children('option:selected').attr("data-id");
			$.post('exhibit_management.php', {targetId}, function(response){
				$("#hideExhibit").html($(response).find("#hideExhibit").html());
				$("#hideExhibit").modal('show');
			});
		}
		else if($(this).val() == 'publish'){
			var targetId = $(this).children('option:selected').attr("data-id");
			$.post('exhibit_management.php', {targetId : targetId, action : 'publish'}, function(response){
				$("#managementExhibitList").html($(response).find("#managementExhibitList").html());
				$("#alert-area").html($(response).find("#alert-area").html());
			});
		}		
	});

/**********************************************************************
** OUVERTURE D'UNE MODAL SI CLIC SUR LE BOUTON SUPPRIMER DEFINITIVEMENT
** Envoi le targetId de l'expo en Ajax et recharge la page avant
** d'ouvrir la modal.
***********************************************************************/
	$('.delete-exhibit').on('click', function(){
		var targetId = $(this).attr("data-id");
		$.post(window.location.href, {targetId}, function(response){
			$("#deleteExhibit").html($(response).find("#deleteExhibit").html());
			$("#deleteExhibit").modal('show');
		});
	});

	$('.publish-exhibit').on('click', function(){
		var targetId = $(this).attr("data-id");
		$.ajax({
			method: 'POST',
			data : {
				targetId : targetId,
				action : 'publish'
			},
			success: function(data){
				var newDoc = document.open("text/html", "replace");
				newDoc.write(data);
				newDoc.close()
			}
		});
	});

/*********************************************************
** RECHARGEMENT DE LA PAGE SI UNE EXPO A BIEN ETE UPDATE
*********************************************************/
	if($('#update-exhibit').length == 1){
		$.post(window.location.href, function(response){
			$("#exhibitMainInfo").html($(response).find("#exhibitMainInfo").html());
		});
	};

/**********************************************
** REFRESH AJAX APRES INSERT OU UPDATE
** DES TEXTES D'ACCOMPAGNEMENT D'EXPO 
************************************************/
	if($('#insert-exhibit-text').length == 1 || $('#update-exhibit-text').length == 1){
		$.post(window.location.href, function(response){
			$("#formTextArea").html($(response).find("#formTextArea").html());
		});
	};

/**********************************************
** REFRESH AJAX APRES INSERT OU UPDATE
** D'UN EVENEMENT D'EXPO 
************************************************/
	if($('#insert-exhibit-event').length == 1 || $('#update-exhibit-event').length == 1){
		$.post(window.location.href, function(response){
			$("#alert-area-event").html($(response).find("#update-exhibit-event").html())
			$("#exhibitEvent").html($(response).find("#exhibitEvent").html());
		});
	};
	

/**********************************************
** EXECUTION REQUETE AJAX POUR UPDATE OU DELETE
** UN EVENEMENT SUR LE ZOOM EXHIBIT
************************************************/
	$('#exhibitEvent').on('click','.update-event', function(){
		var targetEvent = $(this).attr("data-id");
		$.post(window.location.href, {targetEvent : targetEvent}, function(response){
			$("#exhibitEvent form").html($(response).find("#exhibitEvent form").html());
		});
	});

	$('#exhibitEvent').on('click','.delete-event', function(){
		var targetEvent = $(this).attr("data-id");
		$.post(window.location.href, {targetEvent : targetEvent}, function(response){
			$("#deleteEvent").html($(response).find("#deleteEvent").html());
			$("#deleteEvent").modal('show');
		});
	});

/**********************************************
** EXECUTION REQUETE AJAX POUR LIER
** DES ARTISTES SUR LE ZOOM EXHIBIT
************************************************/

	$('#btn-selectedArtist').on('click', function(){
		var listId = [];
		$('#selectedArtists li').each(function(){
			listId.push($(this).attr('data-artistId'));
		});
		$.post(window.location.href, {actionLink : 'addArtist', artistId : listId}, function(response){
			$("#exhibitLinkedArtist").html($(response).find("#exhibitLinkedArtist").html());
			$("#alert-area-artist").html($(response).find("#update-exhibit-artist").html());
		});
	})

});
