/**
 * Make the form pretty
 */

( function() {
		
	window.addEventListener( 'load', init, false );
	
	function init() {
		
		var els = {
			'form': document.getElementById( 'pword-form' ),
			'next': document.getElementById( 'pword-next' ),
			'previous': document.getElementById( 'pword-previous' ),
		}
		
		els.form.classList.add( 'has-js' );
		
		els.next.addEventListener( 'click', function() {
			els.form.classList.add( 'next' );
		}, false );
		
		els.previous.addEventListener( 'click', function() {
			els.form.classList.remove( 'next' );
		}, false );
		
	}
	
})();