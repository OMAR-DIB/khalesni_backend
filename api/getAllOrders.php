<?php
// Database connection
include '../connection.php';

header('Content-Type: application/json');

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Query to get all orders with user details and food/add-ons details
    $query = "
        SELECT 
            `order`.id AS order_id, 
            `order`.status, 
            `user`.username AS user_name,
            `order_items`.id AS order_items_id, 
            `order_items`.food_id, 
            `order_items`.addones_id,
            `order_items`.quantity,
            `order`.total_price,
            `food`.name AS food_name, 
            `food`.price AS food_price,
            `food`.imgPath AS imgPath,
            `addones`.name AS addon_name, 
            `addones`.price AS addon_price
        FROM 
            `order`
        JOIN 
            `user` ON `order`.user_id = `user`.id
        JOIN 
            `order_items` ON `order`.id = `order_items`.order_id
        JOIN 
            `food` ON `order_items`.food_id = `food`.id
        LEFT JOIN 
            `addones` ON `order_items`.addones_id = `addones`.id";

    if ($stmt = $conn->prepare($query)) {
        $stmt->execute();
        $result = $stmt->get_result();

        $orders = [];

        while ($row = $result->fetch_assoc()) {
            $orders[] = [
                'order_id' => $row['order_id'],
                'status' => $row['status'],
                'user_name' => $row['user_name'],
                'items' => [
                    'food_id' => $row['food_id'],
                    'addones_id' => $row['addones_id'],
                    'quantity' => $row['quantity'],
                    'total_price' => $row['total_price'],
                    'food_details' => [
                        'name' => $row['food_name'],
                        'price' => $row['food_price'],
                        'imgPath' => $row['imgPath']
                    ],
                    'addons' => [
                        'name' => $row['addon_name'],
                        'price' => $row['addon_price'],
                    ]
                ]
            ];
        }

        echo json_encode($orders);
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Failed to prepare the statement']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

$conn->close();
?>
