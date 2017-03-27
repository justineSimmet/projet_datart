$(function(){
/**********************************************
** DIV DRAGGABLE
************************************************/
	var xPos = '';
	var yPos = '';

	$('.drag-area').draggable(
		{ 
			appendTo: '#drop-area',
			cursorAt: { right: 0, bottom: 0 },
			refreshPositions: true,
			helper: function(){
				var ref = $(this).attr('data-reference');
				return '<div class="ui-draggable-helper" data-ref="'+ref+'"></div>';
			},
			revert: 'invalid',

			drag: function(event, ui){
	            xPos = ui.position.left;
	            yPos = ui.position.top;
	            console.log('x :'+xPos+' - y :'+yPos);
        	}
		}
	);

	
	
	$('#drop-area').droppable(
		{
			drop : function (event, ui) {
				var svgArea = d3.select('#originalPlan');
				var rectangle = svgArea.append("rect").attr("x", xPos-26.2).attr("y", yPos-157.45).attr("width", 25).attr("height", 25).attr("fill", "#ffffff").attr("stroke-width", 2).attr("stroke" , "rgb(213,49,58)");
    		}
		}
	);

});