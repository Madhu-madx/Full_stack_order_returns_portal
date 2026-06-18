<?php
include 'db.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: order.php');
    exit;
}

$order_id       = trim($_POST['order_id']);
$reason         = trim($_POST['reason']);
$item_condition = $_POST['item_condition'];

// Basic input check
if (empty($order_id) || empty($reason) || empty($item_condition)) {
    header('Location: order.php?error=db');
    exit;
}

// --- VALIDATION 1: Check order exists ---
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmt->bind_param("s", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: order.php?error=not_found&order_id=' . urlencode($order_id));
    exit;
}

$order = $result->fetch_assoc();

// --- VALIDATION 2: Check return window (30 days) ---
$purchase_date = new DateTime($order['purchase_date']);
$today         = new DateTime();
$diff          = (int)$today->diff($purchase_date)->days;

if ($diff > 30) {
    header('Location: order.php?error=expired&order_id=' . urlencode($order_id));
    exit;
}

// --- VALIDATION 3: Item condition ---
if ($item_condition === 'damaged') {
    header('Location: order.php?error=condition&order_id=' . urlencode($order_id));
    exit;
}

// --- INSERT return request ---
$stmt2 = $conn->prepare(
    "INSERT INTO return_requests
       (order_id, customer_name, item_name, purchase_date, reason, item_condition, status)
     VALUES (?, ?, ?, ?, ?, ?, 'pending')"
);
$stmt2->bind_param(
    "ssssss",
    $order_id,
    $order['customer_name'],
    $order['item_name'],
    $order['purchase_date'],
    $reason,
    $item_condition
);

if ($stmt2->execute()) {
    $new_id = $conn->insert_id;
    $return_id = "RET" . str_pad($new_id, 4, '0', STR_PAD_LEFT);
    header("Location: order.php?success=" . urlencode($return_id));
} else {
    header('Location: order.php?error=db');
}
exit;
?>
