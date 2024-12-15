<?php
// Include database connection
require_once '../connection.php';

// Set response header to JSON
header("Content-Type: application/json");

// Check for the POST request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate data
    if (
        !isset($data['name']) || empty($data['name']) ||
        !isset($data['price']) || empty($data['price']) ||
        !isset($data['description']) || empty($data['description']) ||
        !isset($data['category_id']) || empty($data['category_id']) ||
        !isset($data['imgPath']) || empty($data['imgPath']) ||
        !isset($data['quantity']) || empty($data['quantity']) ||
        !isset($data['addons']) || !is_array($data['addons'])
    ) {
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields."
        ]);
        exit;
    }

    $name = $data['name'];
    $price = $data['price'];
    $description = $data['description'];
    $category_id = $data['category_id'];
    $imgPath = $data['imgPath'];
    $quantity = $data['quantity'];
    $addons = $data['addons']; // Array of addons

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Insert into `food` table
        $query = "INSERT INTO food (name, price, description, category_id, imgPath, quatity) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sdssss", $name, $price, $description, $category_id, $imgPath, $quantity);
        $stmt->execute();
        $food_id = $stmt->insert_id;

        // Insert into `food_addons` table
        $query = "INSERT INTO food_and_addones (food_id, addones_id) VALUES (?, ?)";
        $stmt = $conn->prepare($query);

        foreach ($addons as $addon_id) {
            $stmt->bind_param("ii", $food_id, $addon_id);
            $stmt->execute();
        }

        // Commit transaction
        $conn->commit();

        echo json_encode([
            "status" => "success",
            "message" => "Food item added successfully."
        ]);
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo json_encode([
            "status" => "error",
            "message" => "Failed to add the food item. $e"
        ]);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method. Use POST."
    ]);
}

// Close the database connection
$conn->close();
