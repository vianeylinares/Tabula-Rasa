jQuery(document).ready(function($){

	jQuery( ".separate" ).click(function(){

		which_separator = jQuery(this).parent().attr('id');
		which_separator_num = which_separator.substr(which_separator.indexOf('-') + 1);

		if( jQuery(this).hasClass('disable') ){

			/* Disable other active separators and checkboxes */
			jQuery( ".separate" ).removeClass("active");
			jQuery( ".separate" ).addClass("disable");
			jQuery( ".guests-group input" ).css({
				"display": "none",
			});

			jQuery(this).removeClass("disable");
			jQuery(this).addClass("active");

			jQuery( "#" + which_separator + " input" ).css({
				"display": "inline-block",
			});

			jQuery(this).parent().draggable( "disable" );
			jQuery(this).parent().css({"cursor":"auto"});

			return false;

		}

		if( jQuery(this).hasClass('active') ){

			jQuery(this).removeClass("active");
			jQuery(this).addClass("disable");

			jQuery( "#" + which_separator + " input" ).css({
				"display": "none",
			});

			jQuery(this).parent().draggable( "enable" );
			jQuery(this).parent().css({"cursor":"move"});

			return false;

		}

	});

	jQuery(".guests-group input[type=checkbox]").click(function(){

		which_guest_id = jQuery(this).val();
		which_guest_group = jQuery(this).parent().parent().attr('id');

		jQuery(this).parent().remove();

		new_num_guests = parseInt(jQuery("#" + which_guest_group + " .num-guests").attr("num_guests")) - 1;

		jQuery("#" + which_guest_group + " .num-guests").attr("num_guests", new_num_guests);

		group_array = jQuery("#" + which_guest_group + " .group-array").attr("group_array");
		new_group_array = group_array.replace(which_guest_id,'');
		new_group_array = new_group_array.replace(',,',',');

		jQuery("#" + which_guest_group + " .group-array").attr("group_array", new_group_array);

		jQuery.post(

			home_url + '/wp-admin/admin-ajax.php', {

				action: 'removeGuestFromGroup',

					data: { guest_id: jQuery(this).val() },
					dataType: "json"

			}, function(datas){

				datas = $.parseJSON(datas);

				removed_guest_from_table = "<div id='draggable-" + which_guest_id + "' class='guests-group draggable returned'>";
				removed_guest_from_table+= datas.guest_name;
				removed_guest_from_table+= "<div num_guests='1' class='num-guests'></div>";
				removed_guest_from_table+= "<div group_array='" + which_guest_id + "' class='group-array'></div>";
				removed_guest_from_table+= "</div>";

				jQuery(".guests-box").append(removed_guest_from_table);

				set_draggable();

			}

		);

	});

});