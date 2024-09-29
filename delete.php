<?php
// delete.php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$post_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Delete the post
$sql = "DELETE FROM posts WHERE id = :id AND user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $post_id, ':user_id' => $user_id]);

header("Location: dashboard.php");  // Redirect back to the dashboard after deletion
exit;
?>
