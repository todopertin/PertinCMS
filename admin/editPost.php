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
		<title>Admin - Edit Post</title>
		<link rel="stylesheet" href="../style/normalize.css">
		<link rel="stylesheet" href="../style/main.css">
		<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
		<script>
			tinymce.init({
				selector: "textarea",
				plugins: [
					"advlist autolink lists link image charmap print preview anchor",
					"searchreplace visualblocks code fullscreen",
					"insertdatetime media table contextmenu paste"
				],
				toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
			});
		</script>
	</head>
	<body>
		<div id="wrapper">
			<?php include('menu.php');?>
			<p><a href="./">Blog Admin Index</a></p>
			<h2>Edit Post</h2>
			<?php
				if(isset($_POST['submit'])){
					$_POST=array_map('stripslashes', $_POST);
					extract($_POST);
					if($postID==''){
						$error[]='This post is missing a valid id!';
					}
					if($postTitle==''){
						$error[]='Please enter post title';
					}
					if($postDesc==''){
						$error[]='Please enter post description';
					}
					if($postCont==''){
						$error[]='Please enter content';
					}
				}
				if(!isset($error)){
					try{
						$stmt=$dbConn->prepare('UPDATE blog_posts SET postTitle=:postTitle, postDesc=:postDesc, postCont=:postCont WHERE postID=:postID');
						$stmt->bindParam(':postTitle', $postTitle);
						$stmt->bindParam(':postDesc', $postDesc);
						$stmt->bindParam(':postCont', $postCont);
						$stmt->execute();
					} catch(PDOException, $ex){
						echo $ex->getMessage();
					}
				}
				//check for any errors
				if(isset($error)){
					foreach($error as $error){
						echo $error.'<br />';
					}
				}
				try{
					$stmt=$dbConn->prepare('SELECT postID, postTitle, postDesc, postCont FROM blog_posts WHERE postID=:postID');
					$stmt->bindParam(':postID', $_GET['id']);
					$stmt->execute();
					$row=$stmt->fetch();
				} catch(PDOException $ex){
					echo $ex->getMessage();
				}
			?>
			<form action='' method='post'>
				<input type='hidden' name='postID' value='<?php echo $row['postID'];?>'/>
				<p><label>Title</label><br/>
				<input type='text' name='postTitle' value='<?php echo $row['postTitle'];?>'/></p>
				<p><label>Description</label><br/>
				<textarea rows='10' cols='60' name='postDesc'><?php echo $row['postDesc'];?></textarea></p>
				<p><label>Content</label><br/>
				<textarea rows='10' cols='60' name='postCont'><?php echo $row['postCont'];?></textarea></p>
				<p><input type='submit' name='submit' value='Submit'/></p>
			</form>
		</div>
	</body>
</html>
