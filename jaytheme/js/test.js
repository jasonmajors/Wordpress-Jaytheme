jQuery( document ).ready(function() {

	var $nav = jQuery( ".contender" );
	var $defaultText = jQuery( ".hero-description-fixed.default-text" );

	$nav.mouseenter(function() {
		var targetHero = jQuery(this).attr( "data-targethero" );
		jQuery( ".hero-description-fixed." + targetHero ).show();
		$defaultText.hide();
	});

	$nav.mouseleave(function() {
		jQuery( ".hero-description-fixed" ).hide();
		$defaultText.show();
	});

	$nav.hover(function() {
		jQuery(this).toggleClass('deep-shadow');
	});
});
