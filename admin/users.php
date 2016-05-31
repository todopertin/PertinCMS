<?php
	//include config
	require_once('../includes/config.php');

	//if not logged in redirect to login page
	if(!$user->is_logged_in()){header('Location: login.php');}
	
	//show message from add/edit user page
	if(isset($_GET['deluser'])){
		if($_GET['deluser']!=1){
			$stmt=$dbConn->prepare('DELETE FROM blog_members WHERE memberID=:memberID');
			$stmt->bindParam(':memberID', $_GET['deluser']);
			$stmt->execute();
			header('Location: users.php?action=deleted');
			exit;
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Admin - Users</title>
		<link rel="stylesheet" href="../style/normalize.css">
		<link rel="stylesheet" href="../style/main.css">
		<script language="JavaScript" type="text/javascript">
			function deluser(id, title){
				if (confirm("Are you sure you want to delete '" + title + "'")){
					window.location.href = 'users.php?deluser=' + id;
				}
			}
		</script>
	</head>
	<body>
		<div id="wrapper">
			<?php include('menu.php');?>
			<?php
				if(isset($_GET['action']){
					echo '<h3>User '.$_GET['action'].'.</h3>';
				}
			?>
			<table>
				<tr>
					<th>Username</th>
					<th>Email</th>
					<th>Action</th>
				</tr>
				<?php
					try{
						$stmt=$dbConn->query('SELECT memberID, username, email FROM blog_members ORDER BY username');
						while($row=$stmt->fetchAll()){
							echo '<tr>';
								echo '<td>'.$row['username'].'</td>';
								echo '<td>'.$row['email'].'</td>';
								echo '<td>';
								echo '<a href="edit-user.php?id='.$row['memberID'].'">Edit</a>';
								echo '<a href="javascript:deluser('.$row['memberID'].', '.$row['username'].')">Delete</a>';
								echo '</td>';
							echo '</tr>';
						}
					} catch(PDOException $ex){
						echo $ex->getMessage();
					}
				?>
			</table>
			<p><a href='addUser.php'>Add User</a></p>
		</div>
	</body>
</html>
