jQuery(document).ready(function($){
    
    jQuery(".table-list input").click(function(){
    					    
       guest = jQuery(this).parent().attr('id');
       
       guest_id = guest.substr(guest.indexOf('-') + 1);
       
       console.log(guest_id);
       
       if(jQuery(this).is(":checked")){
        
    	    jQuery.post(
    
    			home_url + '/wp-admin/admin-ajax.php', {
    
    				action: 'arrivedGuest',
    
    					data: { guest_id: guest_id },
    					dataType: "json"
    
    			}, function(datas){
    
    				datas = $.parseJSON(datas);
    				
    				console.log(datas);
    			    
    			    jQuery("#" + guest + " span").addClass("arrived");
    			    
    			}
    
    		);
    		
        }
        
        if(!jQuery(this).is(":checked")){
        
    	    jQuery.post(
    
    			home_url + '/wp-admin/admin-ajax.php', {
    
    				action: 'leftGuest',
    
    					data: { guest_id: guest_id },
    					dataType: "json"
    
    			}, function(datas){
    
    				datas = $.parseJSON(datas);
    				
    				console.log(datas);
    
    			    jQuery("#" + guest + " span").removeClass("arrived");
    
    			}
    
    		);
    		
        }
        
    });
    
});