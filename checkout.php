<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
include_once 'product-action.php';
error_reporting(0);
session_start();

if(empty($_SESSION["user_id"])) {
    header('location:login.php');
} else {
    foreach ($_SESSION["cart_item"] as $item) {
        $item_total += ($item["price"] * $item["quantity"]);

        if ($_POST['submit']) {
            $SQL = "INSERT INTO users_orders(u_id, title, quantity, price) VALUES('" . $_SESSION["user_id"] . "', '" . $item["title"] . "', '" . $item["quantity"] . "', '" . $item["price"] . "')";
            mysqli_query($db, $SQL);
            $success = header('location:cod.php');

            // Empty the cart after placing the order
            unset($_SESSION["cart_item"]);
        }
    }

    // Fetch user's membership level
    $user_id = $_SESSION["user_id"];
    $sql = "SELECT Membership FROM users WHERE u_id='$user_id'";
    $query = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($query);
    $membership = $row['Membership'];

    // Calculate discount based on membership level
    $discount_percentage = 0;
    $reward_points = 0;
    if ($membership == 'Megabyte') {
        $discount_percentage = 0;
        $reward_points = 0;
    } elseif ($membership == 'Terabyte') {
        $discount_percentage = 6;
        $reward_points = 10;
    } elseif ($membership == 'Gigabyte') {
        $discount_percentage = 4;
        $reward_points = 8;
    }

    $discount_amount = ($item_total * $discount_percentage) / 100;
    $total_after_discount = $item_total - $discount_amount;
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="main logo.png">
    <title>Order Checkout</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet"> 

    <!-- Add the new CSS for hover effect -->
    <style>
        .navbar-nav .nav-item .nav-link.active,
        .navbar-nav .nav-item .nav-link:hover {
            color: white !important;
        }

        .navbar-nav .nav-item .nav-link:hover {
            color: orange !important;
        }

        .app-section .text-img-block:hover {
            background-color: orange !important;
        }

        .navbar-nav .nav-item .nav-link {
            color: orange !important;
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
</head>

<body>
    <div class="site-wrapper">
        <header id="header" class="header-scroll top-header headrom">
            <nav class="navbar navbar-dark">
                <div class="container">
                    <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                    <a class="navbar-brand" href="index.php"> HARDWARE </a>
                    <div class="collapse navbar-toggleable-md float-lg-right" id="mainNavbarCollapse">
                        <ul class="nav navbar-nav">
                            <li class="nav-item"> <a class="nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a> </li>
                            <?php
                            $ress = mysqli_query($db, "SELECT * FROM hardware");
                            while ($rows = mysqli_fetch_array($ress)) {
                                echo '<li class="nav-item">
                                    <a class="nav-link active" href="hardwares.php?store_id=' . $rows['st_id'] . '">Product</a>
                                </li>';
                            }
                            ?>
                            <?php
                            if(empty($_SESSION["user_id"])) {
                                echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a> </li>
                                      <li class="nav-item"><a href="registration.php" class="nav-link active">Sign Up</a> </li>';
                            } else {

                                echo '<li class="nav-item"><a href="your_orders.php" class="nav-link ">My Orders</a> </li>';
                                echo '<li class="nav-item"><a href="view.php" class="nav-link active">Profile</a> </li>';
    
                                echo '<li class="nav-item"><a href="logout.php" class="nav-link active">Logout</a> </li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <div class="page-wrapper">
            <div class="top-links">
                <div class="container">
                    <ul class="row links">
                        <li class="col-xs-12 col-sm-4 link-item "><span>1</span><a href="#">Pick your hardware products</a></li>
                        <li class="col-xs-12 col-sm-4 link-item active" ><span>2</span><a href="checkout.php">Get delivered & Pay</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="container">
                <span style="color:green;"><?php echo $success; ?></span>
            </div>
            
            <div class="container m-t-30">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
                        <div class="widget widget-cart">
                            <div class="widget-heading">
                                <h3 class="widget-title text-dark">Your Shopping Cart</h3>
                                <div class="clearfix"></div>
                            </div>
                            <div class="order-row bg-white">
                                <div class="widget-body">
                                    <?php
                                    $item_total = 0;
                                    foreach ($_SESSION["cart_item"] as $item) {
                                    ?>                                  
                                    <div class="title-row">
                                        <?php echo $item["title"]; ?><a href="hardwares.php?store_id=<?php echo $_GET['store_id']; ?> "></a>
                                    </div>
                                    <div class="form-group row no-gutter">
                                        <div class="col-xs-8">
                                            <input type="text" class="form-control b-r-0" value=<?php echo $item["price"]; ?> readonly id="exampleSelect1">
                                        </div>
                                        <div class="col-xs-4">
                                            <input class="form-control" type="text" readonly value='<?php echo $item["quantity"]; ?>' id="example-number-input"> 
                                        </div>
                                    </div>
                                    <?php
                                        $item_total += ($item["price"] * $item["quantity"]);
                                    }
                                    ?>  
                                </div>
                            </div>
                            <div class="widget-body">
                                <div class="price-wrap text-xs-center">
                                    <p>Total Amount</p>
                                    <h3 class="value"><strong>RM<?php echo number_format($item_total, 2); ?></strong></h3>
                                    <p>Free Shipping</p>
                                    <?php
                                    if ($row) {
                                        echo '<tr>
                                                <td>' . $row['Membership'] . '</td>
                                                <br>
                                              </tr>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-6">
                        <div class="container m-t-" style="width: 700px;">
                            <form action="" method="post">
                                <div class="widget clearfix">
                                    <div class="widget-body">
                                        <form method="post" action="#">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="cart-totals margin-b-20">
                                                        <div class="cart-totals-title">
                                                            <h4>Cart Summary</h4>
                                                        </div>
                                                        <div class="cart-totals-fields">
                                                            <table class="table">
                                                                <tbody>
                                                                    <tr>
                                                                        <td>Cart Subtotal</td>
                                                                        <td>RM<?php echo number_format($item_total, 2); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Discount</td>
                                                                        <td>RM<?php echo number_format($discount_amount, 2); ?> (<?php echo $discount_percentage; ?>%)</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>Reward Points</td>
                                                                        <td><?php echo $reward_points; ?> points</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="text-color"><strong>Total</strong></td>
                                                                        <td class="text-color"><strong>RM <?php echo number_format($total_after_discount, 2); ?></strong></td>
                                                                    </tr>
                                                                    
                                                               </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="payment-option">
                                                        <ul class="list-unstyled">
                                                            <li>
                                                                <label class="custom-control custom-radio">
                                                                    <input name="mod" id="radioStacked1" value="COD" type="radio" class="custom-control-input" checked> 
                                                                    <span class="custom-control-indicator"></span> 
                                                                    <span class="custom-control-description">Payment on delivery</span>
                                                                    <br> 
                                                                    <span>Cash, Debit & Credit Cards</span>
                                                                </label>
                                                            </li>
                                                        </ul>
                                                        <p class="text-xs-center">
                                                            <input type="submit" name="submit" class="btn btn-outline-success btn-block" value="Order now">
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

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