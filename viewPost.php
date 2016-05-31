<?php
	require 'includes/config.php';
	$stmt=$dbConn->prepare('SELECT postID, postTitle, postCont, postDate FROM blog_posts WHERE postID=:postID');
	$stmt->bindParam(':postID', $_GET['id'], PDO::PARAM_INT);
	$stmt->execute();
	$row=$stmt->fetch();
	if($row['postID']==''){ //If there is no postID coming from the database, their is no record so redirect the user to the index page.
		header('Location: ./');
		exit;
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Blog - <?php echo $row['postTitle'];?></title>
		<link rel="stylesheet" href="style/normalize.css">
		<link rel="stylesheet" href="style/main.css">
	</head>
	<body>
		<div id="wrapper">
			<h1>Blog</h1>
			<hr />
			<p><a href="./">Blog Index</a></p>
			<?php
				echo '<div>';
					echo '<h1>'.$row['postTitle'].'</h1>';
					echo '<p>'.date('jS M Y', strtotime($row['postDate'])).'</p>';
					echo '<p>'.$row['postCont'].'</p>';
				echo '</div>';
			?>
		</div>
	</body>
</html>