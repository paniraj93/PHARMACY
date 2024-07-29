<?php
require_once '../src/db.php';

session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password for comparison
    $hashed_password = hash('sha256', $password);

    // Retrieve admin user
    $db = getDbConnection();
    $stmt = $db->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->execute([$username, $hashed_password]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin_dashboard.php');  // Redirect to admin dashboard
        exit;
    } else {
        $message = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="POST" action="admin_login.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <button type="submit">Login</button>
        </form>
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
