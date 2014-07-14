jQuery( document ).ready(function() {

	console.log( "Ready! ");
	jQuery( ".contender" ).mouseenter(function() {
			var targetHero = jQuery(this).attr( "data-targethero" );
			jQuery( ".hero-description-fixed." + targetHero ).show();
		});

	jQuery( ".contender" ).mouseleave(function() {
			jQuery( ".hero-description-fixed" ).hide();
		});
});
