/*-----------------------------------------------------------------------------
INITIALISATION TINYMCE
------------------------------------------------------------------------------*/
var configTinyMce = {selector: '.textarea-avaible',
    				language: 'fr_FR',
    				menubar: false,
    				toolbar:'undo | redo | bold | italic | alignleft | aligncenter | alignright | cut | copy | paste',
    				statusbar: false};


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
	var locationPath = window.location;
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

/*-----------------------------------------------------------------------------
Initialisation des dates pas defaut pour les événements
------------------------------------------------------------------------------*/
function getBeginDate(){
	if ($('#beginDate').length) {
		var beginDate = new Date($('#beginDate').val());
		beginDate.setDate(beginDate.getDate() - 3);
		return beginDate;
	}
};

function getEndDate(){
	if ($('#endDate').length) {
		var endDate = new Date($('#endDate').val());
		endDate.setDate(endDate.getDate() + 3);
		return endDate;
	}
};


/***************************************************************************************
** INITIALISATION DE LA FONCTION D'ACTION SUR LE SUBMIT DE VISUELS PRINCIPAUX DES OEUVRES
***************************************************************************************/
function mainPictureAction(mainTarget, visualArea, captionArea){
	$(mainTarget).on('submit', function(e){
		e.preventDefault();
		var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();

        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'json', // selon le retour attendu
            data: data,
            success: function (response) {
                //Test si il n'y a pas d'erreur sur l'image envoyée
                if(response.file.image.error == 0 ){
                	var success ='<p class="text-success">Le visuel a bien été enregistré.<p>';
					var btnPicture = '<button type="button" class="btn btn-danger pull-left delete-main-picture"'
					+' data-action="deletePicture" data-picture="'+response.text.pictureId+'">Supprimer</button>';

                	$form.find('.alert-area-picture').html(success);
                	$form.find('input[name="pictureId"]').val(response.text.pictureId);
                	$(mainTarget).find('.input-image').removeClass('hidden');
                	$(captionArea).addClass('hidden');
                	$form.find('button[type="submit"]').html('Modifier');
                	$form.find('.delete-main-picture').remove();
                	$(btnPicture).insertBefore($form.find('button[type="submit"]'));
                	$(mainTarget).find('input[name="image"]').val('');
                }
                else{
                	var error ='<p class="text-danger"><strong>Erreur !</strong>'+response.file.image.error+'<p>';
                	$form.find('.alert-area-picture').html(error);
                	$(mainTarget).find('input[name="file"]').val('');
                }
            }
        })
	})

    $(mainTarget).find('input[name="image"]').on('change', function (e) {
        var files = $(this)[0].files;
 
        if (files.length > 0) {
            // On part du principe qu'il n'y qu'un seul fichier
            // étant donné que l'on a pas renseigné l'attribut "multiple"
            var file = files[0];
            var $image_preview = $(visualArea);
            var $caption = $(captionArea);

            // Ici on injecte les informations recoltées sur le fichier pour l'utilisateur
            $caption.removeClass('hidden');
            $(mainTarget).find('.input-image').addClass('hidden');
            $image_preview.find('img').attr('src', window.URL.createObjectURL(file));
            $caption.find('p:first').html(file.name);
        }
    });
 
    // Bouton "Annuler" pour vider le champ d'upload
    $(captionArea).find('button[name="cancel"]').on('click', function (e) {
        e.preventDefault();
 
        $(mainTarget).find('input[name="image"]').val('');
        $(mainTarget).find('.input-image').removeClass('hidden');
        $(visualArea).find('img').attr('src', '');
        $(captionArea).addClass('hidden');
    });

    // Bouton "Supprimer" pour effacer une photo de la base de donnée et des fichiers
    $(mainTarget).on('click','.delete-main-picture', function(e){
    	e.preventDefault();
		var $form = $(this);
		var actiondDetail = $form.attr("data-action");
		var picture = $form.attr("data-picture");
		$.post('picture_process.php',{action : actiondDetail, pictureId : picture}, function(response){
			var obj = JSON.parse(response);
			if (obj.response == 'success') {
				var success ='<p class="text-success">Le visuel a bien été supprimé.<p>';
				$(mainTarget).find('input[name="image"]').val('');
				$(mainTarget).find('input[name="legend"]').val('');
				$(visualArea).find('img').attr('src', '');
				$(mainTarget).find('.delete-main-picture').remove();
				$(mainTarget).find('button[type="submit"]').html('Ajouter');
				$(mainTarget).find('input[name="pictureId"]').val('');
			}
			else{
				var error ='<p class="text-danger"><strong>Erreur !</strong> L\'image n\'a pas pu être supprimée.<p>';
                $form.find('.alert-area-picture').html(error);
                $(captionArea).addClass('hidden');
			}
		});		
	});
}
/*dz-processing
dz-error*/
Dropzone.options.picturesUpload = {
	paramName : "image",
	maxFilesize: 2,
	acceptedFiles: ".jpeg, .jpg",
	dictDefaultMessage : 'Déposez vos fichiers ici ou cliquez pour les sélectionner.',
	dictFallbackMessage : 'Votre navigateur est trop vieux pour le glisser-déposer, merci d\'utiliser le transfert d\'image comme au bon vieux temps.',
	dictInvalidFileType : 'Votre fichier n\'est pas au bon format. Pour les visuels seul les jpeg sont acceptés.',
	dictFileTooBig : 'Votre fichier est trop lourd, il ne doit pas dépasser 2 Mo.',
  	success: function(file, response){
  		var obj = JSON.parse(response);
  		var thumbnail ='<div class="col-sm-2">'
			    +'<div class="thumbnail">'
				+'<div class="img-container">'
				+'<img src="'+obj.text.target+'" class="responsive">'
				+'</div>'
				+'<div class="caption">'
				+'<input type="text" name="legend" readonly data-artwork="'+obj.text.artworkId+'" data-visual="">'
				+'</div>'
			    +'</div>'
			    +'</div>';
		var correctStyle = {opacity : '1' , color : '#1FBA2E'} ;
		var errorStyle = {opacity : '1' , color : '#E00B33'};
    	if(obj.file.image.error == 0){
    		var div = this.element.querySelector('.dz-success-mark');
        	$('.preview-gallery').append(thumbnail);
        	$(div).css(correctStyle);
        }else{
        	var container = this.element.querySelector('.dz-preview');
    		var div = this.element.querySelector('.dz-error-mark');
    		var span = this.element.querySelector('.dz-error-message');
        	$(container).removeClass('dz-processing');
        	$(container).addClass('dz-error');
        	$(span).find('span').text(obj.file.image.error);
        }
    }
};

$(document).ready(function(){

	tinymce.init(configTinyMce);

	$('#loading-svg').hide();

/*-----------------------------------------------------------------------------
MISE EN PLACE DU DATEPICKER JQUERI UI SUR LES CHAMPS DATE
------------------------------------------------------------------------------*/
	$.datepicker.setDefaults($.datepicker.regional['fr']);

	$.datepicker.setDefaults({
        showAnim: 'clip',
		showOtherMonths: true,
		selectOtherMonths: true,
        dateFormat: 'dd/mm/yy'
    });

	$('#begin_date').datepicker({
		minDate: 0,
		onSelect:function(selected){
			$("#end_date").datepicker("option","minDate", selected);
		}
	});


	$('#end_date').datepicker({
		minDate: new Date(getBeginDate()),
		onSelect:function(selected){
			$("#begin_date").datepicker("option","maxDate", selected);
		}
	});


	$('#datepicker').datepicker({
		minDate: new Date(getBeginDate()),
		maxDate: new Date(getEndDate())
	});



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
** GESTION AJAX DE L'UPLOAD DES PHOTOS D'OEUVRES
************************************************/
	mainPictureAction('#main-one', '#visual-one', '#caption-one');
	mainPictureAction('#main-two', '#visual-two', '#caption-two');
	mainPictureAction('#main-three', '#visual-three', '#caption-three');

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
					+'<p><strong>Un email de confirmation a été envoyé.</strong><p>'
					+'</div>';

					$('#alert-area').append(divSuccess);
					$('.user-form').removeData();	
				}
				else{
					var divError ='<div class="alert alert-danger alert-dismissable">'
					+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
					+'<p><strong>Un email de confirmation n\'a pas pu être envoyé.</strong> Merci de vérifier l\'état de votre connexion</p>'
					+'</div>';

					$('#alert-area').append(divError);
					$('.user-form').removeData();
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
					+'<p><strong>Un email de confirmation a été envoyé.</strong></p>'
					+'</div>';

					$('#alert-area').append(divSuccess);
					$('.user-form').removeData();
				}
				else{
					var divError ='<div class="alert alert-danger alert-dismissable">'
					+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
					+'<p><strong>Un email de confirmation n\'a pas pu être envoyé.</strong> Merci de vérifier l\'état de votre connexion</p>'
					+'</div>';

					$('#alert-area').append(divError);
					$('.user-form').removeData();
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
					+'<p><strong>Un email de confirmation a été envoyé.</strong></p>'
					+'</div>';

					$('#alert-area').append(divSuccess);	
				}
				else{
					var divError ='<div class="alert alert-danger alert-dismissable">'
					+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
					+'<p><strong>Un email de confirmation n\'a pas pu être envoyé.</strong> Merci de vérifier l\'état de votre connexion</p>'
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


/************************************************************
** ACTIONS SUR LA LISTE DEROULANTE DES ACTIONS SUR L'ARTISTE
*************************************************************/

	$('.actionArtist').on('change', function(){
		if ($(this).val() == 'update' || $(this).val() == 'show' ){
			var artistId = $(this).children('option:selected').attr("data-id");
			window.location.replace('artist_zoom?artist='+artistId);
		}
		else if($(this).val() == 'hide'){
			var artistId = $(this).children('option:selected').attr("data-id");
			$.post('artist_management.php',{ targetId : artistId} , function(response){
				$("#hideartist").html($(response).find("#hideartist").html());
				$("#hideartist").modal('show');
			});
		}
		else if($(this).val() == 'publish'){
			var artistId = $(this).children('option:selected').attr("data-id");
			$.post('artist_management.php', {targetId : artistId, action : 'publish'}, function(response){
				$("#artistManagementList").html($(response).find("#artistManagementList").html());
				$("#alert-area").html($(response).find("#alert-area").html());
			});
		}
	});

	$('.delete-artist').on('click', function(){
			var artistId = $(this).attr("data-id");
			$.post(window.location.href,{ targetId : artistId}, function(response){
				$("#deleteArtist").html($(response).find("#deleteArtist").html());
				$("#deleteArtist").modal('show');
			});
		});	


/*************************************************************************
** ACTION ACTUALISER AU CLIC DE LA MODIF DES TEXTES
**************************************************************************/

	if($('#insert-artist-text').length == 1 || $('#update-artist-text').length == 1){
		$.post(window.location.href, function(response){
			$(".formText_formPhoto").html($(response).find(".formText_formPhoto").html());
		});
	};


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
		var targetExhibit = $(this).attr("data-id");
		$.post(window.location.href, {targetExhibit}, function(response){
			$("#deleteExhibit").html($(response).find("#deleteExhibit").html());
			$("#deleteExhibit").modal('show');
		});
	});

	$('.publish-exhibit').on('click', function(){
		var targetExhibit= $(this).attr("data-id");
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
			$("#exhibitEvent").html($(response).find("#exhibitEvent").html());
		});
	};

/**********************************************
** REFRESH AJAX APRES INSERT OU UPDATE
** DES TEXTES D'ACCOMPAGNEMENT D'EXPO 
************************************************/

	if($('#insert-exhibit-text').length == 1 || $('#update-exhibit-text').length == 1){
		$('#loading-svg').show();
		var loaded = 0;
		$.post(window.location.href, function(response){
			tinymce.remove();
			$("#formTextualContent").html($(response).find("#formTextualContent").html());
			tinymce.init(configTinyMce);
			loaded++;
            if(loaded == 1) {
				$('#loading-svg').hide();
			}
		});
	};

/**********************************************
** REFRESH AJAX APRES INSERT OU UPDATE
** D'UN EVENEMENT D'EXPO 
************************************************/
	if($('#insert-exhibit-event').length == 1 || $('#update-exhibit-event').length == 1){
		
		$.post(window.location.href, function(response){
			$("#exhibitEvent").html($(response).find("#exhibitEvent").html());
			$("#alert-area-event").html($(response).find("#update-exhibit-event").html())
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
		var exhibitId = $(this).attr('data-exhibitId')
		$.post(window.location.href, {action : 'add-artist', artistId : listId, targetExhibit : exhibitId}, function(response){
			$("#alert-area-artist").html($(response).find("#alert-area-artist").html());
			$("#recordedArtists").html($(response).find("#recordedArtists").html());
			$("#selectedArtists" ).html($(response).find("#selectedArtists" ).html());
			$("#exhibitLinkedArtwork").html($(response).find("#exhibitLinkedArtwork").html());
			$("#recordedArtists" ).sortable( "refresh" );
			$("#selectedArtists" ).sortable( "refresh" );
		});
	})

/**********************************************
** EXECUTION REQUETE AJAX POUR ASSOCIER
** DES OEUVRES SUR LE ZOOM EXHIBIT
************************************************/

	$('#btn-selectedArtwork').on('click', function(){
		var listId = [];
		$('#exhibitLinkedArtwork input:checked').each(function(){
			listId.push($(this).val());
		});
		var exhibitId = $(this).attr('data-exhibitId')
		$.post(window.location.href, {action : 'add-artwork', artworkId : listId, targetExhibit : exhibitId}, function(response){
			$("#alert-area-artwork").html($(response).find("#alert-area-artwork").html());
			$("#exhibitLinkedArtwork").html($(response).find("#exhibitLinkedArtwork").html());
		});
	})

/***************************************************
** ACTIONS SUR LA LISTE DEROULANTE DES ACTIONS ARTWORK
****************************************************/
	$('.actionArtwork').on('change', function(){
		if ($(this).val() == 'update' || $(this).val() == 'show'){
			var artworkId = $(this).children('option:selected').attr("data-id");
			window.location.replace('artwork_zoom.php?artwork='+artworkId);
		}
		else if($(this).val() == 'hide'){
			var targetId = $(this).children('option:selected').attr("data-id");
			$.post('artwork_management.php', {targetId}, function(response){
				$("#hideArtwork").html($(response).find("#hideArtwork").html());
				$("#hideArtwork").modal('show');
			});
		}
		else if($(this).val() == 'publish'){
			var targetId = $(this).children('option:selected').attr("data-id");
			$.post('artwork_management.php', {targetId : targetId, action : 'publish'}, function(response){
				$("#managementArtworkList").html($(response).find("#managementArtworkList").html());
				$("#hiddenArtworkList").html($(response).find("#hiddenArtworkList").html());
				$("#alert-area").html($(response).find("#alert-area").html());
			});
		}		
	});

/**********************************************************************
** OUVERTURE D'UNE MODAL SI CLIC SUR LE BOUTON SUPPRIMER DEFINITIVEMENT
** Envoi le targetId de l'expo en Ajax et recharge la page avant
** d'ouvrir la modal.
***********************************************************************/
	$('.delete-artwork').on('click', function(){
		var targetArtwork = $(this).attr("data-id");
		$.post(window.location.href, {targetId : targetArtwork}, function(response){
			$("#deleteArtwork").html($(response).find("#deleteArtwork").html());
			$("#deleteArtwork").modal('show');
		});
	});

	$('.publish-artwork').on('click', function(){
		var targetArtwork= $(this).attr("data-id");
		$.ajax({
			method: 'POST',
			data : {
				targetId : targetArtwork,
				action : 'publish'
			},
			success: function(data){
				var newDoc = document.open("text/html", "replace");
				newDoc.write(data);
				newDoc.close()
			}
		});
	});

});

