$(function(){
/**********************************************
** DIV DRAGGABLE
************************************************/
	var position = '';
	var xPos = '';
	var yPos = '';

	$('.drag-area').draggable(
		{ 
			appendTo: '#drop-target',
			helper: 'clone',
			revert: 'invalid',
			start: function(e, ui) {
				$(ui.helper).find("span").remove();
				$(ui.helper).addClass("ui-draggable-helper");
			},
			drag: function(){
	            position = $(this).offset();
	            xPos = position.left;
	            yPos = position.top;
	            console.log(xPos+' '+yPos);
        	}
		}
	);

	
	
	$('#drop-area').droppable(
		{
			drop : function (e, ui) {
				var area = $(this);
				var clone = $(ui.draggable).clone();
				clone.find("span").remove();
				clone.find(".artwork-ref").css("display", "inline-block");
       			clone.removeClass("ui-draggable-helper").addClass("ui-draggable-dropped");
       			area.append(clone.offset({top: yPos, left: xPos }));
    		}
		}
	);

});