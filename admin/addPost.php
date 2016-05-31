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
		<title>Admin - Add Post</title>
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
			<h2>Add Post</h2>
			<?php
				//if form has been submitted process it
				if(isset($_POST['submit']){
					$_POST=array_map('stripslashes', $_POST);
					//collect form data
					extract($_POST);
					if($postTitle]==''){
						$error[]="Please enter post title.";
					}
					if($postDesc==''){
						$error[]="Please enter post description.";
					}
					if($postCont==''){
						$error[]="Please enter the content.";
					}
				}
				if(!isset($error)){
					try{
						$stmt=$dbConn->prepare('INSERT INTO blog_posts(postTitle, postDesc, postCont, postDate) VALUES(:postTitle, :postDesc, :postCont, :postDate)');
						$stmt->bindParam(':postTitle', $postTitle);
						$stmt->bindParam(':postDesc', $postDesc);
						$stmt->bindParam(':postCont', $postCont);
						$stmt->bindParam(':postDate', date('Y-m-d H:i:s'));
						//redirect to index page
						header('Location: index.php?action=added');
						exit;
					} catch(PDOException, $ex){
						echo $ex->getMessage();
					}
				}
			?>
			<form action='' method='post'>
				<p><label>Title</label><br/>
				<input type='text' name='postTitle' value='<?php if(isset($error)){echo $_POST['postTitle'];}?>'/></p>
				<p><label>Description</label><br/>
				<textarea rows='10' cols='60' name='postDesc'><?php if(isset($error)){echo $_POST['postDesc'];}?></textarea></p>
				<p><label>Content</label><br/>
				<textarea rows='10' cols='60' name='postCont'><?php if(isset($error)){echo $_POST['postCont'];}?></textarea></p>
				<p><input type='submit' name='submit' value='Submit'/></p>
			</form>
		</div>
	</body>
</html>
