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
		 };	

		
		if ($(this)val() == 'delete') {
			var userId = $(this).children('option:selected').attr("data-id");

		}			
	});

