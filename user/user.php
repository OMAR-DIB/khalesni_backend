<?php
require_once '../connection.php';

// Create User
function createUser($email, $password, $username, $phone, $address) {
    global $connection;
    $passwordHash = password_hash($password, PASSWORD_BCRYPT); // Securely hash the password
    $query = "INSERT INTO user (email, password, username, phone, address) 
              VALUES ('$email', '$passwordHash', '$username', $phone, '$address')";
    return mysqli_query($connection, $query);
}

// Read All Users
function readUsers() {
    global $connection;
    $query = "SELECT * FROM user";
    return mysqli_query($connection, $query);
}

// Update User
function updateUser($id, $email, $password, $username, $phone, $address) {
    global $connection;
    $passwordHash = password_hash($password, PASSWORD_BCRYPT); // Securely hash the password
    $query = "UPDATE user SET 
                email='$email', 
                password='$passwordHash', 
                username='$username', 
                phone=$phone, 
                address='$address' 
              WHERE id=$id";
    return mysqli_query($connection, $query);
}

// Delete User
function deleteUser($id) {
    global $connection;
    $query = "DELETE FROM user WHERE id=$id";
    return mysqli_query($connection, $query);
}
?>
