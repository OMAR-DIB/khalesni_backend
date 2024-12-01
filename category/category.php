<?php
require_once '../connection.php';

// Create Category
function createCategory($name) {
    global $connection;
    $query = "INSERT INTO category (name) VALUES ('$name')";
    return mysqli_query($connection, $query);
}

// Read All Categories
function readCategories() {
    global $connection;
    $query = "SELECT * FROM category";
    return mysqli_query($connection, $query);
}

// Update Category
function updateCategory($id, $name) {
    global $connection;
    $query = "UPDATE category SET name='$name' WHERE id=$id";
    return mysqli_query($connection, $query);
}

// Delete Category
function deleteCategory($id) {
    global $connection;
    $query = "DELETE FROM category WHERE id=$id";
    return mysqli_query($connection, $query);
}

function readCategoryByName($category_name) {
    global $connection;
    $query = "SELECT id FROM category WHERE name = '$category_name' LIMIT 1";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);  // Return category ID
    } else {
        return null;  // No category found
    }
}

// Function to get products by category ID
function readProductsByCategoryId($category_id) {
    global $connection;
    $query = "SELECT * FROM food WHERE category_id = $category_id";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;  // Collect each product
        }
        return $products;  // Return products
    } else {
        return [];  // No products found
    }
}

?>
