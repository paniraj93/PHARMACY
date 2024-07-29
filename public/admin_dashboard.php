<?php
require_once '../src/db.php';

session_start();

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: admin_login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getDbConnection();
    
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'increase_quantity') {
            $medicine_id = $_POST['medicine_id'];
            $quantity = $_POST['quantity'];

            $stmt = $db->prepare("UPDATE medicines SET quantity = quantity + ? WHERE id = ?");
            $stmt->execute([$quantity, $medicine_id]);
            $message = 'Medicine quantity updated successfully.';
        } elseif ($action === 'add_medicine') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];

            $stmt = $db->prepare("INSERT INTO medicines (name, description, price, quantity) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $quantity]);
            $message = 'New medicine added successfully.';
        } elseif ($action === 'remove_medicine') {
            $medicine_id = $_POST['medicine_id'];

            $stmt = $db->prepare("DELETE FROM medicines WHERE id = ?");
            $stmt->execute([$medicine_id]);
            $message = 'Medicine removed successfully.';
        }
    }
}

// Fetch medicines
$db = getDbConnection();
$medicines = $db->query("SELECT * FROM medicines")->fetchAll(PDO::FETCH_ASSOC);

// Fetch sales data for chart
$sales_data = $db->query("
    SELECT m.name, SUM(s.quantity_sold) AS total_sales
    FROM sales s
    JOIN medicines m ON s.medicine_id = m.id
    GROUP BY s.medicine_id
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="admin-dashboard-container">
        <h2>Admin Dashboard</h2>
        <p><?php echo $message; ?></p>
        
        <form method="POST" action="logout.php">
            <button type="submit">Logout</button>
        </form>

        <h3>Increase Medicine Quantity</h3>
        <form method="POST" action="admin_dashboard.php">
            <input type="hidden" name="action" value="increase_quantity">
            <label for="medicine_id">Medicine:</label>
            <select name="medicine_id" id="medicine_id" required>
                <?php foreach ($medicines as $medicine): ?>
                    <option value="<?php echo $medicine['id']; ?>"><?php echo $medicine['name']; ?></option>
                <?php endforeach; ?>
            </select><br><br>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required><br><br>
            <button type="submit">Increase Quantity</button>
        </form>

        <h3>Add New Medicine</h3>
        <form method="POST" action="admin_dashboard.php">
            <input type="hidden" name="action" value="add_medicine">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>
            <label for="description">Description:</label>
            <input type="text" id="description" name="description"><br><br>
            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" required><br><br>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required><br><br>
            <button type="submit">Add Medicine</button>
        </form>

        <h3>Remove Medicine</h3>
        <form method="POST" action="admin_dashboard.php">
            <input type="hidden" name="action" value="remove_medicine">
            <label for="medicine_id">Medicine:</label>
            <select name="medicine_id" id="medicine_id" required>
                <?php foreach ($medicines as $medicine): ?>
                    <option value="<?php echo $medicine['id']; ?>"><?php echo $medicine['name']; ?></option>
                <?php endforeach; ?>
            </select><br><br>
            <button type="submit">Remove Medicine</button>
        </form>

        <h3>Sales Data</h3>
        <canvas id="salesChart" width="400" height="200"></canvas>
        <script>
            var ctx = document.getElementById('salesChart').getContext('2d');
            var salesData = <?php echo json_encode($sales_data); ?>;
            var labels = salesData.map(data => data.name);
            var data = salesData.map(data => data.total_sales);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Sales',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </div>
</body>
</html>
