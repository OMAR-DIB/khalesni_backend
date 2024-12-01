<?php
require_once 'connection.php';

// Create Food
function createFood($name, $price, $description, $imgPath, $categoryId, $quantity) {
    global $connection;

    // Escape strings for security
    $name = mysqli_real_escape_string($connection, $name);
    $description = mysqli_real_escape_string($connection, $description);
    $imgPath = mysqli_real_escape_string($connection, $imgPath);

    // Prepare query
    $query = "INSERT INTO food (name, price, description, img_path, category_id, quatity) 
              VALUES ('$name', $price, '$description', '$imgPath', $categoryId, $quantity)";

    return mysqli_query($connection, $query);
}

// Read All Food Items
function readFood() {
    global $connection;
    $query = "SELECT * FROM food";
    return mysqli_query($connection, $query);
}

// Update Food
function updateFood($id, $name, $price, $description, $imgPath, $categoryId, $quantity) {
    global $connection;

    // Escape strings for security
    $name = mysqli_real_escape_string($connection, $name);
    $description = mysqli_real_escape_string($connection, $description);
    $imgPath = mysqli_real_escape_string($connection, $imgPath);

    // Prepare query
    $query = "UPDATE food SET 
                name = '$name', 
                price = $price, 
                description = '$description', 
                img_path = '$imgPath', 
                category_id = $categoryId, 
                quatity = $quantity 
              WHERE id = $id";

    return mysqli_query($connection, $query);
}

// Delete Food
function deleteFood($id) {
    global $connection;
    $query = "DELETE FROM food WHERE id = $id";
    return mysqli_query($connection, $query);
}
?>
