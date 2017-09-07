<?php
 ob_start();
 session_start();
 require_once 'dbconnect.php';
 
 // if session is not set this will redirect to login page
 if( !isset($_SESSION['user']) ) {
  header("Location: index.php");
  exit;
 }
 // select logged-in users detail
 $res=mysqli_query($conn, "SELECT * FROM users WHERE userId=".$_SESSION['user']);
 $userRow=mysqli_fetch_array($res, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<title>Welcome - <?php echo $userRow['userEmail']; ?></title>
<style type="text/css">
	body{
		background-color: #FFFFFF;
	}
</style>
</head>
<body>



	<img style="width: 400px; height: 200px" src="Logo_Border3.png">


	<div style="float: right">
            welcome <?php echo $userRow['userName']; ?>

            <?php echo "<img style='width:100px; height:100px' src='" . $userRow['userImg']. "'>"; ?>
			
			<br>

             
            <a href="logout.php?logout">Sign Out</a>
            <a href="users.php?users">users</a>
   </div>
         
   
</body>
</html>
<?php ob_end_flush(); ?>