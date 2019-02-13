/**
 * Test passwords for strength and matchiness
 */

(function(){

	window.addEventListener( 'load', initPasswordChecker, false );

	var pwscore = document.getElementById('pwscore');
	var pwscorediv = document.getElementById('pwscorediv');
	var pwscoredivParent = pwscorediv.parentElement;
	var pwfeedback = document.getElementById('pwfeedback');
	var confpwmatch = document.getElementById('confpwmatch');


	var newpw, confpw, strong, match;


	/**
	 * Sets up event listeners.
	 * left the existing events in place
	 * @return bool
	 */
	function initPasswordChecker() {

		setSubmitStatus();
		checkPassword();
		
		var np = document.getElementById('newpw');
		np.addEventListener( 'change', function() { checkPassword() }, false );
		np.addEventListener( 'keypress', function() { checkPassword() }, false );
		np.addEventListener( 'input', function() { checkPassword() }, false );

		var cp = document.getElementById('confpw');
		cp.addEventListener( 'change', function() { checkPassword() }, false );
		cp.addEventListener( 'keypress', function() { checkPassword() }, false );
		cp.addEventListener( 'input',function() { checkPassword() }, false );

	}
	
	/**
	 * Tests if the new password and password confirmation match.
	 * @return bool
	 */
	function passwordsMatch() {
		if ( newpw && confpw && newpw == confpw ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Returns a text string associated with a score from 0-4
	 * @param int s is a number from 0-4 indicating password strength
	 * @return str
	 */
	function getScoreText(s) {
		var scores = ['Weak ☹️', 'Weak 🙁', 'Fair 😕', 'Strong 😀', 'Very Strong 💯'];
		if( s >= 0 && s < scores.length) {
			return scores[s];
		} else {
			return scores[0];
		}
	}

	/**
	 * Tests the new password against a few regexes to satisfy URI's requirements
	 * NB: As of 2019-02, URI requires that any three of the four patterns match
	 * returns the number of tests passed
	 * @return int
	 */
	function testPassword() {
		var passed = 0;
		var tests = [/[a-z]+/g, /[A-Z]+/g, /[0-9]+/g, /[\!\@\#\$\%\^\&\*\(\)\_\-\+\=]+/g];
		for (var i=0; i<tests.length; i++) {
			if ( tests[i].test( newpw ) ) {
				 passed++;
			}
		}
		return passed;
	}

	/**
	 * Displays the matching text.
	 * whether or not the password matches
	 */
	function displayMatchMessage() {
		if (confpw) {
			if (match) {
				confpwmatch.innerHTML = '<strong class="yes">Passwords Match</strong>';
			} else {
				confpwmatch.innerHTML = '<strong class="no">Passwords do not match</strong>';
			}
		} else { 
			confpwmatch.innerHTML = ''; 
		}
	}

	/**
	 * Displays the password's strength.
	 * displays a message in text as well as changes a className
	 * @param int score is the passwords 0-4 score
	 * @param bool isStrong indicates whether or not the password is acceptable to URI
	 * @return int
	 */
	function displayStrength(score, isStrong) {
		// cap score at 2 is URI conditions are not met
		if ( ! isStrong && score > 2) {
			score = 2;
		}
		pwscore.innerHTML = "Strength: <strong>" + getScoreText( score ) + "</strong>";
		pwscorediv.className = 'pwscore pwscore' + ( score );
	}


	/**
	 * Sets the status of the submit button from disabled to enabled and back
	 */
	function setSubmitStatus() {
		var b = document.getElementById('submitbutton');
	
		if ( strong && match ) { 
			b.setAttribute('disabled', false);
		} else {
			b.setAttribute('disabled', true);
		}
	}


	/**
	 * Displays feedback text to encourage a stronger password
	 * @param obj ret is the zxcvbn object
	 * @param bool testsPassed indicates the number of URI tests passed
	 * @return int
	 */
	function displayFeedback(ret, testsPassed) {
		pwfeedback.innerHTML = '';

		if (ret.feedback.warning) pwfeedback.innerHTML += ret.feedback.warning + '. ';

		var temp = '' + ret.feedback.suggestions;
		temp = temp.replace('.,', '. ');
	
		if (temp && temp.slice(-1) != '.') {
			temp += '. ';
		}
		if (temp && temp.slice(-1) == '.') {
			temp += ' ';
		}
		if (ret.feedback.suggestions) {
			pwfeedback.innerHTML += temp;
		}
		if (testsPassed < 3) {
			pwfeedback.innerHTML += '<strong>Must have at least 3 out 4 of: lowercase, uppercase, digits, and special characters.</strong>';
		}
	}

	/**
	 * Clears password messages, status, and suggestions.
	 */
	function clearMessages() {
		pwscore.innerHTML = '';
		pwscorediv.className = '';
		pwfeedback.innerHTML  = '';
	}


	/**
	 * Checks the new password and displays appropriate messages.
	 */
	function checkPassword() {

		newpw = document.getElementById('newpw').value;
		confpw = document.getElementById('confpw').value;

		if (newpw != '') {
			
			pwscoredivParent.classList.add( 'active' );
			
			var ret = zxcvbn( newpw );
			var testsPassed = testPassword();
			
			// re-calculate these every time
			match = false;
			strong = false;

			match = passwordsMatch();
			
			if( testsPassed >= 3 && ret.score > 3) {
				strong = true;
			}
	
			displayStrength( ret.score, strong );
			displayFeedback( ret, testsPassed );
			displayMatchMessage();
			setSubmitStatus( strong, match );
	
	
		} else {
			pwscoredivParent.classList.remove( 'active' );
			clearMessages();
		}

	}


})()
