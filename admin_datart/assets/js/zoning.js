function cleanArray(array, target){
	for(var i in array){
		    if(array[i][0]==target){
		        array.splice(i,1);
		        break;
		    }
		}
}


$(function(){

	var arrayData = [];

/**********************************************
** ACTIONS SUR LE MENU
************************************************/

$('#saveZoning').on('click', function(){
	
	var dataJson = JSON.stringify(arrayData);
	/*console.log(dataJson);*/
});

/**********************************************
** DRAG AND DROP AVEC JQUERY UI
************************************************/

	$('.dragItem').draggable(
		{ 
			appendTo: '#drop-area',
			cursor: 'move',
			cursorAt: { left: 5, top: 5 },
			helper: function(){
				var ref = $(this).attr('data-reference');
				return '<div class="ui-draggable-helper" id="'+ref+'"><span>'+ref+'</span></div>';
			},
			revert: 'invalid',

		}
	);
	
	$('#drop-area').droppable(
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
				appendTo: '#drop-area',
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



