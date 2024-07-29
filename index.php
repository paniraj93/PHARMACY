<?php
session_start();
require_once '../src/functions.php';

// Get the list of regular medicines
$regularMedicines = getRegularMedicines();

// Initialize the cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action == 'add_to_cart') {
        $medicine = $_POST['medicine'];
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        $price = $_POST['price'];

        // Check if the medicine is already in the cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['medicine'] === $medicine) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        // If not found, add new entry
        if (!$found) {
            $_SESSION['cart'][] = ['medicine' => $medicine, 'quantity' => $quantity, 'price' => $price];
        }
    } elseif ($action == 'buy_now') {
        $medicine = $_POST['medicine'];
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        $price = $_POST['price'];

        // Set the cart with the single item and redirect to billing
        $_SESSION['cart'] = [['medicine' => $medicine, 'quantity' => $quantity, 'price' => $price]];
        header('Location: billing.php');
        exit;
    } elseif ($action == 'search') {
        $searchTerm = $_POST['search_term'];
        header('Location: search.php?term=' . urlencode($searchTerm));
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pharmaceutical Management System</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <div class="menu">
        <a href="admin_login.php">Admin Login</a>
        <form method="POST" action="" class="search-form">
            <input type="text" name="search_term" placeholder="Search Medicines" required>
            <button type="submit" name="action" value="search">Search</button>
        </form>
        <a href="cart.php">Cart (<?php echo count($_SESSION['cart']); ?>)</a>
    </div>
    <div class="header">
        <h1>Welcome to Our Pharmacy</h1>
    </div>
    <div class="container">
        <h2>Regular Medicines</h2>
        <div class="medicine-container">
            <?php foreach ($regularMedicines as $medicine): ?>
                <div class="medicine-card">
                    <h3><?php echo htmlspecialchars($medicine['name']); ?></h3>
                    <p>Price: $<?php echo number_format($medicine['price'], 2); ?></p>
                    <form method="POST" action="">
                        <input type="hidden" name="medicine" value="<?php echo htmlspecialchars($medicine['name']); ?>">
                        <input type="hidden" name="price" value="<?php echo htmlspecialchars($medicine['price']); ?>">
                        <input type="number" name="quantity" value="1" min="1" required>
                        <br><br>
                        <button type="submit" name="action" value="add_to_cart">Add to Cart</button>
                        <button type="submit" name="action" value="buy_now">Buy Now</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
