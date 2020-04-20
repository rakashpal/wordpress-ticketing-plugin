jQuery(document).ready(function(){
	jQuery(document).on('submit','#ticket_form',function(e){
		e.preventDefault();
        var formObj=jQuery(this);
		var ajaxurl=jQuery(this).attr('action');

		//alert(ajaxurl);
		var data = jQuery( this ).serializeArray();
        
         jQuery.post(ajaxurl, data, function(response) {
            console.log(response);
            var res=JSON.parse(response);
                     if(res.success==1){
                formObj.trigger("reset");
                jQuery(".response").html(`<p style="color:green">`+res.message+`</p>`);
            }else{
                jQuery(".response").html(`<p style="color:red">`+res.message+`</p>`);

            }
            
        });
    });
    jQuery(document).on('submit','#ticket_form_revert',function(e){
        
        e.preventDefault();
        var formObj=jQuery(this);
		var ajaxurl=jQuery(this).attr('action');

		//alert(ajaxurl);
		var data = jQuery( this ).serializeArray();
        
         jQuery.post(ajaxurl, data, function(response) {
            console.log(response);
            var res=JSON.parse(response);
                     if(res.success==1){
                formObj.trigger("reset");
                jQuery(".response").html(`<p style="color:green">`+res.message+`</p>`);
                window.location.reload();
            }else{
                jQuery(".response").html(`<p style="color:red">`+res.message+`</p>`);

            }
            
        });
    });
    jQuery(document).on('click','#complete_ticket',function(e){
        
        e.preventDefault();
      
		var ajaxurl=jQuery(this).data('url');
        var ticket_id=jQuery(this).data('id');
		//alert(ajaxurl);
		var data = {
            action:'complete_ticket',
            ticket_id:ticket_id
        };
        
         jQuery.post(ajaxurl, data, function(response) {
            var res=JSON.parse(response);
                     if(res.success==1){
                        alert(res.message);
                window.location.reload();
            }else{
               alert(res.message);

            }
            
        });
	});
});