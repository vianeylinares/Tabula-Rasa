jQuery(document).ready(function($){

	jQuery( ".droppable" ).droppable({
		drop: function( event, ui ) {
			jQuery("#" + which_guest_group).css({
				"display": "none",
			});

			which_table = jQuery(this).attr('id');
			which_table_num = which_table.substr(which_table.indexOf('-') + 1);
			table_seats_taken =  jQuery("#" + which_table + " div.seats-taken").attr('seats-taken');

			numbers = jQuery("#" + which_guest_group + " div.num-guests").attr("num_guests");
			table = jQuery("#" + which_table + " div.table-id").attr("table-id");
			with_main_guest = jQuery("#" + which_guest_group + " div.with-main-guest").attr("with_main_guest");

			which_guest_group_num = which_guest_group.substr(which_guest_group.indexOf('-') + 1);

			guests_group = jQuery("#" + which_guest_group + " div.group-array").attr("group_array");

			console.log(guests_group);

			console.log("Which table: " + which_table + ", Table num: " + which_table_num + ", Seats taken: " + table_seats_taken + ", Guests: " + numbers + ", Table id: " + table, "guests_group: " + guests_group);

			jQuery.post(

				home_url + '/wp-admin/admin-ajax.php', {

					action: 'addGuestsToTable',

						data: { table_id: table, table_num: which_table_num, guests_number: numbers, seats_taken: table_seats_taken, main_guest: with_main_guest, guests_group: guests_group },
						dataType: "json"

				}, function(datas){

					datas = $.parseJSON(datas);

					console.log(datas);

					if(datas.none == undefined){

						seats_taken_display = "<div class='table-id' table-id='" + table + "'></div>";
						seats_taken_display+= "<div class='seats-taken' seats-taken='" + datas.seats_total + "'></div>";
						seats_taken_display+= "<style>#table-" + which_table_num + " .table-visual img{ display: none; } #table-" + which_table_num + " .table-visual img:nth-child(" + (datas.seats_total + 1) + "){ display: block; }</style>";

						jQuery("#"+ which_table + " .table-data").html(seats_taken_display);

						if(datas.seats_total == datas.max_seats){
							jQuery('#table-' + which_table_num ).droppable("disable");
						}

						new_seats_display = "";

						for( i=1; i<=datas.new_guests; i++ ){

							//if(i!=1){
								//new_seats_display+= "<div class='guester guest-id_" +  datas['guest_'+i+'_ID'] + "'>&nbsp;&nbsp;&nbsp;<input type='checkbox' value='" +  datas['guest_'+i+'_ID'] + "' checked /> ";
							//}else{
								//new_seats_display+= "<div class='guester guest-id_" +  datas['guest_'+i+'_ID'] + "'><input type='checkbox' value='" +  datas['guest_'+i+'_ID'] + "' checked disabled /> ";
							//}

							new_seats_display+= "<div class='guester guest-id_" +  datas['guest_'+i+'_ID'] + "'>";
							new_seats_display+= "<input type='checkbox' value='" +  datas['guest_'+i+'_ID'] + "' checked/> ";
							new_seats_display+= datas["guest_"+i];
							new_seats_display+= "</div>";

						}

						jQuery("#small-dialog-"+ which_table_num + " .table-guests-list").append(new_seats_display);

						//jQuery("body").prepend("<div class='mfp-bg my-mfp-zoom-in mfp-ready'></div>");

						//location.reload();

					}

					if(datas.none == 0 ){

						alert("No hay asientos suficientes en esta mesa");

						//return false;
						jQuery("#" + which_guest_group).css({
							"display": "block",
							"top": "auto",
							"left": "auto",
							"width": "100%",
							"height": "auto"
						});

						jQuery("#" + which_guest_group).draggable({revert:"invalid"});

					}

				}

			);

		}

    });	

});