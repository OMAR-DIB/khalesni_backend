<?php
// Replace 'your_password' with the actual password you want to hash
$password = 'adminadmin';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Display the hashed password
echo "Hashed Password: " . $hashedPassword;
?>
git rebase -i HEAD~<1>
