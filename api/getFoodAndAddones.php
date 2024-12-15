<?php
// Include the database connection file
include '../connection.php';

// Set the header to return JSON
header("Content-Type: application/json");

// Create an empty response array
$response = [];

try {
    // Query to fetch food items with their addons
    $sql = "
        SELECT 
            food.id AS food_id,
            food.name AS food_name,
            food.price AS food_price,
            food.description,
            food.imgPath,
            food.quatity,
            category.name AS category_name,
            category.id AS category_id,
            addones.id AS addon_id,
            addones.name AS addon_name,
            addones.price AS addon_price
        FROM 
            food
        LEFT JOIN 
            food_and_addones ON food.id = food_and_addones.food_id
        LEFT JOIN 
            addones ON food_and_addones.addones_id = addones.id
        LEFT JOIN 
            category ON food.category_id = category.id
        ORDER BY 
            food.id
    ";

    // Execute the query
    $result = $conn->query($sql);

    // Initialize a temporary array to group addons with foods
    $foodMap = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $foodId = $row['food_id'];

            // If the food item is not already in the map, add it
            if (!isset($foodMap[$foodId])) {
                $foodMap[$foodId] = [
                    'id' => $foodId,
                    'name' => $row['food_name'],
                    'price' => $row['food_price'],
                    'description' => $row['description'],
                    'imgPath' => $row['imgPath'],
                    'quantity' => $row['quatity'],
                    'category_name' => $row['category_name'],
                    'category_id' => $row['category_id'],
                    'addons' => []
                ];
            }

            // Add addon details if available
            if (!is_null($row['addon_id'])) {
                $foodMap[$foodId]['addons'][] = [
                    'id' => $row['addon_id'],
                    'name' => $row['addon_name'],
                    'price' => $row['addon_price']
                ];
            }
        }

        // Convert the food map to an array
        $response = array_values($foodMap);
    } else {
        $response = ['message' => 'No food items found'];
    }

    // Return the response as JSON
    echo json_encode($response);
} catch (Exception $e) {
    // Handle errors
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>
