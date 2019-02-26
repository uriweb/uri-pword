<?php
/** 
 * Form body for the administrative password change.
 */
 
?>


<form class="modern-form" method="post" enctype="multipart/form-data" onkeypress="return event.keyCode != 13;">
	<fieldset>
		<legend>Administration</legend>
		
		<div class="form-row">

		<h3>Reset Password For Which Service(s)?</h3>
		
		<input type="checkbox" id='gafe' name="gafe" <?php echo $gafe_check; ?> /> <label for="gafe">Google / @etal</label>
		<div class="form-description">Gmail, Google Drive, etc.</div>
		
		<div id="hideifetal">
			<input type="checkbox" id='ldap' name="ldap" <?php echo $ldap_check; ?> /> <label for="ldap">LDAP</label>
			<div class="form-description">Sakai, Wifi, Wordpress, etc.</div>
		
			<input type="checkbox" id='ad' name="ad" <?php echo $ad_check; ?> /> <label for="ad">Active Directory</label>
			<div class="form-description">AD, network shares</div>
		</div>

		<!-- I don't know what this does -JP -->
		<span id="showifetal" style='display: none;'></span>
		
		</div>
		
		<div class="form-row">
			<label for="admin">Administrator's Email:</label>
			<input type="text" id="admin" name="admin" size="30" maxlength="120" <?php echo $admin_value; ?> />
		</div>
		
		<div class="form-row">
			<label for="adminpw">Administrator's Password:</label>
			<input type="password" name="adminpw"id="adminpw" size="30" maxlength="120" <?php echo $adminpw_value; ?> />
		</div>

	</fieldset>

	<hr>

	<fieldset>
		<legend>User Account</legend>
		<div class="form-row">
			<label for="user">User URI Email Address:</label>
			<input type="text" id="user" name="user" size="40" maxlength="64" <?php echo $user_value; ?> />
		</div>

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
			<label for="newpw">New Password:</label>
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
			<label for="confpw">Repeat New Password:</label>
			<input type="password" id='confpw' name="confpw" size="33" maxlength="32" <?php echo $confpw_value; ?> />
			<div class="form-description"></div>
			<div class="feedback match">
				<span id='confpwmatch'></span>
			</div>
		</div>


		<input type="submit" name="submit_form" value="Submit" id="submitbutton" class="button" />

	</fieldset>

</form>

<script type="text/javascript" src="<?php print $base_path; ?>j/zxcvbn.js"></script>
<script type="text/javascript" src="<?php print $base_path; ?>j/password-tester.js"></script>
<script type="text/javascript" src="<?php print $base_path; ?>j/popup.js"></script>
