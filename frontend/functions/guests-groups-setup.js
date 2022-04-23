function set_draggable(){

	jQuery( ".draggable" ).draggable({
		revert: "invalid",
		drag: function(event, ui){
			which_guest_group = jQuery(this).attr('id');
		}
    });

}