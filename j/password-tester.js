/**
 * Test passwords for strength and matchiness
 */

(function(){

	window.addEventListener( 'load', initPasswordChecker, false );

	var pwscore = document.getElementById('pwscore');
	var pwscorediv = document.getElementById('pwscorediv');
	var pwfeedback = document.getElementById('pwfeedback');
	var confpwmatch = document.getElementById('confpwmatch');


	var newpw, confpw, strong = false, match = false;


	function initPasswordChecker() {

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
	
	function passwordsMatch() {
		if ( newpw && confpw && newpw == confpw ) {
			return true;
		} else {
			return false;
		}
	}


	function getScoreText(s) {
		var scores = ['Weak ☹️', 'Weak 🙁', 'Fair 😕', 'Strong 😀', 'Very Strong 💯'];
		if( s >= 0 && s < scores.length) {
			return s + ' ' + scores[s];
		} else {
			return scores[0];
		}
	}

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

	function displayStrength(score, isStrong) {
		// cap score at 2 is URI conditions are not met
		if ( ! isStrong && score > 2) {
			score = 2;
		}
		pwscore.innerHTML = "Strength: <strong>" + getScoreText( score ) + "</strong>";
		pwscorediv.className = 'pwscore pwscore' + ( score );
	}


	function setSubmitStatus(strong, match) {
		var b = document.getElementById('submitbutton');
	
		if ( strong && match ) { 
			b.disabled = false;
			b.className = 'pass';
		} else {
			b.disabled = true;
			b.className = 'fail';
		}
	}


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

	function clearMessages() {
		pwscore.innerHTML = '';
		pwscorediv.className = '';
		pwfeedback.innerHTML  = '';
	}


	function checkPassword() {

		newpw = document.getElementById('newpw').value;
		confpw = document.getElementById('confpw').value;

		if (newpw != '') {
			
			var ret = zxcvbn( newpw );
			var testsPassed = testPassword();

			match = passwordsMatch();

			if( testsPassed >= 4 && ret.score > 3) {
				strong = true;
			}
	
			// see if we achieve very strong status
// 			if( strong && /.{8}/.test(pwd) ) {
// 				 testsPassed++;
// 			}
	
			displayStrength( ret.score, strong );
			displayFeedback( ret, testsPassed );
			displayMatchMessage();
			setSubmitStatus( strong, match );
	
	
		} else {
			clearMessages();
		}

	}


})()
