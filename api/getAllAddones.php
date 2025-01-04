<?php
// Include database connection
require_once '../connection.php';

// Set response header to JSON
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $query = "SELECT id, name, price FROM addones";
        $result = $conn->query($query);

        $addons = [];
        while ($row = $result->fetch_assoc()) {
            $addons[] = $row;
        }

        echo json_encode([
            "status" => "success",
            "addons" => $addons
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to fetch addons: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method. Use GET."
    ]);
}

$conn->close();
?>
    