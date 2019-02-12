/**
 * Popups
 */

( function() {
	
	window.addEventListener( 'load', init, false );
	
	function init() {
		
		var popups, i;
		
		popups = document.querySelectorAll( '.popup' );

		for ( i = 0; i < popups.length; i++ ) {
			popups[i].classList.add( 'has-js' );
			control( popups[i], popups[i].querySelector( '.popup-trigger' ) );
		}
		
	}
	
	function control( el, trigger ) {
		
		var classname = 'open';
		
		trigger.addEventListener( 'click', function() {
			
			if ( el.classList.contains( classname ) ) {
				el.classList.remove( classname );
			} else {
				el.classList.add( classname );
			}
			
		}, false );
		
	}
	
})();