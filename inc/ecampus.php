<!-- ================================ BEGIN PAGE CONTENT ================================ -->	
					
	<article class="page">
		
		<header class="entry-header">
			<h1 class="entry-title">Change Your e-Campus Password</h1>
		</header>
		
		<div class="entry-content">
		
			<?php
				if ( $_POST['action'] === 'reset' ) {
				?>
					<p>You forgot your e-Campus password, and want to reset it.</p>
					<a class="cl-button prominent" href="https://appsaprod.uri.edu:9503/psp/sahrprod_m2/EMPLOYEE/HRMS/c/MAINTAIN_SECURITY.EMAIL_PSWD.GBL?">Reset e-Campus password</a>
				<?php
				} else {
				?>
					<p>Sign into e-Campus to change your password.</p>
					<a class="cl-button prominent" href="https://appsaprod.uri.edu:9503/psp/sahrprod_m2/?cmd=login">Sign into e-Campus</a>
				<?php
				}

			?>
			
			<div id="start-over"><a href="<?php echo $base_path; ?>" class="cl-button">Start Over</a></div>
						
		</div>
	
	</article>
					
<!-- ================================ END PAGE CONTENT ================================ -->	