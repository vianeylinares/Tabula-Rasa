jQuery('body').on( 'click', '.guester input[type=checkbox]', function($) {

//jQuery(".guester input[type=checkbox]").click(function(){

	//alert("Here");

	which_guest_id = jQuery(this).val();

	console.log(which_guest_id);

	which_table = jQuery(".guest-id_" + which_guest_id).parent().parent().attr('id');

	which_table_num = which_table.substr(which_table.indexOf('-') + 8);

	jQuery(".guest-id_" + jQuery(this).val()).remove();

	console.log(which_guest_id);

	jQuery.post(

		home_url + '/wp-admin/admin-ajax.php', {

			action: 'removeGuestFromTable',

				data: { guest_id: which_guest_id },
				dataType: "json"

		}, function(datas){

			datas = jQuery.parseJSON(datas);

			console.log(datas);

			if(jQuery("#draggable-" + which_guest_id).length == 0) {

                removed_guest_from_table = "<div id='draggable-" + which_guest_id + "' class='guests-group draggable returned'>";
                removed_guest_from_table+= datas.guest_name;
                removed_guest_from_table+= "<div num_guests='1' class='num-guests'></div>";
                removed_guest_from_table+= "<div group_array='" + which_guest_id + "' class='group-array'></div>";
                removed_guest_from_table+= "</div>";

			    jQuery(".guests-box").append(removed_guest_from_table);

			} else {

			    jQuery("#draggable-" + which_guest_id).css({
					"display": "block",
					"top": "auto",
					"left": "auto",
					"width": "100%",
					"height": "auto"
				});

				jQuery("#draggable-" + which_guest_id).draggable({revert:"invalid"});

			}

			set_draggable();

			seats_taken_display = "<div class='table-id' table-id='" + datas.table_id + "'></div>";
			seats_taken_display+= "<div class='seats-taken' seats-taken='" + datas.table_count + "'></div>";
			seats_taken_display+= "<style>#table-" + which_table_num + " .table-visual img{ display: none; } #table-" + which_table_num + " .table-visual img:nth-child(" + (datas.table_count + 1) + "){ display: block; }</style>";

			console.log(seats_taken_display);

			jQuery("#table-" + which_table_num + " .table-data").html(seats_taken_display);

			if(datas.table_pre_count == datas.table_max_seats){
				//jQuery('#table-' + which_table_num ).droppable("enable");
			}

			//location.reload();

		}

	);

//});




});