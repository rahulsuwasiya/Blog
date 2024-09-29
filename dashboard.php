<?php
// dashboard.php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch the user's posts from the database
$sql = "SELECT * FROM posts WHERE user_id = :user_id ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Welcome to your dashboard, <?php echo $_SESSION['username']; ?>!</h1>
    <a href="create.php">Create New Post</a> <!-- Link to create a new post -->
    
    <h2>Your Blog Posts</h2>
    <?php if ($posts): ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                        <td><?php echo ucfirst($post['status']); ?></td>
                        <td><?php echo $post['created_at']; ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $post['id']; ?>">Edit</a> |
                            <a href="delete.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You haven't created any posts yet.</p>
    <?php endif; ?>
    <div style="margin-top:10px;">
    <a href="public_blog.php" style="text-decoration: none; margin-top:100px;">Back to blogs</a> 
    </div>
</body>
</html>
