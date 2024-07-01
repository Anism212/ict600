<?php
session_start();

if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $store_id = $_POST['store_id'];
    $d_id = $_POST['d_id'];
    $quantity = $_POST['quantity'];

    // Update the quantity in the cart
    foreach ($_SESSION["cart_item"] as &$item) {
        if ($item["d_id"] == $d_id) {
            $item["quantity"] = $quantity;
            break;
        }
    }
}

// Redirect back to the page displaying the cart
header("Location: hardwares.php?store_id=$store_id");
exit();
?>
