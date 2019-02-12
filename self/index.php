<?php

# The machine running the pw change daemon
$GLOBALS['SERVER_HOST'] = "soupy.ucs.uri.edu";

# The port the pw change daemon listens on
$GLOBALS['SERVER_PORT'] = 2012;

# Assume self change version because that is not restricted by ip address
$is_self = 1;

##############################################################################

// Default to having no services selected
$gafe_check = "";
$ldap_check = "";
$ad_check = "";

// Default to all text fields cleared
$admin_value = '';
$adminpw_value = '';
$user_value = '';
$currpw_value = '';
$newpw_value = '';
$confpw_value = '';

// There is no feedback message by default
$message_str = "";

// If this is a submission, perform it
if (isset($_REQUEST['submit_form'])) {
  // This array contains the fields submitted for reset. They are sent one
  // per line in the following order:
  //   - remote ip address for logging
  //   - services: combination of letters 'g', 'l', and 'a' in any order
  //   - reset type: either 'self' or 'admin'
  //   - if 'admin' type, the 5 lines:
  //        admin id, admin pw, user id, new password, confirm pw
  //   - if 'self' type, the 4 lines:
  //        user id, current password, new password, confirm pw
  $fields = array();

  // Add the remote ip address
  array_push($fields, $_SERVER['REMOTE_ADDR']);

  // Retain the current service selections regardless of outcome
  // and create the string to send to server
  $services = "";
  if (isset($_REQUEST['gafe'])) {
    $gafe_check = " checked ";
    $services .= "g";
  }
  if (isset($_REQUEST['ldap'])) {
    $ldap_check = " checked ";
    $services .= "l";
  }
  if (isset($_REQUEST['ad'])) {
    $ad_check = " checked ";
    $services .= "a";
  }
  array_push($fields, $services);

  // The rest depends on self or admin
  if ($is_self) {
    // Add the reset type
    array_push($fields, "self");
    // Add the rest of the fields
    array_push($fields, $_REQUEST['user']);
    array_push($fields, $_REQUEST['currpw']);
    array_push($fields, $_REQUEST['newpw']);
    array_push($fields, $_REQUEST['confpw']);
  }
  else {
    // Add the reset type
    array_push($fields, "admin");
    // Add the rest of the fields
    array_push($fields, $_REQUEST['admin']);
    array_push($fields, $_REQUEST['adminpw']);
    array_push($fields, $_REQUEST['user']);
    array_push($fields, $_REQUEST['newpw']);
    array_push($fields, $_REQUEST['confpw']);
  }

  // Send to server, and get result
  list($status, $messages) = send_to_server($fields);

  // Get messages ready for display
  if (count($messages) > 0) {
    $message_str =
        '<center><p>' . join("</p>\n<p>", $messages) . "</p></center>\n";
  }

  // If failed, retain the text fields and present any messages in red
  if (!$status) {
    // Retain the fields on failure
    if (!$is_self) {
      $admin_value = ' value="' . htmlspecialchars($_REQUEST['admin']) . '" ';
      $adminpw_value = ' value="' . htmlspecialchars($_REQUEST['adminpw']).'" ';
    }
    $user_value = ' value="' . htmlspecialchars($_REQUEST['user']) . '" ';
    if ($is_self) {
      $currpw_value = ' value="' . htmlspecialchars($_REQUEST['currpw']) . '" ';
    }
    $newpw_value = ' value="' . htmlspecialchars($_REQUEST['newpw']) . '" ';
    $confpw_value = ' value="' . htmlspecialchars($_REQUEST['confpw']) . '" ';

  }
}

// Make messages red on failure
if (strlen($message_str) > 0) {
        $message_class = $status ? "success" : "error";
} else {
        $message_class = "none";
}

$message_str = '<div class="' . $message_class . '"><b>' . $message_str . "</b></div>";

function send_to_server($fields)
{
  // Put all the data in a single string
  $server_data = join("\n", $fields) . "\n";

  # Connect to the server
  if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    return array(false, "Could not create socket.");
  }
  $result = socket_connect($sock, $GLOBALS['SERVER_HOST'],
                           $GLOBALS['SERVER_PORT']);
  if ($result === false) {
    return array(false, "Could not connect to server.");
  }

  # Read the public key from server
  $pk_raw = socket_read($sock, 2048);
  $pk = hex2bin($pk_raw);

  # Send encrypted data to server
  $encrypted = sodium_crypto_box_seal($server_data, $pk);
  socket_write($sock, $encrypted);

  # Get result from the server
  $raw_result = socket_read($sock, 2048);

  # Break it into lines
  $result = explode("\n", $raw_result);
  $status = false;
  if (array_shift($result) === "SUCCESS") {
    $status = true;
  }

  # Return result and any messages
  return array($status, $result);
}

?>


<?php

	require_once '../config.php';


	$page_title = 'Self-Serve Password Change';
	$display_title = 'Self-Serve Password Change';
	$display_description = 'Information and Technology Services';
	
	include_once('../inc/header.php');

?>
	
	<article class="page">
		
		<header class="entry-header">
			<h1 class="entry-title">Change Your URI Password</h1>
		</header>

		<p class="fullwidth type-intro">Authentication at URI is changing.</p>
		<p>This form changes your password for all of the following systems to the same password: Sakai, Wifi, Office 365, Wordpress, Gmail, Google Drive, Google Apps, Active Directory, Jabber and VoIP pages, and Network Shares. <strong>This page does not change your e-Campus password.</strong></p>

<hr>

<?php echo $message_str; ?> 

<?php if ($status) { ?>

<hr>

<h3>Quick Links:</h3>
<ul>
	<li><a href='https://sakai.uri.edu'>Sakai</a></li>
	<li><a href='https://accounts.google.com'>Google</a></li>
	<li><a href='https://portal.office.com'>Office 365 Portal</a><br>
	<small>(NOTE: password changes make take up to 30 minutes to take effect at the Office portal)</small></li>
</ul>

<?php } else { ?>

<form class="modern-form" id='passwordform' method="post" enctype="multipart/form-data" onkeypress="return event.keyCode != 13;">
	<fieldset>
		<legend>Enter your URI Credentials</legend>

		<div class="form-row">
			<label for="user">Your URI Email Address:</label>
			<input type="text" id="user" name="user" placeholder="sample@uri.edu" size="40" maxlength="64" <?php echo $user_value; ?> />
			<div class="form-description">
				Enter your full URI email address (e.g. sample@uri.edu or sample@my.uri.edu)
			</div>
		</div>

		<div class="form-row">
			<label for="currpw">Enter Current Password:</label>
			<input type="password" id="currpw" name="currpw" size="33" maxlength="32" <?php echo $currpw_value; ?> />
			<div class="form-description">
				Note: if you have not yet unified your passwords, enter your Sakai/Wifi/LDAP password here.
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

		<input type="submit" name="submit_form" value="Submit" id="submitbutton" class='fail' disabled>

	</fieldset>

	<input type="checkbox" id='gafe' name="gafe" $gafe_check style='visibility: hidden; height: 1px; margin: 0;'/>
	<input type="checkbox" id='ldap' name="ldap" $ldap_check style='visibility: hidden; height: 1px; margin: 0;'/>
	<input type="checkbox" id='ad' name="ad" $ad_check style='visibility: hidden; height: 1px; margin: 0;'/>

</form>


<?php } ?>



<script type="text/javascript" src="<?php print $base_path; ?>j/zxcvbn.js"></script>
<script type="text/javascript" src="<?php print $base_path; ?>j/password-tester.js"></script>
<script type="text/javascript" src="<?php print $base_path; ?>j/popup.js"></script>


<?php

	include_once('../inc/footer.php');

?>
