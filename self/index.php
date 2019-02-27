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
    $message_str = '<p>' . join("</p>\n<p>", $messages) . "</p>\n";
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

$message_str = '<div class="' . $message_class . '"><strong>' . $message_str . "</strong></div>";

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


/**
 * That's it for set up, let's render the page
 */

require_once '../config.php';


$page_title = 'Self-Serve Password Change';
$display_title = 'Self-Serve Password Change';
$display_description = 'Information and Technology Services';

// add the root password change to the breadcrumb
$breadcrumbs[] = array('href' => $base_path, 'text' => 'Password Change');

include_once('../inc/header.php');

?>
	
	<article class="page">
		<header class="entry-header">
			<h1 class="entry-title">Change Your URI Password</h1>
		</header>

		<?php echo $message_str; ?> 
		
		<?php if ( $status ) {
		
				include_once('../inc/form-self-success.php');
			
			} else {
			
				include_once('../inc/form-self.php');
				
			}
		?>

	</article>

<?php
	
	include_once('../inc/footer.php');
