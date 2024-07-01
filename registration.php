<!DOCTYPE html>
<html lang="en">
<?php
session_start(); //temp session
error_reporting(0); // hide undefine index
include("connection/connect.php"); // connection

if (isset($_POST['submit'])) //if submit btn is pressed
{
    if (empty($_POST['username']) || empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['password']) || empty($_POST['cpassword']) || empty($_POST['address'])) {
        $message = "All fields must be Required!";
    } else {
        //cheching username & email if already present
        $check_username = mysqli_query($db, "SELECT username FROM users where username = '".$_POST['username']."' ");
        $check_email = mysqli_query($db, "SELECT email FROM users where email = '".$_POST['email']."' ");
    
        if ($_POST['password'] != $_POST['cpassword']) {  //matching passwords
            $message = "Password not match";
        } elseif (strlen($_POST['password']) < 6) {  //cal password length
            $message = "Password Must be >=6";
        } elseif (strlen($_POST['phone']) < 10) {  //cal phone length
            $message = "Invalid phone number!";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { // Validate email address
            $message = "Invalid email address please type a valid email!";
        } elseif (mysqli_num_rows($check_username) > 0) {  //check username
            $message = 'Username Already exists!';
        } elseif (mysqli_num_rows($check_email) > 0) { //check email
            $message = 'Email Already exists!';
        } else {
            $fname = $_FILES['file']['name'];
            $temp = $_FILES['file']['tmp_name'];
            $fsize = $_FILES['file']['size'];
            $extension = explode('.', $fname);
            $extension = strtolower(end($extension));
            $fnew = uniqid().'.'.$extension;
            $store = "admin/Res_img/hardwares/".basename($fnew); // the path to store the upload image

            if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif') {        
                if ($fsize >= 1000000) {
                    $error = '<div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Max Image Size is 1024kb!</strong> Try different Image.
                              </div>';
                } else {
                    move_uploaded_file($temp, $store);
                    $mql = "INSERT INTO users(username, f_name, l_name, email, phone, password, Membership, address, img) VALUES('".$_POST['username']."', '".$_POST['firstname']."', '".$_POST['lastname']."', '".$_POST['email']."', '".$_POST['phone']."', '".($_POST['password'])."', '".$_POST['membership']."', '".$_POST['address']."', '".$fnew."')";
                    mysqli_query($db, $mql);
                    $success = "Account Created successfully! <p>You will be redirected in <span id='counter'>5</span> second(s).</p>
                                <script type='text/javascript'>
                                function countdown() {
                                    var i = document.getElementById('counter');
                                    if (parseInt(i.innerHTML) <= 0) {
                                        location.href = 'login.php';
                                    }
                                    i.innerHTML = parseInt(i.innerHTML) - 1;
                                }
                                setInterval(function(){ countdown(); }, 1000);
                                </script>";
                    header("refresh:5;url=login.php"); // redirected once inserted success
                }
            } elseif ($extension == '') {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Select image</strong>
                          </div>';
            } else {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Invalid extension!</strong> png, jpg, gif are accepted.
                          </div>';
            }
        }
    }
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags must come first in the head; any other head content must come after these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="main logo.png">
    <title>Registration</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
</head>

<!-- Add the new CSS for hover effect -->
<style>
    /* Styles for active (clicked) state */
    .navbar-nav .nav-item .nav-link.active,
    .navbar-nav .nav-item .nav-link:hover {
        color: white !important; /* Text color for active state and hover state */
    }

    /* Styles for hover state */
    .navbar-nav .nav-item .nav-link:hover {
        color: orange !important; /* Text color for hover state */
    }

    /* Add hover effect to app-section text-img-block */
    .app-section .text-img-block:hover {
        background-color: orange !important; /* Background color on hover */
    }

    /* Styles for hover state */
    .navbar-nav .nav-item .nav-link {
        color: orange !important; /* Text color for hover state */
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var navLinks = document.querySelectorAll('.navbar-nav .nav-item .nav-link');

    navLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            navLinks.forEach(function (el) {
                el.classList.remove('active');
            });

            event.target.classList.add('active');
        });

        link.addEventListener('mouseover', function () {
            navLinks.forEach(function (el) {
                el.classList.remove('hover');
            });

            link.classList.add('hover');
        });

        link.addEventListener('mouseout', function () {
            navLinks.forEach(function (el) {
                el.classList.remove('hover');
            });
        });
    });
});
</script>

<body>
    <!--header starts-->
    <header id="header" class="header-scroll top-header headrom">
        <!-- .navbar -->
        <nav class="navbar navbar-dark">
            <div class="container">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                <a class="navbar-brand" href="index.php">HARDWARE</a>
                <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                    <ul class="nav navbar-nav">
                        <li class="nav-item"> <a class="nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a> </li>
                        <li class="nav-item"> <a class="nav-link active" href="h_stores.php">Shop <span class="sr-only"></span></a> </li>
                        <?php
                        if (empty($_SESSION["user_id"])) {
                            echo  '<li class="nav-item"><a href="about.php" class="nav-link active">About store</a> </li>';
                            echo  '<li class="nav-item"><a href="staff.php" class="nav-link active ">Staff</a> </li>';
                            
                            echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a> </li>
                                  <li class="nav-item"><a href="registration.php" class="nav-link ">Sign Up</a> </li>';
                        } else {
                            echo '<li class="nav-item"><a href="your_orders.php" class="nav-link active">Your Orders</a> </li>';
                            echo '<li class="nav-item"><a href="logout.php" class="nav-link active">LogOut</a> </li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- /.navbar -->
    </header>

    <div class="page-wrapper">
        <div class="breadcrumb">
            <div class="container">
                <ul>
                    <li><a href="#" class="active">
                        <span style="color:red;"><?php echo $message; ?></span>
                        <span style="color:green;"><?php echo $success; ?></span>
                    </a></li>
                </ul>
            </div>
        </div>

        <section class="contact-page inner-page">
            <div class="container">
                <div class="row">
                    <!-- REGISTER -->
                    <div class="col-md-8">
                        <div class="widget">
                            <div class="widget-body">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="exampleInputEmail1">User-Name</label>
                                            <input class="form-control" type="text" name="username" id="example-text-input">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="exampleInputEmail1">First Name</label>
                                            <input class="form-control" type="text" name="firstname" id="example-text-input">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="exampleInputEmail1">Last Name</label>
                                            <input class="form-control" type="text" name="lastname" id="example-text-input-2">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="exampleInputEmail1">Email address</label>
                                            <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="exampleInputEmail1">Phone number</label>
                                            <input class="form-control" type="text" name="phone" id="example-tel-input-3">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="exampleInputPassword1">Password</label>
                                            <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="exampleInputPassword1">Repeat password</label>
                                            <input type="password" class="form-control" name="cpassword" id="exampleInputPassword2">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="exampleTextarea">Address</label>
                                            <textarea class="form-control" id="exampleTextarea" name="address" rows="3"></textarea>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="exampleInputPassword1">Membership</label>
                                            <select name="membership" class="form-control" id="exampleSelectMembership">
                                                <option value="megabyte">Megabyte</option>
                                                <option value="gigabyte">Gigabyte</option>
                                                <option value="terabyte">Terabyte</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="exampleInputFile">Image</label>
                                            <input type="file" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp" name="file">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <p style="color:red;"><?php echo $error; ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <input type="submit" value="submit" name="submit" class="btn theme-btn">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- end: Widget -->
                        </div>
                        <!-- /REGISTER -->
                    </div>
                     <!-- WHY? -->
                     <div class="col-md-4">
                        <h4>Registration is fast, easy and free.</h4>
                        <p>Get registered to avail all these benefits:</p>
						<ul>
						<li>Choose from Wide varities of product</li>
						<li>Live product tracking</li>
						
						<li>Free home delivery</li>
						
						</ul>
                        <hr>
                        <img src="main logo.png" alt="" class="img-fluid">
                        <p>Frequently Asked?</p>
                        <div class="panel">
                           <div class="panel-heading">
                              <h4 class="panel-title"><a data-parent="#accordion" data-toggle="collapse" class="panel-toggle collapsed" href="#faq1" aria-expanded="false"><i class="ti-info-alt" aria-hidden="true"></i>What is Hardware Customer Care Number?</a></h4>
                           </div>
                           <div class="panel-collapse collapse" id="faq1" aria-expanded="false" role="article" style="height: 0px;">
                              <div class="panel-body">For any inquiries about Hardware's diverse range of services and products or assistance with placing orders, don't 
							  hesitate to connect with our dedicated Customer 
							  Care team at +60 11-3630 6284. Whether you have specific qu
							  estions about our menu, need support with the ordering process, or want to share feedback,
							  our team is here to provide prompt and personalized assistance. Your satisfaction is our top priority, and we encourage you to reach out to +60 11-3630 6284 for a seamless and enjoyable experience with Hardware.</div>
                           </div>
                        </div>
                        <!-- end:panel -->
						
						<div class="panel">
                           <div class="panel-heading">
                              <h4 class="panel-title"><a data-parent="#accordion" data-toggle="collapse" class="panel-toggle" href="#faq2" aria-expanded="true"><i class="ti-info-alt" aria-hidden="true"></i>Do you charge for delivery?</a></h4>
                           </div>
                           <div class="panel-collapse collapse" id="faq2" aria-expanded="true" role="article">
                              <div class="panel-body">No. At Hardware, we are delighted to offer complimentary delivery services to our valued customers.
							  We understand the importance of convenience, and that's why we provide free delivery as part of our commitment to ensuring your satisfaction. 
							  Enjoy the ease of having our delicious Korean hardwares delivered straight to your doorstep without any additional charges. It's our 
							  way of enhancing your experience and making your dining experience with Hardware even more delightful.</div>
                           </div>
                        </div>
                        <!-- end:Panel -->
                        <h4 class="m-t-20">Contact Customer Support</h4>
                        <p> If you're looking for more help or have a question to ask, please feel free. </p>
                        <p> <a href="https://www.instagram.com/hardwarestore04_/" class="btn theme-btn m-t-15">Contact Us</a> </p>
                     </div>
                     <!-- /WHY? -->
                </div>
            </div>
        </section>
          <!-- start: FOOTER -->
          <footer class="footer">
            <div class="container">
                <!-- top footer statrs -->
                <div class="row top-footer">
						
						<div class="col-xs-12 col-sm-4 address color-gray">
                            <h5>Address:</h5>
                            <p>Tepi Klinik Bidan Jalan Sungai Baru Simpang Ampat, 02700 Kangar, Perlis</p>
							 <h5>Operational Hours:</h5>
                            <p>Monday to Sunday 
							8 a.m to 10 p.m  </p>
							
                            <h5>Call us at: <a style="font-family: Arial; color:white;" href="https://l.instagram.com/?u=https%3A%2F%2Fwa.me%2Fc%2F601136306284&e=AT3Hloia6Bzu4ndOJ6l4HvQ13ibHGuLl0g5l42LzsQgYmIQtnv14sS-F9EjjYEqYye6wrYO-9GtUbnrIG1gUOx2OmDeRviNU2MZffQ">+60 11-3630 6284</a></h5></div>
                   


				   <div class="col-xs-12 col-sm-2 about color-gray">
                        <h5>Social media</h5>
                       <a href="https://www.facebook.com/koreanchickenperlisbyHanaChicken" target="_blank">
    <img src="images/i.png" alt="Facebook" width="50" height="50">
</a>
<a href=https://www.instagram.com/hardwarestore04_/ target="_blank">
    <img src="images/ii.png" alt="Instagram" width="50" height="50">
</a>
<a href="https://www.tiktok.com/@hanachickenperlis1" target="_blank">
    <img src="images/t.png" alt="Tiktok" width="50" height="50">
</a>



                    </div>
					<div class="col-xs-12 col-sm-3 popular-locations color-gray">
                        <h5>Locations We Deliver To</h5>
                        <ul>
                            <li><a >Arau</a> </li>
                            <li><a >Beseri</a> </li>
                            <li><a >Bintong</a> </li>
                            <li><a >Kaki Bukit</a> </li>
                            <li><a >Kuala Perlis</a> </li>
                            <li><a >Kaki Bukit</a> </li>
                            <li><a >Kangar</a> </li>
                            <li><a >Simpang Ampat</a> </li>
                            <li><a >Tambun Tulang</a> </li>
                            <li><a >Mata Ayer</a> </li>
                        </ul>
                    </div>
					 <div class="col-xs-12 col-sm-3 payment-options color-gray">
                            <h5>All Major Credit Cards Accepted</h5>
                            <ul>
                                <li>
                                    <a > <img src="images/paypal.png" alt="Paypal"> </a>
                                </li>
                                <li>
                                    <a ><img src="images/mastercard.png" alt="Mastercard"> </a>
                                </li>
                                <li>
                                    <a > <img src="images/maestro.png" alt="Maestro"> </a>
                                </li>
                                <li>
                                    <a ><img src="images/stripe.png" alt="Stripe"> </a>
                                </li>
                                <li>
                                    <a ><img src="images/bitcoin.png" alt="Bitcoin"> </a>
                                </li>
                            </ul>
                        </div>
                        
                   
                    
                   
                </div>
                <!-- top footer ends -->
                
            </div>
        </footer>
        <!-- end:Footer -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/hardware.min.js"></script>
</body>
</html>
