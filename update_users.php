<!DOCTYPE html>
<html lang="en">

<?php
include("connection/connect.php"); // Include connection file
session_start(); // Start session
error_reporting(0); // Hide undefined index errors

if (isset($_POST['submit'])) {
    if (empty($_POST['uname']) || empty($_POST['fname']) || empty($_POST['lname']) || 
        empty($_POST['email']) || empty($_POST['password']) || empty($_POST['phone'])) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>All fields are required!</strong>
                  </div>';
    } else {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Invalid email!</strong>
                      </div>';
        } elseif (strlen($_POST['password']) < 6) {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Password must be at least 6 characters!</strong>
                      </div>';
        } elseif (strlen($_POST['phone']) < 10) {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Invalid phone number!</strong>
                      </div>';
        } else {
            $mql = "UPDATE users SET 
                    username='$_POST[uname]', 
                    f_name='$_POST[fname]', 
                    l_name='$_POST[lname]', 
                    email='$_POST[email]', 
                    phone='$_POST[phone]', 
                    password='" . ($_POST['password']) . "' 
                    WHERE u_id='$_GET[user_upd]'";
            mysqli_query($db, $mql);
            $success = '<div class="alert alert-success alert-dismissible fade show">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                          <strong>User info updated!</strong>
                        </div>';
            // Redirect to view.php
            header("Location: view.php");
            exit;
        }
    }
}

if (isset($_GET['user_upd'])) {
    $user_upd = $_GET['user_upd'];
    $ssql = "SELECT * FROM users WHERE u_id='$user_upd'";
    $res = mysqli_query($db, $ssql);
    $newrow = mysqli_fetch_array($res);
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="main logo.png">
    <title>Home</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        /* Styles for active (clicked) state */
        .navbar-nav .nav-item .nav-link.active,
        .navbar-nav .nav-item .nav-link:hover {
            color: white !important;
        }

        /* Styles for hover state */
        .navbar-nav .nav-item .nav-link:hover {
            color: orange !important;
        }

        /* Add hover effect to app-section text-img-block */
        .app-section .text-img-block:hover {
            background-color: orange !important;
        }

        /* Styles for hover state */
        .navbar-nav .nav-item .nav-link {
            color: orange !important;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var navLinks = document.querySelectorAll('.navbar-nav .nav-item .nav-link');

            navLinks.forEach(function(link) {
                link.addEventListener('click', function(event) {
                    navLinks.forEach(function(el) {
                        el.classList.remove('active');
                    });

                    event.target.classList.add('active');
                });

                link.addEventListener('mouseover', function() {
                    navLinks.forEach(function(el) {
                        el.classList.remove('hover');
                    });

                    link.classList.add('hover');
                });

                link.addEventListener('mouseout', function() {
                    navLinks.forEach(function(el) {
                        el.classList.remove('hover');
                    });
                });
            });
        });
    </script>
</head>

<body class="home">
    <!--header starts-->
    <header id="header" class="header-scroll top-header headrom">
        <!-- .navbar -->
        <nav class="navbar navbar-dark">
            <div class="container">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                <a class="navbar-brand" href="index.php">HARDWARE</a>
                <div class="collapse navbar-toggleable-md float-lg-right" id="mainNavbarCollapse">
                    <ul class="nav navbar-nav">
                        <li class="nav-item"> <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a> </li>
                        <?php
                        $ress = mysqli_query($db, "select * from hardware");
                        while ($rows = mysqli_fetch_array($ress)) {
                            echo '<li class="nav-item"><a class="nav-link active" href="hardwares.php?store_id=' . $rows['st_id'] . '">Product</a></li>';
                        }
                        ?>
                        <?php
                        if (empty($_SESSION["user_id"])) {
                            echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a></li>
                                  <li class="nav-item"><a href="registration.php" class="nav-link active">Sign Up</a></li>';
                        } else {
                            echo '<li class="nav-item"><a href="your_orders.php" class="nav-link active">My Orders</a></li>
                                  <li class="nav-item"><a href="logout.php" class="nav-link active">Logout</a></li>
                                  <li class="nav-item"><a href="update_users.php" class="nav-link active">Profile</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- /.navbar -->
    </header>

    <!-- Page wrapper  -->
    <div class="page-wrapper" style="height:1200px;">
        <!-- Bread crumb -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-primary">Update Users Info</h3>
            </div>
        </div>
        <!-- End Bread crumb -->
        <!-- Container fluid  -->
        <div class="container-fluid">
            <!-- Start Page Content -->
            <div class="row">
                <div class="container-fluid">
                    <!-- Start Page Content -->
                    <?php
                    echo $error;
                    echo $success;
                    ?>

                    <div class="col-lg-12">
                        <div class="card card-outline-primary">
                            <div class="card-header">
                                <h4 class="m-b-0 text-black">Update Users</h4>
                            </div>
                            <div class="card-body">
                                <form action='' method='post'>
                                    <div class="form-body">
                                        <hr>
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Username</label>
                                                    <input type="text" name="uname" class="form-control" value="<?php echo $newrow['username']; ?>" placeholder="username">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label class="control-label">First Name</label>
                                                    <input type="text" name="fname" class="form-control form-control-danger" value="<?php echo $newrow['f_name']; ?>" placeholder="jon">
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Last Name</label>
                                                    <input type="text" name="lname" class="form-control" placeholder="doe" value="<?php echo $newrow['l_name']; ?>">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label class="control-label">Email</label>
                                                    <input type="text" name="email" class="form-control form-control-danger" value="<?php echo $newrow['email']; ?>" placeholder="example@gmail.com">
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Password</label>
                                                    <input type="text" name="password" class="form-control form-control-danger" value="<?php echo $newrow['password']; ?>" placeholder="password">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Phone</label>
                                                    <input type="text" name="phone" class="form-control form-control-danger" value="<?php echo $newrow['phone']; ?>" placeholder="phone">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <input type="submit" name="submit" class="btn btn-success" value="Save">
                                        <a href="view.php" class="btn btn-inverse">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Page Content -->
            </div>
            <!-- End Container fluid -->
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
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
