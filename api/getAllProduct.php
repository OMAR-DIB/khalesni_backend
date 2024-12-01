
<?php
// Include the database connection file
include '../connection.php';

// Set content type to JSON
header('Content-Type: application/json');


// SQL query to fetch food items and their add-ons
$query = "
    SELECT 
        f.id AS food_id,
        f.name AS food_name,
        f.price AS food_price,
        f.description AS food_description,
        f.imgPath AS food_imgPath,
        f.category_id,
        f.quatity AS food_quantity,
        c.name AS category_name,
        a.id AS addon_id,
        a.name AS addon_name,
        a.price AS addon_price,
        a.quatity AS addon_quantity
    FROM 
        food f
    LEFT JOIN 
        category c ON f.category_id = c.id
    LEFT JOIN 
        food_and_addones fa ON f.id = fa.food_id
    LEFT JOIN 
        addones a ON fa.addones_id = a.id
";

// Execute the query
$result = mysqli_query($conn, $query);

if (!$result) {
    // Return error if query fails
    echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . mysqli_error($conn)]);
    exit;
}

// Group food and their add-ons
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $food_id = $row['food_id'];

    // Initialize the food item if it doesn't exist
    if (!isset($data[$food_id])) {
        $data[$food_id] = [
            'id' => $row['food_id'],
            'name' => $row['food_name'],
            'price' => $row['food_price'],
            'description' => $row['food_description'],
            'imgPath' => $row['food_imgPath'],
            'category_id' => $row['category_id'],
            'category_name' => $row['category_name'],
            'quantity' => $row['food_quantity'],
            'addons' => []
        ];
    }

    // Add the addon if available
    if (!empty($row['addon_id'])) {
        $data[$food_id]['addons'][] = [
            'id' => $row['addon_id'],
            'name' => $row['addon_name'],
            'price' => $row['addon_price'],
            'quantity' => $row['addon_quantity']
        ];
    }
}

// Reindex the result to be a list
$response = array_values($data);

// Output the JSON response
echo json_encode(['food' => $response], JSON_PRETTY_PRINT);

// Close the database connection
mysqli_close($conn);
?>
