function checkPassword(pwd, toConsole)
{
    var pwfeedback = document.getElementById('pwfeedback');
    var pwscore = document.getElementById('pwscore');
    var pwscorediv = document.getElementById('pwscorediv');
    var confpwmatch = document.getElementById('confpwmatch');
	
    if (pwd != '')
    {
        var ret = zxcvbn(pwd);

        // send result to console
        if (toConsole)
        {
            /*
            if (console.dir)
                console.dir(ret);
            else
                if (console.log)
                    console.log(ret);
            */

            var explanation = document.getElementById('explanation');
            explanation.innerHTML  = 'How the password "<strong>'+pwd+'</strong>" was broken into parts:\n';
            explanation.innerHTML += objectDump(ret.match_sequence);;
        }
	
	var patt1 = /[a-z]+/g;
	var patt2 = /[A-Z]+/g;
	var patt3 = /[0-9]+/g;
	var patt4 = /[\!\@\#\$\%\^\&\*\(\)\_\-\+\=]+/g;
	var pattcount = 0;
	if (patt1.test(pwd)) pattcount = pattcount + 1;
	if (patt2.test(pwd)) pattcount = pattcount + 1;
	if (patt3.test(pwd)) pattcount = pattcount + 1;
	if (patt4.test(pwd)) pattcount = pattcount + 1;

	if (pattcount<3) { ret.score = 2; }

	submitbutton = '<input type="submit" name="submit_form" value="Change Password" class="submitbutton" onclick="document.getElementById(\'passwordform\').submit();">';

	scorestring = '';
	switch(ret.score) {
		case 0: scorestring = 'Weak'; break;
		case 1: scorestring = 'Weak'; break;
		case 2: scorestring = 'Fair'; break;
		case 3: scorestring = 'Strong &#10004;'; break;
		case 4: scorestring = 'Very Strong &#10004;'; break;
	}
	pwscore.innerHTML = "Strength: <b>" + scorestring + "</b>";
	pwscore.className = 'pwscore pwscore'+(ret.score+1);
	pwscorediv.className = 'pwscore pwscore'+(ret.score+1);

        pwfeedback.innerHTML = '';
	if (ret.feedback.warning) pwfeedback.innerHTML += ret.feedback.warning + '. ';
	var temp = '' + ret.feedback.suggestions;
	temp = temp.replace('.,', '. ');
	if (temp && temp.slice(-1) != '.') { temp += '. '; }
	if (temp && temp.slice(-1) == '.') { temp += ' '; }
	if (ret.feedback.suggestions) pwfeedback.innerHTML += temp;
	if (pattcount<3) { pwfeedback.innerHTML += '<b>Must have at least 3 out 4 of: lowercase, uppercase, digits, and special characters.</b>'; }
	var newpw = document.getElementById('newpw').value;
	var confpw = document.getElementById('confpw').value;

	if (confpw) {
		if (newpw != confpw) { confpwmatch.innerHTML = '<b style="color: #900; background-color: #fcc;">&nbsp;&larr; mismatch &times;&nbsp;</b>'; }
		else { confpwmatch.innerHTML = '<b style="color: #090;"> &#10004; </b>'; }
	} else { 
		confpwmatch.innerHTML = ''; 
	}

    	var pwsubmitbutton = document.getElementById('submitbutton');
	if (ret.score > 2 && newpw == confpw) { 
		pwsubmitbutton.style.visibility = "visible";
		pwsubmitbutton.disabled = false;
		pwsubmitbutton.className = 'pass';
	} else {
		pwsubmitbutton.style.visibility = "visible";
		pwsubmitbutton.disabled = true;
		pwsubmitbutton.className = 'fail';
	}
    }
    else
    {
        pwscore.innerHTML = '';
	pwscore.className = '';
	pwscorediv.className = '';
        pwfeedback.innerHTML  = '<span style="color: #aaa;"> &nbsp; &nbsp; &uarr; start typing a password</span>';
    }
}

checkPassword(document.getElementById('newpw').value, false);