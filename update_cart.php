<?php
// Assuming you have a session started and your cart data is stored in $_SESSION['cart']

if (isset($_POST['update_cart'])) {
    $item_id = $_POST['item_id'];
    $new_quantity = $_POST['quantity'];

    // Validate $new_quantity if needed

    // Update the cart item with the new quantity
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $new_quantity;
        // Optionally, you can update other properties like price if needed
    }
}

// Redirect back to cart.php or wherever your cart page is
header("Location: cart.php");
exit();
?>
