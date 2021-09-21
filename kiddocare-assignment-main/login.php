<?php
require("config.php");
session_start(); // Checking whether the session is already there or not if

if(isset($_POST['username']))   // it checks whether the user clicked login button or not
{
     $username = $_POST['username'];
     $password = $_POST['password'];

    if(isset($_POST["username"]) && isset($_POST["password"])){
    $file = fopen('users.txt', 'r');
    $good=false;
    while(!feof($file)){
        $line = fgets($file);
        $array = explode("|",$line);
    if(trim($array[0]) == $_POST['username'] && trim($array[1]) == $_POST['password']){
            $good=true;
            break;
        }
    }

    if($good){
    $_SESSION['username'] = $username;
        echo '<script type="text/javascript"> window.open("index.php","_self");</script>';
    }else{
        echo "invalid UserName or Password";
    }
    fclose($file);
    }
    else{
        include 'login.php';
    }

}
?>

<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <title >Login Page</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <center><h1>Login Page</h1></center>

<div class="login-page">
  <div class="form">
    <form class="login-form" method='post'>
			<br>
      <label>Username</label>
      <input type="text" name="username"/>

      <label>Password</label>
      <input type="password" name="password"/>
			<br>
			<br>
      <button type="submit" style="width: 100%;" name="login" value="login">LOGIN</button>
    </form>
  </div>
</div>
<br>
<div class="wrapfooter">
<footer> Powered by YZ Designs </footer>
</div>
</body>
</html>
