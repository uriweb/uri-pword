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

	include_once( $body );

	include_once('inc/footer.php');

?>
