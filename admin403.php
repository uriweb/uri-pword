<?php

	require_once 'config.php';

// 	$page_title = 'Password Change';
// 	$display_title = 'Password Change';
// 	$display_description = 'Information and Technology Services';

	$body = 'inc/form-select-service.php';
	
	if ( isset ( $_POST['type'] ) && isset ( $_POST['action'] ) ) {
		
		if ( $_POST['type'] === 'uri' ) {
			// change or reset
			if ( $_POST['action'] === 'change' ) {
				header('Location: ' . $base_path . 'self/');
			} else {
				$body = 'inc/uri-reset.php';
			}
		}

		if ( $_POST['type'] === 'ecampus' ) {
			$body = 'inc/ecampus.php';
		}

		if ( $_POST['type'] === 'etal' ) {
			$body = 'inc/etal.php';
		}
		
	}


	include_once('inc/header.php');

?>

<div style="font-family: monospace; padding: 1em; font-size: 12pt; font-weight: bold; background-color: #900; color: #fff; margin-bottom: 2rem">
<h2 style="color: #fff">THIS IS A DEVELOPMENT SITE &mdash; PLEASE NOTE:</h2>
<ul>
	<li>Pages are not viewable by the public</li>
	<li>Pages still affect actual accounts/passwords</li>
</ul>
</div>


<hr>
<h2>Administrative Password Change</h2>
(Help change someone else's password)
<hr>

<div style="margin: 1em; padding: 1em; border: 1px solid #009; background-color: #eef; font-weight: bold;">
<strong>This device/computer is not registered to use this function.</strong>
</div>

<div style="margin: 1em; padding: 1em; border: 2px solid #900; background-color: #fdd; font-weight: bold;">
Repeated, unauthorized attempts to use this function will result in a network block.
</div>

<div style="margin: 1em; padding: 1em; border: 1px solid #999; background-color: #eee;">
If you feel this is in error, please contact the <a href='https://web.uri.edu/itservicedesk/'>IT Service Desk</a> (helpdesk@uri.edu, 874-4357)<br>
and <strong>include the below diagnostic information</strong>:<br>
<br>

<pre style="text-align: left; width: 250px; margin: auto; padding: 1em; border: 1px dotted #999; background-color: #fff;">
R: <?php echo $_SERVER['REMOTE_ADDR']; ?>&nbsp;
X: <?php echo $_SERVER['HTTP_X_FORWARDED_FOR']; ?>&nbsp;
C: <?php echo $_SERVER['HTTP_CLIENT_IP']; ?>&nbsp;
T: <?php echo date("Y-m-d g:ia"); ?>&nbsp;
   <?php echo date("e"); ?>&nbsp;
</pre>
</div>


<?php
	include_once('inc/footer.php');
