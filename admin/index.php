<?php
	//include config
	require_once('../includes/config.php');

	//if not logged in redirect to login page
	if(!$user->is_logged_in()){header('Location: login.php');}
	
	if(isset($_GET['delpost'])){
		$stmt=$dbConn->prepare('DELETE FROM blog_posts WHERE postID=:postID');
		$stmt->bindParam(':postID', $_GET['delpost']);
		$stmt->execute();
		header('Location: index.php?action=deleted');
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Admin</title>
		<link rel="stylesheet" href="../style/normalize.css">
		<link rel="stylesheet" href="../style/main.css">
		<script language="JavaScript" type="text/javascript">
			function delpost(id, title){
				if (confirm("Are you sure you want to delete '" + title + "'")){
					window.location.href = 'index.php?delpost=' + id;
				}
			}
		</script>
	</head>
	<body>
		<div id="wrapper">
			<?php include('menu.php');?>
			<table>
				<tr>
					<th>Title</th>
					<th>Date</th>
					<th>Action</th>
				</tr>
				<?php
					try{
						$stmt=$dbConn->query('SELECT postID, postTitle, postDate FROM blog_posts ORDER BY postID DESC');
						while($row=$stmt->fetchAll()){
							echo '<tr>';
								echo '<td>'.$row['postTitle'].'</td>';
								echo '<td>'.date('jS M Y', strtotime($row['postDate'])).'</td>';
								echo '<td><a href="editPost.php?id='.$row['postID'].'">Edit</a>';
								echo '<td><a href="javascript:delpost('.$row['postID'].', '.$row['postTitle'].')">Delete</a>';
							echo '</tr>';
						}
					} catch(PDOException $ex){
						echo $ex->getMessage();
					}
				?>
			</table>
			<p><a href='addPost.php'>Add Post</a></p>
		</div>
	</body>
</html>
