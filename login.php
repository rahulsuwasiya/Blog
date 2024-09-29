<?php
// login.php - Login logic
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username exists
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Password is correct, start session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php"); // Redirect to user dashboard
        exit;
    } else {
        echo "Invalid username or password!";
    }
}
?>

<!-- HTML Form for Login -->
<html>
    <head>
        <link rel="stylesheet" href="style.css"> <!-- Your CSS styles -->
    </head>
<body>
<form method="POST" action="">
    <div class="flex">
    <div class="container">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br>

    <button type="submit">Login</button>
</div>
</div>
</form>
</body>
</html>

