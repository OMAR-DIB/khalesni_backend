<?php
// add_order.php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['user_id']) && isset($data['items']) && isset($data['total_price']) && is_array($data['items']) && isset($data['location']) && isset($data['phoneNumber'])) {
        $user_id = $data['user_id'];
        $items = $data['items'];
        $total_price = $data['total_price'];
        $location = $data['location'];
        $phoneNumber = $data['phoneNumber'];

        try {
            $conn->begin_transaction();

            // Insert order
            $stmt = $conn->prepare("INSERT INTO `order` (user_id, status, total_price, location, phoneNumber) VALUES (?, ?, ?, ?, ?)");
            $status = 'pending';
            $stmt->bind_param("isdss", $user_id, $status, $total_price, $location, $phoneNumber);
            $stmt->execute();
            $order_id = $conn->insert_id;

            // Insert multiple items with optional addones
            $stmt = $conn->prepare("INSERT INTO `order_items` (order_id, food_id, addones_id, quantity) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiii", $order_id, $food_id, $addones_id, $quantity);

            foreach ($items as $item) {
                $food_id = $item['food_id'];
                $addones_id = isset($item['addones_id']) ? $item['addones_id'] : NULL; // Set to NULL if no add-ons
                $quantity = $item['quantity'] ?? 1;

                $stmt->execute();
            }

            $conn->commit();
            echo json_encode(["message" => "Order added successfully"]);

        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            echo json_encode(["message" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["message" => "Missing required fields"]);
    }
} else {
    echo json_encode(["message" => "Invalid request method"]);
}
?>
