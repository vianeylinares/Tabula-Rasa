jQuery(document).ready(function($){

	jQuery( ".table-box" ).click(function(){		

		table_type = jQuery(this).attr('id');
		table_seats = table_type.substr(table_type.indexOf('-') + 1);
		table_shape = table_type.substr(0, table_type.indexOf('-'));

		jQuery.post(

			home_url + '/wp-admin/admin-ajax.php', {	
				
				action: 'createTable',
				
					data: {
						table_shape: table_shape,
						table_seats: table_seats
					}				

			}, function(data){

				data = $.parseJSON(data);

				new_table_display = "<div id='table-" + data.table_id + "' class='table draggable' style='top: 0; left: 0;'>";
				new_table_display += "<div class='table-visual'>";
				new_table_display += "<img src='" + home_url +"/wp-content/plugins/tabula-rasa/images/table-" + data.table_shape + "-" + data.table_seats + "-seat-0.png' />";
				new_table_display += "</div>";
				new_table_display += "<div class='table-visual-ref'>" + data.total_tables + "</div>";
				new_table_display += "</div>";

				jQuery(".venue-space-box h2 span").text(data.total_tables);
				jQuery(".table-distribution-box").append(new_table_display);

				set_draggable();

			}

		);

	});

});