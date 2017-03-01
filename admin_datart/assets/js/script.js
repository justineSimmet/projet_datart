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

$('.datepicker').focusin(function(){
	if ( $(this).attr('name') == 'end_date' && $(this).val() == ''){
		$beginDate = $(this).parent().parent().prev().children().children().val();
		$(this).val($beginDate);
	}
});

$('.datepicker').datepicker({
		showAnim: 'clip',
		showOtherMonths: true,
		selectOtherMonths: true,
		minDate: -7
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
	//La valeur de userId est envoyée en POST via une requête ajax vers la page actuelle.
	//C'est là que ça devient vicieux ^^
	//Les données renvoyée correspondent à la page actuelle avec la création d'un objet User
	//$targetUser. Pour afficher ces données, j'interviens directement sur DOM en lui disant de
	//créer une variable newDoc qui correspond à l'ensemble de la page HTML présente (Doctype / Head / Body).
	//Je remplace le contenu de cette page par les données retournée en ajac et referme la manipulation de doc.
	$('.actionUser').on('change', function(){
		if ($(this).val() == 'update'){
			var userId = $(this).children('option:selected').attr("data-id");
			$.ajax({
				method: 'POST',
				data : {
					targetUser : userId
				},
				success: function(data){
					var newDoc = document.open("text/html", "replace");
					newDoc.write(data);
					newDoc.close();
				}	
			});
		 }
		else if($(this).val() == 'delete'){
			var userId = $(this).children('option:selected').attr("data-id");
			$.ajax({
				method: 'POST',
				data : {
					targetUser : userId
				},
				success: function(data){
					var newDoc = document.open("text/html", "replace");
					newDoc.write(data);
					newDoc.close();
					$("#mymodal").modal('show');
				}	
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
			$.ajax({
				method: 'POST',
				data : {
					targetId
				},
				success: function(data){
					var newDoc = document.open("text/html", "replace");
					newDoc.write(data);
					newDoc.close();
					$("#hideExhibit").modal('show');
				}	
			});
		}
		else if($(this).val() == 'publish'){
			var targetId = $(this).children('option:selected').attr("data-id");
			$.ajax({
				method: 'POST',
				data : {
					targetId : targetId,
					action : 'publish'
				},
				success: function(data){
					var newDoc = document.open("text/html", "replace");
					newDoc.write(data);
					newDoc.close();
				}	
			});
		}		
	});

/**********************************************************************
** OUVERTURE D'UNE MODAL SI CLIC SUR LE BOUTON SUPPRIMER DEFINITIVEMENT
** Envoi le targetId de l'expo en Ajax et recharge la page avant
** d'ouvrir la modal.
***********************************************************************/
	$('.btn-delete').on('click', function(){
		var targetId = $(this).attr("data-id");
		$.ajax({
			method: 'POST',
			data : {
				targetId : targetId
			},
			success: function(data){
				var newDoc = document.open("text/html", "replace");
				newDoc.write(data);
				newDoc.close();
				$("#deleteExhibit").modal('show')
			},
		});
	});

	$('.btn-publish').on('click', function(){
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

/**********************************************
** REFRESH AJAX APRES INSERT OU UPDATE
** DES TEXTES D'ACCOMPAGNEMENT D'EXPO 
************************************************/
	if($('#insert-exhibit-text').length == 1 || $('#update-exhibit-text').length == 1){

		var insertSuccess ='<div class="alert alert-success alert-dismissable">'
		+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
		+'<strong>Les textes d\'accompagnement ont bien été enregistré.'
		+'</div>';

		var updateSuccess ='<div class="alert alert-success alert-dismissable">'
		+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
		+'<strong>Les textes d\'accompagnement ont bien été mis à jour.</strong>'
		+'</div>';

		if($('#insert-exhibit-text').length == 1){
			$.ajax({
				method: 'POST',
				success: function(data){
					var newDoc = document.open("text/html", "replace");
					newDoc.write(data);
					newDoc.close();

					$('#alert-area').append(insertSuccess);
				},
			});	
		}
		else{
			$.ajax({
				method: 'POST',
				success: function(data){
					var newDoc = document.open("text/html", "replace");
					newDoc.write(data);
					newDoc.close();

					$('#alert-area').append(updateSuccess);
				},
			});
		}

	};

/**********************************************
** REFRESH AJAX APRES INSERT OU UPDATE
** D'UN EVENEMENT D'EXPO 
************************************************/
	if($('#insert-exhibit-event').length == 1 || $('#update-exhibit-event').length == 1){

		var insertSuccess ='<div class="alert alert-success alert-dismissable">'
		+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
		+'<strong>L\'événement a bien été enregistré.'
		+'</div>';

		var updateSuccess ='<div class="alert alert-success alert-dismissable">'
		+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
		+'<strong>L\'événement a bien été mis à jour.</strong>'
		+'</div>';

		if($('#insert-exhibit-event').length == 1){
			$.ajax({
				method: 'POST',
				success: function(data){
					var newDoc = document.open("text/html", "replace");
					newDoc.write(data);
					newDoc.close();

					$('#alert-area').append(insertSuccess);
				},
			});	
		}
		else{
			$.ajax({
				method: 'POST',
				success: function(data){
					var newDoc = document.open("text/html", "replace");
					newDoc.write(data);
					newDoc.close();

					$('#alert-area').append(updateSuccess);
				},
			});
		}

	};
	
/*********************************************************
** RECHARGEMENT DE LA PAGE SI UNE EXPO A BIEN ETE UPDATE
*********************************************************/
	if($('#update-exhibit').length == 1){
		$.ajax({
			method: 'POST',
			success: function(data){
				var newDoc = document.open("text/html", "replace");
				newDoc.write(data);
				newDoc.close();

				var divSuccess ='<div class="alert alert-success alert-dismissable">'
				+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
				+'<strong>Félicitation</strong> L\'exposition a bien été modifiée.'
				+'</div>';
				$('#alert-area').append(divSuccess);
			},
		});

	};


/**********************************************
** EXECUTION REQUETE AJAX POUR UPDATE OU DELETE
** UN EVENEMENT SUR LE ZOOM EXHIBIT
************************************************/
	$('.update-event').on('click', function(){
		var targetEvent = $(this).attr("data-id");
		$.ajax({
			method: 'POST',
			data : {
				targetEvent : targetEvent,
				action : 'update'
			},
			success: function(data){
				var newDoc = document.open("text/html", "replace");
				newDoc.write(data);
				newDoc.close();	
			},
		});
	});

	$('.delete-event').on('click', function(){
		var targetEvent = $(this).attr("data-id");
		$.ajax({
			method: 'POST',
			data : {
				targetEvent : targetEvent
			},
			success: function(data){
				var newDoc = document.open("text/html", "replace");
				newDoc.write(data);
				newDoc.close();
				$("#deleteEvent").modal('show')	
			},
		});
	});

});
