(function( $ ) {

	//click event of add to cart in single product template
	$('.single-product .single_add_to_cart_button').click(function(e){
		
	if ($(this).hasClass('disabled')) {
		//dont do anything if button is disabled, variation need to be selected first
	}else{
		$('body').addClass('loadingPopup');
		e.preventDefault();
		$(this).removeClass('added');
		$(this).addClass('loading');
		var product_id = $(this).val();
		var variation_id = $('input[name="variation_id"]').val();
		var quantity = $('input[name="quantity"]').val();
		
		//console.log(product_id+ " " + quantity+ " " + variation_id);
	
		//Check if we have viariable product and loop through attributes, but them in an array
		var attributes = [];
		$(".variations select").each(function(){
			var name = $(this).attr("data-attribute_name");
			var value = $(this).val();
			attributes.push(name+"="+value);
		});
		
		if (attributes.length === 0) {
			//var finalAttributes;
			var toCart = '?add-to-cart=' + product_id+'&variation_id=' + variation_id+'&quantity=' + quantity;
		}else{
			//console.log(attributes.join('&'));
			var product_id = $('input[name="add-to-cart"]').val();			
			var finalAttributes = attributes.join('&');
			var toCart = '?add-to-cart=' + product_id+'&variation_id=' + variation_id+'&'+finalAttributes+'&quantity=' + quantity;
		}

		$.get(toCart , function() {
			$('.viewcart').remove();
			$(".woocommerce .up-sells .links").remove();
			
				$('.single_add_to_cart_button').after(" <p class='viewcart'> <a target='_blank' href='"+WebdWoocommerceCrosellPopup.siteUrl+"/cart'><i class='fa fa-shopping-cart'></i> "+WebdWoocommerceCrosellPopup.view_your_cart+"</a></p>");				
				
				$(".single_add_to_cart_button").removeClass('loading');
				$(".single_add_to_cart_button").addClass('added');	


				
				$('.woocommerce .up-sells').prepend("<center><div class='links'></div></center>");
				$(".woocommerce .up-sells .links").html("<a target='_blank' href='"+WebdWoocommerceCrosellPopup.siteUrl+"/cart' ><i class='fa fa-shopping-cart'></i> "+WebdWoocommerceCrosellPopup.view_your_cart+"</a> | <a href='"+WebdWoocommerceCrosellPopup.siteUrl+"/checkout' target='_blank' ><i class='fa fa-credit-card'></i> "+WebdWoocommerceCrosellPopup.checkout+"</a> ");
				 
				$('.woocommerce .up-sells').fadeIn();
				$('.woocommerce .up-sells  .links').append('<span class="close">&times;</span>');			 
				$('.woocommerce .up-sells .close').on('click',function(){
													
					$('.woocommerce .up-sells').css('display','none');
					$(this).remove();					
				});
				
			refresh_fragments();
			//setInterval(refresh_fragments, 60000);				
				
				var upsells = document.getElementsByClassName('up-sells');
				window.onclick = function(event) {
					if (event.target == upsells) {
						upsells.style.display = "none";
					}
				}			
		});	
	
			function refresh_fragments() {
				console.log('fragments refreshed!');
				$( document.body ).trigger( 'wc_fragment_refresh' );
				$('body').removeClass('loadingPopup');
			}
			
	
	} // end of checking if button is disabled


	});	
	
})( jQuery )	