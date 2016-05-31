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
		<title>Admin - Edit User</title>
		<link rel="stylesheet" href="../style/normalize.css">
		<link rel="stylesheet" href="../style/main.css">
	</head>
	<body>
		<div id="wrapper">
			<?php include('menu.php');?>
			<p><a href="users.php">User Admin Index</a></p>
			<h2>Edit User</h2>
			<?php
				if(isset($_POST['submit'])){
					$_POST=array_map('stripslashes', $_POST);
					extract($_POST);
					if($username=''){
						error[]='Please enter username';
					}
					if($email=''){
						error[]='Please enter email';
					}
					if(strlen($password)>0){
						if($password=''){
							$error[]='Please enter password';
						}
						if($confirmPassword=''){
							$error[]='Please confirm the password';
						}
						if($password!=$confirmPassword){
							$error[]='Passwords do not match';
						}
					}
					if(!isset($error)){
						try{
							if(isset($password)){
								$hashedPassword=$user->create_hash($password);
								$stmt=$dbConn->prepare('UPDATE blog_members SET username=:username, email=:email, password=:password WHERE memberID=:memberID');
								$stmt->bindParam(':username', $username);
								$stmt->bindParam(':email', $email);
								$stmt->bindParam(':password', $hashedPassword);
								$stmt->bindParam(':memberID', $memberID);
								$stmt->execute();
							} else{
								$stmt=$dbConn->prepare('UPDATE blog_members SET username=:username, email=:email WHERE memberID=:memberID');
								$stmt->bindParam(':username', $username);
								$stmt->bindParam(':email', $email);
								$stmt->bindParam(':memberID', $memberID);
								$stmt->execute();
							}
							//redirect to index page
							header('Location: index.php?action=updated');
							exit;
						} catch(PDOException $ex){
							$ex->getMessage();
						}
					}
				}
				if(isset($error)){
					foreach($error as $error){
						echo $error.'<br />';
					}
				}
				try{
					$stmt=$dbConn->prepare('SELECT memberID, username, email FROM blog_members WHERE memberID=:memberID');
					$stmt->bindParam(':memberID', $_GET['id']);
					$stmt->execute();
				} catch(PDOException $ex){
					echo $ex->getMessage();
				}
			?>
			<form action='' method='post'>
				<input type='hidden' name='memberID' value='<?php echo $row['memberID'];?>'/>
				<p><label>Username</label><br/>
				<input type='text' name='username' value='<?php echo $row['username'];?>'/></p>
				<p><label>Email</label><br/>
				<input type='email' name='email' value='<?php echo $row['email'];?>'/></p>
				<p><label>Password(optional: fill only if you want to change password)</label><br/>
				<input type='password' name='password' value=''/></p>
				<p><label>Confirm Password</label><br/>
				<input type='password' name='confirmPassword' value=''/></p>
				<p><input type='submit' name='submit' value='Submit'/></p>
			</form>
		</div>
	</body>
</html>
