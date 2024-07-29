<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'checkout') {
        // Empty the cart before proceeding to checkout
        header('Location: billing.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Your Cart</h2>
        <?php if (empty($_SESSION['cart'])): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <li>
                        <?php echo htmlspecialchars($item['medicine']); ?> 
                        (Quantity: <?php echo isset($item['quantity']) ? htmlspecialchars($item['quantity']) : 'N/A'; ?>) 
                        - $<?php echo number_format($item['price'] * (isset($item['quantity']) ? $item['quantity'] : 0), 2); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <form method="POST" action="">
                <button type="submit" name="action" value="checkout">Proceed to Checkout</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
