<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $id = $_POST['product_id'];

    // Check if the cart has reached the maximum limit of 20 distinct products
    if (!empty($_SESSION["cart_item"]) && count($_SESSION["cart_item"]) >= 20) {
        $_SESSION["cart_error"] = "You cannot add more than 20 different products to the cart.";
        header('Location: index.php');
        exit;
    }

    // Use prepared statement to fetch product details
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $productId = $row["id"];
        $productName = $row["product_name"];
        $productPrice = $row["product_price"];
        $productImage = $row["product_image"];

        // Check if adding this product will exceed the maximum quantity limit (20 units)
        if (isset($_SESSION["cart_item"][$productId]) && $_SESSION["cart_item"][$productId]["quantity"] >= 20) {
            $_SESSION["cart_error"] = "You cannot add more than 20 units of this product to the cart.";
        } else {
            // Add or increment product in cart
            if (isset($_SESSION["cart_item"][$productId])) {
                $_SESSION["cart_item"][$productId]["quantity"] += 1;
            } else {
                $_SESSION["cart_item"][$productId] = array(
                    'name' => $productName,
                    'id' => $productId,
                    'price' => $productPrice,
                    'quantity' => 1,
                    'image' => $productImage
                );
            }
        }
    } else {
        $_SESSION["cart_error"] = "Product not found.";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to index.php after adding to cart
    header('Location: index.php');
    exit;
}
?>
