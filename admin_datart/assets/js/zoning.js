
$(function(){
/**********************************************
** DRAG AND DROP AVEC JQUERY UI
************************************************/
	var xPos = '';
	var yPos = '';

	var arrayData = [];

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
				$('#availble-artwork').find("[data-reference='" + ref + "']").removeClass('dragItem').remove('span').html('<span class="fa fa-refresh"></span>').addClass('ui-draggable ui-draggable-handle refreshItem');
				refArray = [ref, $('#'+ref).position()];
				arrayData.push(refArray);

				$('.dropItem').draggable(
					{
						containment: "parent"
					}
				);
    		}
		}
	);


	$('#availble-artwork').on('click', '.refreshItem', function(){
		var ref = $(this).attr('data-reference');
		$('#drop-area').find('#'+ref).remove();
		$(this).removeClass('refreshItem').remove('span').html('<span class="fa fa-arrows"></span>').addClass('dragItem');

		for(var i in arrayData){
		    if(arrayData[i][0]==ref){
		        arrayData.splice(i,1);
		        break;
		    }
		}
	})

});
		// var dataJson = JSON.stringify(arrayData);



