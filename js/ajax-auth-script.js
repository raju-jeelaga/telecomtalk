jQuery(document).ready(function ($) {
    
	// Close popup
    //$(document).on('click', '.login_overlay, .close', function () {
		//$('form#login, form#register').fadeOut(500, function () {
            //$('.login_overlay').remove();
        //});
        //return false;
    //});

	// Perform AJAX login/register on form submit
	$('form#login, form#register').on('submit', function (e) {
        if (!$(this).valid()) return false;
        $('p.status', this).show().text(ajax_auth_object.loadingmessage);
		//action = 'ajaxlogin';
		//username = 	$('form#login #username').val();
		//password = $('form#login #password').val();
		//email = '';
		security = $('form#login #security').val();
		if ($(this).attr('id') == 'register') {
			action = 'ajaxregister';
			username = $('#signonname').val();
			password = $('#signonpassword').val();
        	email = $('#user_email').val();
        	security = $('#signonsecurity').val();	
		}  
		ctrl = $(this);
		$.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_auth_object.ajaxurl,
            data: {
                'action': action,
                'username': username,
                'password': password,
				'email': email,
                'security': security
            },
            success: function (data) {
				$('p.status', ctrl).text(data.message);
				if (data.loggedin == true) {
                    //document.location.href = ajax_auth_object.redirecturl;
                    location.reload();
                }
            }
        });
        e.preventDefault();
    });
	
	// Client side form validation
    if (jQuery("#register").length) 
		jQuery("#register").validate(
		{ 
			rules:{
			password2:{ equalTo:'#signonpassword' 
			}	
		}}
		);
    else if (jQuery("#login").length) 
		jQuery("#login").validate();
});