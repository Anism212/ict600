<?php
include("connection/connect.php");
session_start();

if(isset($_GET['order_id'])) {
   $order_id = mysqli_real_escape_string($db, $_GET['order_id']);
   
   // Perform the cancellation logic (update the order status)
   $update_query = mysqli_query($db, "UPDATE users_orders SET status='cancelled' WHERE o_id='$order_id'");

   if ($update_query) {
      echo "<script>alert('Order cancelled successfully');</script>";
      // Redirect or perform any additional actions after cancellation
      header("Location: your_orders.php");
      exit();
   } else {
      echo "<script>alert('Error in cancelling order');</script>";
      // Handle the error, redirect, or display a message
   }
}
?>
