<?php
// Include database connection
require_once '../connection.php';

// Set response header to JSON
header("Content-Type: application/json");

// Check for the DELETE request method
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Get the 'id' from the request header
    $id = isset($_SERVER['HTTP_ID']) ? intval($_SERVER['HTTP_ID']) : null;

    // Check if 'id' is provided
    if (!$id) {
        echo json_encode([
            "status" => "error",
            "message" => "Item ID is required."
        ]);
        exit;
    }

    // Prepare the DELETE SQL query
    $query = "DELETE FROM food WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    // Execute the query
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                "status" => "success",
                "message" => "Food item deleted successfully."
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "No food item found with the provided ID."
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to delete the food item."
        ]);
    }

    // Close the statement
    $stmt->close();
} else {
    // If request method is not DELETE
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method. Use DELETE."
    ]);
}

// Close the database connection
$conn->close();
?>
