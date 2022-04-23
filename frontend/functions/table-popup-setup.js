jQuery(document).ready(function($){

	jQuery( ".popup-with-zoom-anim" ).magnificPopup({

	    type: 'inline',

	    fixedContentPos: false,
	    fixedBgPos: true,

	    overflowY: 'auto',

	    closeBtnInside: true,
	    preloader: false,

	    midClick: true,
	    removalDelay: 300,
	    mainClass: 'my-mfp-zoom-in'

	});

	jQuery( ".table" ).click(function(){

		which_table = jQuery(this).attr('id');
		which_table_num = which_table.substr(which_table.indexOf('-') + 1);

		jQuery("#table-popup-" + which_table_num + ".popup-with-zoom-anim").click();

	});

});