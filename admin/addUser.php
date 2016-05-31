<?php
	//include config
	require_once('../includes/config.php');

	//if not logged in redirect to login page
	if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Admin - Add User</title>
		<link rel="stylesheet" href="../style/normalize.css">
		<link rel="stylesheet" href="../style/main.css">
	</head>
	<body>
		<div id="wrapper">
			<?php include('menu.php');?>
			<p><a href="users.php">User Admin Index</a></p>
			<h2>Add User</h2>
			<?php
				if(isset($_POST['submit'])){
					$_POST=array_map('stripslashes', $_POST);
					extract($_POST);
					$stmt=$dbConn->prepare('SELECT username FROM blog_members WHERE username=:username');
					$stmt->bindParam(':username', $username);
					$stmt->execute();
					$sameUsername=$stmt->fetchAll();
					$stmt=$dbConn->prepare('SELECT email FROM blog_members WHERE email=:email');
					$stmt->bindParam(':email', $email);
					$stmt->execute();
					$sameEmail=$stmt->fetchAll();
					if($username==''){
						$error[]='Please enter a username';
					}
					if($sameUsername!=0){
						$error[]='Username already exists';
					}
					if($email==''){
						$error[]='Please enter email';
					}
					if($sameEmail!=0){
						$error[]='Email already exists';
					}
					if($password==0){
						$error[]='Please enter a password';
					}
					if($confirmPassword==0){
						$error[]='Please confirm your password';
					}
					if($password!=$confirmPassword){
						$error[]='Your confirmation password doesn\'t match';
					}
					if(!isset($error)){
						$hashedPassword=$user->create_hash($password);
						try{
							$stmt=$dbConn->prepare('INSERT INTO blog_members VALUES(:username, :email, :password)');
							$stmt->bindParam(':username', $username);
							$stmt->bindParam(':email', $email);
							$stmt->bindParam(':password', $hashedPassword);
							$stmt->execute();
							header('Location: index.php?action=added');
							exit;
						} catch(PDOException $ex){
							$ex->getMessage();
						}
					}
					//check for any errors
					if(isset($error)){
						foreach($error as $error){
							echo '<p class="error">'.$error.'</p>';
						}
					}
				}
			?>
			<form action='' method='post'>
				<p><label>Username</label><br/>
				<input type='text' name='username' value='<?php if(isset($error)){echo $_POST['username'];}?>'/></p>
				<p><label>Email</label><br/>
				<input type='email' name='email' value='<?php if(isset($error)){echo $_POST['email'];}?>'/></p>
				<p><label>Password</label><br/>
				<input type='password' name='password' value='<?php if(isset($error)){echo $_POST['password'];}?>'/></p>
				<p><label>Confirm Password</label><br/>
				<input type='password' name='confirmPassword' value='<?php if(isset($error)){echo $_POST['confirmPassword'];}?>'/></p>
				<p><input type='submit' name='submit' value='Add User'/></p>
			</form>
		</div>
	</body>
</html>
