<?php
function getDbConnection() {
    $db = new PDO('sqlite:../db/pharmacy.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}

function getRegularMedicines() {
    $db = getDbConnection();
    $stmt = $db->query('SELECT name, price FROM medicines WHERE is_regular = 1');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// functions.php

function addMedicineToCart($medicine, $quantity, $price) {
    // Initialize cart if not set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if medicine is already in the cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['medicine'] === $medicine) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }

    // If not found, add new item
    if (!$found) {
        $_SESSION['cart'][] = ['medicine' => $medicine, 'quantity' => $quantity, 'price' => $price];
    }
}
