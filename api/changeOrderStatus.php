<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get order ID from request
    $orderId = $_POST['order_id'];

    // Prepare and execute SQL query
    $sql = "UPDATE `order` SET status = 'delivered' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $orderId);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Order marked as completed."]);
        } else {
            echo json_encode(["error" => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["error" => $conn->error]);
    }
}

$conn->close();
?>
