<?php
session_start();
require_once '../src/functions.php';

$searchTerm = $_GET['term'] ?? '';

function searchMedicines($term) {
    $db = getDbConnection();
    $stmt = $db->prepare('SELECT name, price FROM medicines WHERE name LIKE ?');
    $stmt->execute(['%' . $term . '%']);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$searchResults = searchMedicines($searchTerm);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <div class="menu">
        <a href="admin_login.php">Admin Login</a>
        <form method="POST" action="" class="search-form">
            <input type="text" name="search_term" placeholder="Search Medicines" value="<?php echo htmlspecialchars($searchTerm); ?>" required>
            <button type="submit" name="action" value="search">Search</button>
        </form>
        <a href="cart.php">Cart (<?php echo count($_SESSION['cart'] ?? []); ?>)</a>
    </div>
    <div class="header">
        <h1>Search Results</h1>
    </div>
    <div class="container">
        <?php if (empty($searchResults)): ?>
            <p>Sorry, the requested medicine is unavailable.</p>
        <?php else: ?>
            <h2>Results for "<?php echo htmlspecialchars($searchTerm); ?>"</h2>
            <div class="medicine-container">
                <?php foreach ($searchResults as $medicine): ?>
                    <div class="medicine-card">
                        <h3><?php echo htmlspecialchars($medicine['name']); ?></h3>
                        <p>Price: $<?php echo number_format($medicine['price'], 2); ?></p>
                        <form method="POST" action="../index.php">
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
        <?php endif; ?>
    </div>
</body>
</html>
