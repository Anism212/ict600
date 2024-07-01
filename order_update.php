<?php
include("connection/connect.php"); //connection to db
error_reporting(0);
session_start();


// sending query
mysqli_query($db,"UPDATE FROM users_orders WHERE o_id = '".$_GET['order_update']."'"); // deletig records on the bases of ID
header("location:your_orders.php");  //once deleted success redireted back to current page

?>
