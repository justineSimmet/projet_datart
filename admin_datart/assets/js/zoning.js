//FONCTION DE NETTOYAGE DE TABLEAUX
//Recherche dans le tableau si la cible recherchée est présente
//Si c'est le cas, la donnée est supprimée du tableau
function cleanArray(array, target){
	for(var i in array){
		if(array[i][0]==target){
		    array.splice(i,1);
		    break;
		}
	}
}


$(document).ready(function() {

	var arrayData = [];

	//Initialise un tableau de référence de positions des éléments droppés dans la cible
	$('#drop-area').find('.dropItem').each(function(){
		var ref = $(this).attr('id');
		refArray = [ref, $(this).position()];
		arrayData.push(refArray);
	});
	

/**********************************************
** ACTIONS SUR LE MENU
************************************************/

	$('#saveZoning').on('click', function(){
		var exhibit = $(this).attr('data-exhibit');
		var dataJson = JSON.stringify(arrayData);
		//Après avoir converti les données du tableau de positions en Json,
		//execution d'une requête Ajax pour sauvegarder les positions en BDD
		$.post('save_zoning.php',{action:'save', data: dataJson, target: exhibit} , function(response){
				var obj = JSON.parse(response);
				if (obj.response == 'success') {
					var alertSuccess = '<div class="alert alert-success alert-dismissable text-center">'
						+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
						+'<strong>Les données ont bien été enregistrées.</strong>'
						+'</div>';
					$('#alert-area').html(alertSuccess);
					// Si l'enregistrement a réussi, exécution d'un html2canvas pour effectuer une "capture"
					// de la zone de Drop et envoi du résultat sous la forme d'un jpeg en Base 64 en Ajax
					// afin de sauvegarder le "visuel" dans un fichier jpeg.
					html2canvas($("#drop-area"), {
    					onrendered: function( canvas ) {
    						var image = canvas.toDataURL('image/jpeg', 1.0);
           					$.post('save_zoning.php', {action : 'imageCreate', data : image, target: exhibit});
        				}
					});
				}
				else{
					var alertDanger = '<div class="alert alert-danger alert-dismissable text-center">'
						+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
						+'<strong>Une erreur s\'est produite durant l\'enregistrement des données. L\'opération n\'a pas pu être effectuée.</strong>'
						+'</div>';
					$('#alert-area').html(alertDanger);
				}
			})
	});

	//Annuler les derniers changements
	$('#cancelZoning').on('click', function(){
		$('#cancelChange').modal('show');
	});

	//Réinitialiser le plan
	$('#resetZoning').on('click', function(){
		$('#deleteChange').modal('show');
	});

	$('#deleteData').on('click', function(){
		//Si la demande de réinitialisation est validée,
		//une requête ajax est envoyée pour effacer pour supprimer les données de la table art-zoning
		var exhibit = $(this).attr('data-exhibit');
		$.post('save_zoning.php', {action: 'delete', target: exhibit}, function(response){
			var obj = JSON.parse(response);
			if (obj.response == 'success') {
				location.reload(true);
			}
			else{
				var alertDanger = '<div class="alert alert-danger alert-dismissable text-center">'
						+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
						+'<strong>Une erreur s\'est produite durant la suppresion des données. L\'opération n\'a pas pu être effectuée.</strong>'
						+'</div>';
				$('#alert-area').html(alertDanger);
			}
		})
	});

	$('#quit').on('click', function(){
		$('#quitWindow').modal('show');
	});

/**********************************************
** DRAG AND DROP AVEC JQUERY UI
************************************************/
	//Initialise les éléments draggable dans la liste des oeuvres
	$('.dragItem').draggable(
		{ 
			appendTo: '#dropTarget',
			cursor: 'move',
			cursorAt: { left: 5, top: 5 },
			helper: function(){
				var ref = $(this).attr('data-reference');
				return '<div class="ui-draggable-helper" id="'+ref+'"><span>'+ref+'</span></div>';
			},
			revert: 'invalid',

		}
	);

	//Initialise les éléments draggable dans la zone de drop
	$('.dropItem').draggable(
		{
			containment: "parent",
			helper: function(){
				var ref = $(this).attr('id');
				$(this).addClass("temp-item");
				$(this).css('opacity', 0);
				return '<div class="ui-draggable-helper" id="'+ref+'"><span>'+ref+'</span></div>';
			},
		}
	);
	
	//Actions au drop :
	//Clonage du helper à l'endroit du drop et conversion en élément dropItem
	//MAJ de la liste des oeuvres pour changer le symbole de l'élément droppé
	//Mise a jour du tableau de références de positions
	$('#dropTarget').droppable(
		{
			drop : function (event, ui) {
				var ref = ui.helper.attr('id');
				$(this).prepend(ui.helper.clone().addClass('ui-draggable-dropped').removeClass('ui-draggable-helper').addClass('dropItem').attr('id',ref));
				$('.temp-item').remove();
				var divContainer = $('#availble-artwork').find("[data-reference='" + ref + "']").parents('.list-element');
				divContainer.find('.action-item-area').remove();
				divContainer.append('<div class="action-item-area refreshItem" data-reference="'+ ref +'"><span class="fa fa-refresh"></span></div>');
				cleanArray(arrayData, ref);
				refArray = [ref, $('#'+ref).position()];
				arrayData.push(refArray);
				//Initialisation à chaque drop d'un draggable pour pouvoir redéplacer l'élément si nécessaire
				$('.dropItem').draggable(
					{
						containment: "parent",
						helper: function(){
							var ref = $(this).attr('id');
							$(this).addClass("temp-item");
							$(this).css('opacity', 0);
							return '<div class="ui-draggable-helper" id="'+ref+'"><span>'+ref+'</span></div>';
						},
					}
				);
    		}
		}
	);

	// Rappel d'un élément droppé a partir de la liste
	$('#availble-artwork').on('click', '.refreshItem', function(){
		var ref = $(this).attr('data-reference');
		$('#'+ref).remove();
		$(this).removeClass('refreshItem').remove('span').html('<span class="fa fa-arrows"></span>').addClass('dragItem ui-draggable ui-draggable-handle');

		cleanArray(arrayData, ref);

		//Réinitialisation du draggable pour l'élément rappelé
		$('.dragItem').draggable(
			{ 
				appendTo: '#dropTarget',
				cursor: 'move',
				cursorAt: { left: 5, top: 5 },
				helper: function(){
					var ref = $(this).attr('data-reference');
					return '<div class="ui-draggable-helper" id="'+ref+'"><span>'+ref+'</span></div>';
				},
				revert: 'invalid',

			}
		);

	})

});



