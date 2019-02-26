<?php
/** 
 * Form body for the self-service password change.
 */
 
?>

<p class="fullwidth type-intro">Authentication at URI is changing.</p>
<p>This form changes your password for all of the following systems to the same password: Sakai, Wifi, Office 365, Wordpress, Gmail, Google Drive, Google Apps, Active Directory, Jabber and VoIP pages, and Network Shares. <strong>This page does not change your e-Campus password.</strong></p>

<hr>

<?php echo $message_str; ?> 

<form class="modern-form" id='passwordform' method="post" enctype="multipart/form-data" onkeypress="return event.keyCode != 13;">
	<fieldset>
		<legend>Enter your URI Credentials</legend>

		<div class="form-row">
			<label for="user">Your URI Email Address:</label>
			<input type="text" id="user" name="user" placeholder="sample@uri.edu" size="40" maxlength="64" <?php echo $user_value; ?> />
			<div class="form-description">
				Your URI email address (e.g. sample@uri.edu or sample@my.uri.edu)
			</div>
		</div>

		<div class="form-row">
			<label for="currpw">Enter Current Password:</label>
			<input type="password" id="currpw" name="currpw" size="33" maxlength="32" <?php echo $currpw_value; ?> />
			<div class="form-description">
				Your URI password (previously called your Sakai password)
			</div>
		</div>
	
	</fieldset>

	<fieldset>
		<legend>Choose a New Password</legend>
		
		<div class="popup" id="pword-reqs">
			<div class="popup-trigger">
				<p>Password Requirements</p>
			</div>
			<div class="popup-container">
				<ul>
					<li>one uppercase letter</li>
					<li>one lowercase letter</li>
					<li>one numeral</li>
					<li>one special character, e.g. ! @ # $ % ^ & * ( ) - _ = +</li>
				</ul>
				<p><strong>Longer passwords</strong> are better (up to 32 characters), and strength must be <strong>"Strong" or better</strong></p>
				<a href='https://security.uri.edu/information-security-awareness/tipsforpasswords/' target='_blank'>Tips from the Office of Information Security</a>
			</div>
		</div>
		
		<div class="form-row">
			<label for="newpw">Choose A New Password:</label>
			<input type="password" id='newpw' name="newpw" size="33" maxlength="32" <?php echo $newpw_value; ?> />
			<div class="form-description"></div>
			<div class="feedback score">
				<div id='pwscorediv'>
					<span id='pwscore'></span>
				</div>
				<p class='pwfeedback' id='pwfeedback'></p>
			</div>
		</div>
		<div class="form-row">
			<label for="confpw">Enter Your New Password Again:</label>
			<input type="password" id='confpw' name="confpw" size="33" maxlength="32" <?php echo $confpw_value; ?> />
			<div class="form-description"></div>
			<div class="feedback match">
			<span id='confpwmatch'></span>
			</div>
		</div>

		<input type="submit" name="submit_form" value="Submit" id="submitbutton" class="button" />

	</fieldset>

	<input type="checkbox" id='gafe' name="gafe" $gafe_check style='visibility: hidden; height: 1px; margin: 0;'/>
	<input type="checkbox" id='ldap' name="ldap" $ldap_check style='visibility: hidden; height: 1px; margin: 0;'/>
	<input type="checkbox" id='ad' name="ad" $ad_check style='visibility: hidden; height: 1px; margin: 0;'/>

</form>



<script type="text/javascript" src="<?php print $base_path; ?>j/zxcvbn.js"></script>
<script type="text/javascript" src="<?php print $base_path; ?>j/password-tester.js"></script>
<script type="text/javascript" src="<?php print $base_path; ?>j/popup.js"></script>
