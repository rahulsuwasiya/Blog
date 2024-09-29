<?php
// edit.php - Editing a blog post with TinyMCE
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$post_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch the post to edit
$sql = "SELECT * FROM posts WHERE id = :id AND user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $post_id, ':user_id' => $user_id]);
$post = $stmt->fetch();

if (!$post) {
    echo "Post not found or you don't have permission to edit this post!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = strip_tags($_POST['content'], '<strong><em><a><ul><ol><li>');
    $tags = $_POST['tags'];
    $status = $_POST['status'];

    if (!empty($title) && !empty($content)) {
        // Update the post in the database
        $sql = "UPDATE posts SET title = :title, content = :content, tags = :tags, status = :status, updated_at = NOW() WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':tags' => $tags,
            ':status' => $status,
            ':id' => $post_id,
        ]);

        header("Location: dashboard.php");
        exit;
    } else {
        echo "Both title and content are required!";
    }
}
?>

<!-- HTML Form for Editing Post -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="style.css"> <!-- Your CSS styles -->
    <script src="https://cdn.tiny.cloud/1/h6qmxprhi7tgou0yd6j7kap0cgmkjgrcoxjmfnfjx6p8fgu4/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'link image code',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code',
        });
    </script>
</head>
<body>
    <h1>Edit Post</h1>
    <form method="POST" action="">
    <div class="create_div">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required><br>

        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($post['content']); ?></textarea><br>

        <label for="tags">Tags (optional):</label>
        <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($post['tags']); ?>"><br>

        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="draft" <?php if ($post['status'] === 'draft') echo 'selected'; ?>>Draft</option>
            <option value="published" <?php if ($post['status'] === 'published') echo 'selected'; ?>>Published</option>
        </select><br>

        <button type="submit">Update Post</button></div>
    </form>
    <a href="dashboard.php">Go back to Dashboard</a>
    <script>
let autoSaveTimeout;

// Event listener for content input
document.getElementById('content').addEventListener('input', function() {
    clearTimeout(autoSaveTimeout);  // Clear previous timeout
    autoSaveTimeout = setTimeout(autoSaveDraft, 2000);  // Auto-save after 2 seconds of inactivity
});

// Function to auto-save the draft
function autoSaveDraft() {
    let title = document.getElementById('title').value;
    let content = tinymce.get('content').getContent();
    let tags = document.getElementById('tags').value;
    let status = 'draft'; // Set status as draft

    // Send an AJAX request to save the draft
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'save_draft.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send(`title=${encodeURIComponent(title)}&content=${encodeURIComponent(content)}&tags=${encodeURIComponent(tags)}&status=${status}`);
}
</script>

</body>
</html>
