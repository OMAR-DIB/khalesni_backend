<?php
require_once '../connection.php';

// Create Addon
function createAddon($name, $price, $quantity) {
    global $connection;
    $query = "INSERT INTO addones (name, price, quatity) VALUES ('$name', $price, $quantity)";
    return mysqli_query($connection, $query);
}

// Read All Addons
function readAddons() {
    global $connection;
    $query = "SELECT * FROM addones";
    return mysqli_query($connection, $query);
}

// Update Addon
function updateAddon($id, $name, $price, $quantity) {
    global $connection;
    $query = "UPDATE addones SET name='$name', price=$price, quatity=$quantity WHERE id=$id";
    return mysqli_query($connection, $query);
}

// Delete Addon
function deleteAddon($id) {
    global $connection;
    $query = "DELETE FROM addones WHERE id=$id";
    return mysqli_query($connection, $query);
}
?>
