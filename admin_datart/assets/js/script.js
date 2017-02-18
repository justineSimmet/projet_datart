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
$(function() {
	var location = window.location.href.substr(window.location.href.lastIndexOf("/")+1);
	$("#main-nav-menu li a").each(function(){
		if($(this).attr("href") == location){
			$(this).addClass("active-nav-link");
      	}
     })
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
	
/**********************************************
** ACTIONS SUR LA LISTE DEROULANTE DES ACTIONS
************************************************/
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
					newDoc.close()
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
					$("#mymodal").modal('show')
				}	
			});

		 };			
	});


});

