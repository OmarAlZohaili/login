<?php
 ob_start();
 session_start(); // start a new session or continues the previous
 if( isset($_SESSION['user'])!="" ){
  header("Location: home.php"); // redirects to home.php
 }
 include_once 'dbconnect.php';
 $error = false;
 if ( isset($_POST['btn-signup']) ) {
 
  // sanitize user input to prevent sql injection
  $name = trim($_POST['name']);
  $name = strip_tags($name);
  $name = htmlspecialchars($name);
 
  $email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);
 
  $pass = trim($_POST['pass']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);

  $lastname = trim($_POST['lastname']);
  $lastname = strip_tags($lastname);
  $lastname = htmlspecialchars($lastname);

  $img = trim($_POST['img']);
  $img = strip_tags($img);
  $img = htmlspecialchars($img);
 
  // basic name validation
  if (empty($name)) {
   $error = true;
   $nameError = "Please enter your full name.";
  } else if (strlen($name) < 3) {
   $error = true;
   $nameError = "Name must have atleat 3 characters.";
  } else if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
   $error = true;
   $nameError = "Name must contain alphabets and space.";
  }


  if (empty($lastname)) {
   $error = true;
   $lastnameError = "Please enter your full last name.";
  } else if (strlen($lastname) < 3) {
   $error = true;
   $lastnameError = "Name must have atleat 3 characters.";
  } else if (!preg_match("/^[a-zA-Z ]+$/",$lastname)) {
   $error = true;
   $lastnameError = "Name must contain alphabets and space.";
  }


  if (empty($img)) {
   $error = true;
   $imgError = "Please enter your img.";
  } else if (strlen($img) < 3) {
   $error = true;
   $imgError = "img must have atleat 3 characters.";
  }


 
  //basic email validation
  if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
   $error = true;
   $emailError = "Please enter valid email address.";
  } else {
   // check whether the email exist or not
   $query = "SELECT userEmail FROM users WHERE userEmail='$email'";
   $result = mysqli_query($conn, $query);
   $count = mysqli_num_rows($result);
   if($count!=0){
    $error = true;
    $emailError = "Provided Email is already in use.";
   }
  }
  // password validation
  if (empty($pass)){
   $error = true;
   $passError = "Please enter password.";
  } else if (!preg_match("/^[a-zA-Z0-9 ]+$/",$pass)) {
   $error = true;
   $passError = "pass must contain alphabets and numbers.";
  }else if(strlen($pass) < 6) {
   $error = true;
   $passError = "Password must have atleast 6 characters.";
  }
 
  // password encrypt using SHA256();
  $password = hash('sha256', $pass);
 
  // if there's no error, continue to signup
  if( !$error ) {
   
   $query = "INSERT INTO users(userName,userEmail,userPass, userLastname, userImg) VALUES('$name','$email','$password', '$lastname', '$img')";
   $res = mysqli_query($conn, $query);
   
   if ($res) {
    $errTyp = "success";
    $errMSG = "Successfully registered, you may login now";
    unset($name);
    unset($email);
    unset($pass);
    unset($lastname);
    unset($img);
   } else {
    $errTyp = "danger";
    $errMSG = "Something went wrong, try again later...";
   }
   
  }
 
 
 }
?>
<!DOCTYPE html>
<html>
<head>
<title>Login & Registration System</title>
<style type="text/css">
  body{
    background-color: #CB2356;
  }
</style>
</head>
<body>

    <img style="width: 400px; height: 200px" src="Logo_Border3.png">

    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
   
       
             <h2>Sign Up.</h2>
             <hr />
           
            <?php
   if ( isset($errMSG) ) {
   
    ?>
             <div class="alert">
 <?php echo $errMSG; ?>
             </div>
 <?php
   }
   ?>
           
       
           
 
             <input type="text" name="name" class="form-control" placeholder="Enter Name" maxlength="50" value="<?php echo $name ?>" />
       
                <span class="text-danger"><?php echo $nameError; ?></span>



             <input type="text" name="lastname" class="form-control" placeholder="Enter Last Name" maxlength="50" value="<?php echo $lastname ?>" />
       
                <span class="text-danger"><?php echo $lastnameError; ?></span>
           
     
       
   
             <input type="email" name="email" class="form-control" placeholder="Enter Your Email" maxlength="40" value="<?php echo $email ?>" />
     
                <span class="text-danger"><?php echo $emailError; ?></span>
       
           
       
             
         
             <input type="password" name="pass" class="form-control" placeholder="Enter Password" maxlength="15" />
             
                <span class="text-danger"><?php echo $passError; ?></span>





             <input type="img" name="img" class="form-control" placeholder="put ur pic" maxlength="1000" />
             
                <span class="text-danger"><?php echo $imgError; ?></span>
       
             <hr />
 
           
             <button type="submit" class="btn btn-block btn-primary" name="btn-signup">Sign Up</button>
             <hr />
           
             <a href="index.php">Sign in Here...</a>
     
   
    </form>
</body>
</html>
<?php ob_end_flush(); ?>