jQuery( document ).ready(function() {
	/**
	  * onload
	  */

	//bonus
	jQuery("input[name='woo_motivation_bonus_type']:checked").parent().addClass("active");
	if ( jQuery('input[type=radio][name=woo_motivation_bonus_type]:checked').val() == 'discount' ){
		jQuery('.main-discount-section').addClass("active");
		jQuery('.main-gift-section').removeClass("active");			
	}
	else if ( jQuery('input[type=radio][name=woo_motivation_bonus_type]:checked').val() == 'none' ){
		jQuery('.main-discount-section').removeClass("active");
		jQuery('.main-gift-section').removeClass("active");			
	}
	else {
		jQuery('.main-discount-section').removeClass("active");
		jQuery('.main-gift-section').addClass("active");
	}

	//notice
	jQuery("input[name='woo_motivation_notice_type']:checked").parent().addClass("active");

	/* onclick */
	jQuery('input[type=radio][name=woo_motivation_bonus_type]').click(function() {
		jQuery("input[name='woo_motivation_bonus_type']").parent().removeClass("active");
		jQuery("input[name='woo_motivation_bonus_type']:checked").parent().addClass("active");
		if ( jQuery(this).val() == 'discount' ){
			jQuery('.main-discount-section').addClass("active");
			jQuery('.main-gift-section').removeClass("active");			
		}
		else if ( jQuery(this).val() == 'none' ){
			jQuery('.main-discount-section').removeClass("active");
			jQuery('.main-gift-section').removeClass("active");			
		}
		else {
			jQuery('.main-discount-section').removeClass("active");
			jQuery('.main-gift-section').addClass("active");
		}
	});
	jQuery('input[type=radio][name=woo_motivation_notice_type]').click(function() {
		jQuery("input[name='woo_motivation_notice_type']").parent().removeClass("active");
		jQuery("input[name='woo_motivation_notice_type']:checked").parent().addClass("active");
		/*if ( jQuery(this).val() == 'success' ){
			jQuery('.main-discount-section').addClass("active");
			jQuery('.main-gift-section').removeClass("active");			
		}
		else if ( jQuery(this).val() == 'none' ){
			jQuery('.main-discount-section').removeClass("active");
			jQuery('.main-gift-section').removeClass("active");			
		}
		else {
			jQuery('.main-discount-section').removeClass("active");
			jQuery('.main-gift-section').addClass("active");
		}*/
	});

	/* CTA */
	jQuery('.woo_motivation_nav a').click(function(event) {
		event.preventDefault();
		var id = jQuery(this).attr('href');
		jQuery('.woo_motivation_content > div').removeClass("active");
		jQuery('.woo_motivation_nav > li > a').removeClass("active");
		jQuery(this).addClass("active");
		jQuery(id).addClass("active");
	});	
});
