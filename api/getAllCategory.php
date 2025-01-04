<?php
require '../connection.php'; // Include the database connection

// SQL query to get all categories
$sql = "SELECT id, name FROM category";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $categories = array();
    while($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    echo json_encode(array('status' => 'success', 'categories' => $categories));
} else {
    echo json_encode(array('message' => 'No categories found.'));
}

$conn->close(); // Close the database connection
?>
