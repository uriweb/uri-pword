<!-- ================================ BEGIN PAGE CONTENT ================================ -->	
					
	<article class="page">
		
		<header class="entry-header">
			<!--<h1 class="entry-title">Change Your Password</h1>-->
		</header>
		
		<div class="entry-content">
			
			<div class="breakout">
				<form id="pword-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<!-- <img src="src/service-desk.jpg" alt="a man offering technical support" /> -->
					<h1>Change your password</h1>
					<ul class="form-slider">
						<li class="input-group" id="pword_type_group">
							<h2>What account are you changing?</h2>
							<input id="type-uri" class="radio" name="type" type="radio" value="uri" checked><label for="type-uri">All, other than e-Campus</label>
							<input id="type-ecampus" class="radio" name="type" type="radio" value="ecampus"><label for="type-ecampus">e-Campus</label>
							<input id="type-etal" class="radio" name="type" type="radio" value="etal"><label for="type-etal">Etal email</label>
							<button id="pword-next" type="button" class="form-nav">Next</button>
						</li>
						<li class="input-group" id="pword_action_group">
							<h2>What do you want to do?</h2>
							<input id="action-change" class="radio" name="action" type="radio" value="change" checked><label for="action-change">Change it myself</label>
							<input id="action-reset" class="radio" name="action" type="radio" value="reset"><label for="action-reset">Reset password</label>
							<button id="pword-previous" type="button" class="form-nav">Back</button>
							<input id="pword-submit" class="button" type="submit" value="Go">
						</li>
					</ul>
					<div class="form-help">
						<h3>How you authenticate at URI is changing.</h3>
						<p>URI is moving toward single sign-on for authentication, and there are now more self-service options for resetting or changing your password.</p>
						<h3>Need help?</h3>
						<p>The <a href="https://web.uri.edu/itservicedesk/">IT Service Desk</a> is standing by. 401.874.4357</p> 
					</div>
				</form>
				
			</div>
			
		</div>
	
	</article>

	<script type="text/javascript" src="<?php print $base_path; ?>j/pwords.js"></script>
					
<!-- ================================ END PAGE CONTENT ================================ -->	
