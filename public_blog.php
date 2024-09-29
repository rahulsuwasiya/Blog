<?php
session_start();
include 'db.php'; // Your database connection file

// Initialize search term
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the SQL statement for searching published posts
$sql = "SELECT * FROM posts WHERE status = 'published' AND (title LIKE :search OR content LIKE :search OR tags LIKE :search) ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$search = '%' . $searchTerm . '%'; // Use wildcards for searching
$stmt->bindParam(':search', $search);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Blog</title>
    <link rel="stylesheet" href="style.css"> <!-- Your CSS styles -->
</head>
<body>
    <h1>Published Blog Posts <a href="login.php">Login</a> <!-- Link to the user's dashboard --></h1>
    
    <!-- Search Form -->
    <form method="GET" action="">
        <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Search by title, content, or tags..." required>
        <button type="submit">Search</button>
        
    </form>
    
    <div class="blog-posts">
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <div class="blog-post">
                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                    <div class="post-meta">
                        <span>Published on: <?php echo htmlspecialchars($post['created_at']); ?></span>
                    </div>
                    <div class="post-content">
                        <?php echo htmlspecialchars($post['content']); ?>
                    </div>
                    <?php if (!empty($post['tags'])): ?>
                        <div class="post-tags">
                            <strong>Tags:</strong> <?php echo htmlspecialchars($post['tags']); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No published blog posts available matching your search.</p>
        <?php endif; ?>
    </div>
    
</body>
</html>
