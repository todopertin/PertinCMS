<?php
	//include config
	require_once('../includes/config.php');

	//if not logged in redirect to login page
	if(!$user->is_logged_in()){header('Location: login.php');}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Admin Login</title>
		<link rel="stylesheet" href="../style/normalize.css">
		<link rel="stylesheet" href="../style/main.css">
	</head>
	<body>
		<div id="login">
			<?php
				if(isset($_POST['submit'])){
					$username=trim($_POST['username']);
					$password=trim($_POST['username']);
					if($user->login($username, $password)){
						//logged in return to index page
						header('Location: index.php');
						exit;
					} else{
						$message='<p class="error">Wrong username or password</p>';
					}
				}
				if(isset($message)){
					echo $message;
				}
			?>
			<form action='' method='post'>
				<p><label>Username</label><input type='text' name='username' value=''/></p>
				<p><label>Password</label><input type='password' name='password' value=''/></p>
				<p><label></label><input type='submit' name='submit' value='Login'/></p>
			</form>
		</div>
	</body>
</html>
