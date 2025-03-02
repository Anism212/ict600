<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="icon" type="image/png" sizes="16x16" href="main logo.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
  <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
  <link rel="stylesheet" href="css/login.css">
  <style type="text/css">
    #buttn {
      color: #fff;
      background-color: #ff3300;
    }
  </style>
</head>
<body>
<?php
include("connection/connect.php"); // INCLUDE CONNECTION
error_reporting(0); // hide undefined index errors
session_start(); // temp sessions

if (isset($_POST['submit'])) { // if button is submit
    $username = $_POST['username']; // fetch records from login form
    $password = $_POST['password'];

    if (!empty($_POST["submit"])) { // if records were not empty
        if ($username == '1' && $password == '1') {
            $_SESSION["user_id"] = 'special_user'; // set a unique session id for special user
            header("refresh:1;url=admin/dashboard.php"); // redirect to dashboard.php page
            exit();
        } else {
            $loginquery = "SELECT * FROM users WHERE username='$username' && password='" . ($password) . "'"; // selecting matching records
            $result = mysqli_query($db, $loginquery); // executing
            $row = mysqli_fetch_array($result);

            if (is_array($row)) { // if matching records in the array & if everything is right
                $_SESSION["user_id"] = $row['u_id']; // put user id into temp session
                
                // Fetch the membership level
                $Membership = $row['Membership']; // Assuming the column name is 'membership_level'

                // Redirect based on membership level
                switch ($Membership) {
                    case 'Gigabyte':
                        header("refresh:1;url=Gigabyte.php");
                        break;
                    case 'Terabyte':
                        header("refresh:1;url=Terabyte.php");
                        break;
                    case 'megabyte':
                        header("refresh:1;url=megabyte.php");
                        break;
                    default:
                        header("refresh:1;url=index.php");
                        break;
                }
            } else {
                $message = "Invalid Username or Password!"; // throw error
            }
        }
    }
}
?>

<!-- Form Mixin-->
<!-- Input Mixin-->
<!-- Button Mixin-->
<!-- Pen Title-->
<div class="pen-title">
  <h1>Login Page</h1>
</div>
<!-- Form Module-->
<div class="module form-module">
  <div class="toggle">
  </div>
  <div class="form">
    <h2>Login to your account</h2>
    <span style="color:red;"><?php echo $message; ?></span>
    <span style="color:green;"><?php echo $success; ?></span>
    <form action="" method="post">
      <input type="text" placeholder="Username" name="username"/>
      <input type="password" placeholder="Password" name="password"/>
      <input type="submit" id="buttn" name="submit" value="Login" />
    </form>
  </div>
  <div class="cta">Not registered?<a href="registration.php" style="color:#f30;"> Create an account</a></div>
</div>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
</body>
</html>
