(function( $ ) {
	
	$('.WebdWoocommerceCrosellPopup .nav-tab-wrapper a').click(function(e){
		
		e.preventDefault();
		
		if($(this).hasClass("gopro") ){
			$(".WebdWoocommerceCrosellPopup form").hide();
		}else $(".WebdWoocommerceCrosellPopup form").fadeIn();
	
	});
	
})( jQuery )	