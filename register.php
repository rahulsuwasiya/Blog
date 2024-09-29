<?php
// register.php - Registration logic
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate the inputs
    if (!empty($username) && !empty($email) && !empty($password)) {
        // Encrypt the password using bcrypt
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Insert user data into the database
        $sql = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password_hash' => $password_hash,
            ]);
            echo "User registered successfully!";
            header("Location: login.php"); // Redirect to login page after successful registration
            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "All fields are required!";
    }
}
?>

<!-- HTML Form for Registration -->
<html>
    <head>
         <link rel="stylesheet" href="style.css"> <!-- Your CSS styles -->
    </head>
<body>
<form method="POST" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br>

    <button type="submit">Sign Up</button>
</form>
</body>
</html>
