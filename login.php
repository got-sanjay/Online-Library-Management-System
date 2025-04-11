<?php if(!isset($Translation)){ @header('Location: index.php?signIn=1'); exit; } ?>
<?php include_once("$currDir/header-start.php"); ?>
<?php
$prefill_username = isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '';
?>
<style>
	.animated-alert {
	opacity: 0;
	transform: translateY(-20px);
	transition: all 0.6s ease;
	position: relative;
	z-index: 1000;
	margin-top: 20px;
}

.animated-alert.show {
	opacity: 1;
	transform: translateY(0);
}

</style>
<div class="row">
	<div class="col-sm-6 col-lg-8" id="login_splash">
		<!-- customized splash content here -->
	</div>
	<div class="col-sm-6 col-lg-4">
		<div class="panel panel-success">

			<div class="panel-heading">
				<h1 class="panel-title"><strong><?php echo $Translation['sign in here']; ?></strong></h1>
				<?php if(sqlValue("select count(1) from membership_groups where allowSignup=1")){ ?>
					<a class="btn btn-success pull-right" href="membership_signup.php"><?php echo $Translation['sign up']; ?></a>
				<?php } ?>
				<div class="clearfix"></div>
			</div>
			<?php if (isset($_GET['registered'])): ?>
				<div id="register-success" class="alert alert-success animated-alert">
					✅ Registration successful. Please log in.
				</div>
			<?php endif; ?>

			<?php if (isset($_GET['loginFailed'])): ?>
				<div id="login-failed" class="alert alert-danger animated-alert">
					❌ Login failed. Please check your credentials.
				</div>
			<?php endif; ?>

			<div class="panel-body">
				<form method="post" action="index.php">
					<div class="form-group">
						<label class="control-label" for="username"><?php echo $Translation['username']; ?></label>
						<input type="text" name="username" class="form-control" required
					   value="<?php echo $prefill_username; ?>" placeholder="Enter your username">
					</div>
					<div class="form-group">
						<label class="control-label" for="password"><?php echo $Translation['password']; ?></label>
						<input class="form-control" name="password" id="password" type="password" placeholder="<?php echo $Translation['password']; ?>" required>
						<span class="help-block"><?php echo $Translation['forgot password']; ?></span>
					</div>
					<div class="checkbox">
						<label class="control-label" for="rememberMe">
							<input type="checkbox" name="rememberMe" id="rememberMe" value="1">
							 <?php echo $Translation['remember me']; ?>
						</label>
					</div>

					<div class="row">
						<div class="col-sm-offset-3 col-sm-6">
							<button name="signIn" type="submit" id="submit" value="signIn" class="btn btn-primary btn-lg btn-block"><?php echo $Translation['sign in']; ?></button>
						</div>
					</div>
					<div class="form-group ">
						<div class="col-sm-offset-3 col-sm-6">
						<div class="text-center">
                        register a account? <a href="signup.php">signup here</a>
                    </div>
						</div>
					</div>
				</form>
			</div>

			<?php if(is_array(getTableList()) && count(getTableList())){ /* if anon. users can see any tables ... */ ?>
				<div class="panel-footer">
					<?php echo $Translation['browse as guest']; ?>
				</div>
			<?php } ?>

		</div>
	</div>
</div>

<script>document.getElementById('username').focus();</script>
<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
		// Show the alerts with animation
		setTimeout(() => {
			$('.animated-alert').addClass('show');
		}, 200); // Slight delay to trigger animation

		// Hide after 4 seconds with fade and slide effect
		setTimeout(() => {
			$('.animated-alert').removeClass('show').fadeOut(600);
		}, 4000);
	});
</script>