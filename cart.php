<?php
session_start();

if (isset($_POST['action']) && $_POST['action'] == "update" && isset($_POST['id']) && isset($_POST['quantity'])) {
    $item_id = $_POST['id'];
    $quantity = $_POST['quantity'];
    if (!empty($_SESSION["cart_item"]) && isset($_SESSION["cart_item"][$item_id])) {
        if ($quantity <= 0) {
            unset($_SESSION["cart_item"][$item_id]);
        } else {
            $_SESSION["cart_item"][$item_id]["quantity"] = $quantity;
        }
        header('Location: cart.php');
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] == "remove" && isset($_GET['id'])) {
    $item_id = $_GET['id'];
    if (!empty($_SESSION["cart_item"]) && isset($_SESSION["cart_item"][$item_id])) {
        unset($_SESSION["cart_item"][$item_id]);
        header('Location: cart.php');
        exit;
    }
}

include 'header.php';
?>
<style>
/* style.css */

/* Cart Container */
.cart-container {
    max-width: 1200px;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

/* style.css */

.collection {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.collection h2 {
    margin-bottom: 20px;
    color: #333;
    text-align: center;
}

.collection table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.collection th,
.collection td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.collection th {
    background-color: #f4f4f4;
    color: #333;
}

.collection tr:hover {
    background-color: #f9f9f9;
}

.collection .remove-btn {
    display: inline-block;
    padding: 8px 16px;
    background-color: #dc3545;
    color: #fff;
    border: none;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.collection .remove-btn:hover {
    background-color: #c82333;
}

.checkout-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #28a745;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    text-align: center;
    transition: background-color 0.3s;
    text-align: center;
}

.checkout-btn:hover {
    background-color: #218838;
}

/* Footer Styles */
footer {
    background-color: #222;
    color: #f9f9f9;
    text-align: center;
    padding: 20px 0;
    margin-top: 50px;
}

@media (max-width: 768px) {
            th, td {
                padding: 10px;
                font-size: 14px;
            }
            a {
                width: 100%;
                padding: 15px 20px;
            }
        }
        @media (max-width: 480px) {
            th, td {
                padding: 8px;
                font-size: 12px;
            }
            a {
                width: 100%;
                padding: 12px 15px;
            }
        }

</style>
<main class="cart-container">
    <div class="collection">
        <h2>Your Cart</h2>
        <?php
        if (isset($_SESSION["cart_error"])) {
            echo '<div class="alert alert-danger">' . $_SESSION["cart_error"] . '</div>';
            unset($_SESSION["cart_error"]);
        }
        ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!isset($_SESSION["cart_item"]) || empty($_SESSION["cart_item"])) {
                    echo '<tr><td colspan="4" style="text-align:center">Your Cart is Empty</td></tr>';
                } else {
                    $total_price = 0;
                    foreach ($_SESSION["cart_item"] as $item_id => $item) {
                        echo '<tr>';
                        echo '<td>' . $item["name"] . '</td>';
                        echo '<td>$' . $item["price"] . '</td>';
                        echo '<td>
                            <form method="post" action="cart.php">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="' . $item_id . '">
                                <button type="submit" name="quantity" value="' . ($item["quantity"] - 1) . '" class="remove-btn" ' . ($item["quantity"] == 1 ? 'disabled' : '') . '>-</button>
                                ' . $item["quantity"] . '
                                <button type="submit" name="quantity" value="' . ($item["quantity"] + 1) . '" class="remove-btn">+</button>
                            </form>
                        </td>';
                        echo '<td><a href="cart.php?action=remove&id=' . $item_id . '" class="remove-btn">Remove</a></td>';
                        echo '</tr>';
                        $total_price += ($item["price"] * $item["quantity"]);
                    }
                    echo '<tr><td colspan="4" style="text-align:right"><strong>Total: $' . number_format($total_price, 2) . '</strong></td></tr>';
                }
                ?>
            </tbody>
        </table>
        <?php
        if (!empty($_SESSION["cart_item"])) {
            if (isset($_SESSION['user_id'])) {
                echo '<a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>';
            } else {
                echo '<a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>';
            }
        }
        ?>
    </div>
</main>
<?php
include 'footer.php';
?>
