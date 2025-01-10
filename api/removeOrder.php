<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $orderId = $data['id'];

    // Prepare and execute SQL query
    $sql = "DELETE FROM `order` WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $orderId);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "Order removed successfully."]);
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
