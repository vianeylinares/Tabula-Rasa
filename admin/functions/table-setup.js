jQuery(document).ready(function($){

	set_draggable();

});

function set_draggable(){

	var sourceImageWidth = jQuery(".venue img").width();
	var sourceImageHeight = jQuery(".venue img").height();

	jQuery( ".draggable" ).draggable({ 
		containment: "parent",
		drag: function(event, iu){

			which_table = jQuery(this).attr('id');
			which_table_num = which_table.substr(which_table.indexOf('-') + 1);
			//console.log(which_table_num);			
			
		},
		stop: function(event, ui){
							
			var offset = jQuery(this).position();
            var xPos = offset.left;
            var yPos = offset.top;

			jQuery.post(
				home_url + '/wp-admin/admin-ajax.php', {	
					
					action: 'getLeftTop',
					
						data: { x: xPos,  y: yPos, length: sourceImageWidth, height: sourceImageHeight, item: which_table_num }
					
				}, function(data){

									
				}

			);					
			
		}			

	});

}