function cleanArray(array, target){
	for(var i in array){
		    if(array[i][0]==target){
		        array.splice(i,1);
		        break;
		    }
		}
}

function getPosition(argument) {
	var yPos = target.position().top - refY;
	var xPos = target.position().left - refY;
}


$(function(){

	/*var cloneArea = $("#dropTarget").find("img");
	var cloneStyle = {  'position': 'absolute',
						'width': cloneArea.css('width'),
						'height': cloneArea.css('height'),
						'top': cloneArea.position().top,
						'left': cloneArea.position().left
					};
	$('#dropTarget').css(cloneStyle);*/

	var arrayData = [];

	// var refArea = $("#drop-area").find("img");
	// var diffY = $(refArea).offset().top - $('#drop-area').offset().top;
	// var diffX = $(refArea).offset().left - $('#drop-area').offset().left;

	// var diffX = ;

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

		var element = $("#drop-area");
		var getImage = '';
		html2canvas(element).then(function(canvas) {
    		var url = canvas.toDataURL("image/png");
			window.open(url, '_blank');
		});
		
		console.log(getImage);

		$.post('save_zoning.php',{action:'save', data: dataJson, target: exhibit, image: getImage} , function(response){
				var obj = JSON.parse(response);
				if (obj.response == 'success') {
					var alertSuccess = '<div class="alert alert-success alert-dismissable text-center">'
						+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
						+'<strong>Les données ont bien été enregistrées.</strong>'
						+'</div>';
					$('#alert-area').html(alertSuccess);
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

	$('#cancelZoning').on('click', function(){
		$('#cancelChange').modal('show');
	});

	$('#resetZoning').on('click', function(){
		$('#deleteChange').modal('show');
	});

	$('#deleteData').on('click', function(){
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


	$('#availble-artwork').on('click', '.refreshItem', function(){
		var ref = $(this).attr('data-reference');
		$('#'+ref).remove();
		$(this).removeClass('refreshItem').remove('span').html('<span class="fa fa-arrows"></span>').addClass('dragItem ui-draggable ui-draggable-handle');

		cleanArray(arrayData, ref);

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



