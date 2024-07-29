<?php
session_start();

if (empty($_SESSION['cart'])) {
    header('Location: ../index.php');
    exit;
}

try {
    $db = new PDO('sqlite:../db/pharmacy.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create a copy of the cart
    $cart = $_SESSION['cart'];

    // Clear the cart
    $_SESSION['cart'] = [];

    $total = 0;
    $tax = 0;
    $discount = 0;

    // Calculate totals and prepare sales data
    foreach ($cart as $item) {
        // Retrieve the medicine ID using the name and price
        $stmt = $db->prepare("SELECT id FROM medicines WHERE name = ? AND price = ?");
        $stmt->execute([$item['medicine'], $item['price']]);
        $medicine = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($medicine) {
            $medicine_id = $medicine['id'];
            $quantity_sold = isset($item['quantity']) ? $item['quantity'] : 0;

            $total += $item['price'] * $quantity_sold;

            // Insert into sales table
            $stmt = $db->prepare("INSERT INTO sales (medicine_id, quantity_sold, sale_date) VALUES (?, ?, ?)");
            $stmt->execute([$medicine_id, $quantity_sold, date('Y-m-d')]);
        } else {
            echo "Your cart contains invalid items.";
            exit;
        }
    }

    $tax = $total * 0.05; // Example tax rate of 5%
    $discount = $total * 0.1; // Example discount rate of 10%
    $finalTotal = $total + $tax - $discount;
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Billing</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <style>
        /* Basic styling for the billing page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total-row td {
            font-weight: bold;
        }
        .print-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .print-button:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Billing Details</h2>
        <table>
            <tr>
                <th>Medicine</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php foreach ($cart as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['medicine']); ?></td>
                    <td><?php echo isset($item['quantity']) ? htmlspecialchars($item['quantity']) : 'N/A'; ?></td>
                    <td>$<?php echo number_format($item['price'] * (isset($item['quantity']) ? $item['quantity'] : 0), 2); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="2">Total</td>
                <td>$<?php echo number_format($total, 2); ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="2">Tax</td>
                <td>$<?php echo number_format($tax, 2); ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="2">Discount</td>
                <td>$<?php echo number_format($discount, 2); ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="2">Final Total</td>
                <td>$<?php echo number_format($finalTotal, 2); ?></td>
            </tr>
        </table>
        <button class="print-button" onclick="printPage()">Print Bill</button>
    </div>
</body>
</html>
