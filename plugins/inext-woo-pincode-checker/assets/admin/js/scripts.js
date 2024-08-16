jQuery(document).ready(function($) {
	/** Tooltip **/
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl)
	});

	// Tabs
	$( '.inext_wpc_wrapper .nav-tabs .nav-item .nav-link' ).on( 'click', function() {
        var el = $(this);
        var el_parent = el.attr('data-parent');
        var el_toggle = el.attr('data-toggle');
        var el_target = el.attr('data-target');
        var el_content = el.attr('data-parent') + '_content';

        $('#' + el_parent + '.nav-tabs').find('.nav-link').removeClass('active');
        el.addClass('active');

        $('#' + el_content).find('.tab-pane').removeClass('active show');
        $('#' + el_content).find('#' + el_target + '.tab-pane').addClass('active show');
	});

    // Check Pincode
	$('.inext_wpc_wrapper form').on('submit', function(e){
		e.preventDefault();
		var el = $(this);
		var submit_btn = el.find('.submit');
		var form_data = el.serializeArray().reduce(function(obj, i) { obj[i.name] = i.value; return obj; }, {});
		// var nonce = el.find('input[data-type="nonce"]').val();
		// var action = el.find('input[data-type="action"]').val();
		var el_notice = $('.inext_wpc_notices');

		dataString = form_data;
		// console.log(dataString);

        $.ajax({
            data: dataString,
            type: "POST",
            url: inext_wpc_admin_ajax_variables.adminajaxurl,
            beforeSend: function() {
				submit_btn.addClass('disabled');
				submit_btn.append(loader);
            },
            success: function(response) {
				response = JSON.parse(response);
				submit_btn.removeClass('disabled');
				submit_btn.find('.' + loader_class).remove();
				if(response.status){
					el_notice.html(inextAlert({'heading':response.msg, 'status':'success'}));
				}
				else{
					el_notice.html(inextAlert({'heading':response.msg, 'status':'error'}));
				}

				setTimeout(function(){
					el_notice.html('');
				}, 5000);
                // console.log(response);
            },
    		error: function(error){
    			console.log(error);
    		}
        });
	});
});
