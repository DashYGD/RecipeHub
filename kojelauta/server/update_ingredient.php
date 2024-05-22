<?php
// update_ingredient.php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$owner = $data['owner'];
$oldName = $data['oldName'];
$name = $data['name'];
$quantity = $data['quantity'];
$unit = $data['unit'];
$price = $data['price'];

// Database connection (adjust with your database details)
$conn = new mysqli('localhost', 'username', 'password', 'database');

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed.']));
}

$sql = "UPDATE ingredients SET name=?, quantity=?, unit=?, price=? WHERE owner=? AND name=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssssss', $name, $quantity, $unit, $price, $owner, $oldName);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update ingredient.']);
}

$stmt->close();
$conn->close();
?>
