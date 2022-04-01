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

				jQuery(".venue-space-box h2 span").text(data.total_tables);

			}

		);

	});

});