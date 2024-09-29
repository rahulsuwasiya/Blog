<?php
// create.php - Creating a blog post with TinyMCE
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = strip_tags($_POST['content'], '<strong><em><a><ul><ol><li>');
    $tags = $_POST['tags'] ?? '';  // Tags are optional
    $status = $_POST['status'];    // Status: draft or published
    $user_id = $_SESSION['user_id'];

    // Validate title and content
    if (!empty($title) && !empty($content)) {
        // Insert new post with tags into the database
        $sql = "INSERT INTO posts (user_id, title, content, tags, status) VALUES (:user_id, :title, :content, :tags, :status)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':title' => $title,
            ':content' => $content,
            ':tags' => $tags,
            ':status' => $status,
        ]);

        header("Location: dashboard.php");
        exit;
    } else {
        echo "Both title and content are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blog Post</title>
    <link rel="stylesheet" href="style.css"> <!-- Your CSS styles -->
    <script src="https://cdn.tiny.cloud/1/h6qmxprhi7tgou0yd6j7kap0cgmkjgrcoxjmfnfjx6p8fgu4/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'link image code',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code',
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save(); // Sync content back to textarea for validation
                });
            }
        });
    </script>
</head>
<body>
    <h1>Create a New Post</h1>
    <form method="POST" action="">
        <div class="create_div">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br>
        <div class="create">
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="5" required></textarea><br></div>

        <label for="tags">Tags (optional):</label>
        <input type="text" id="tags" name="tags"><br>

        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
        </select><br>

        <button type="submit">Create Post</button>
    </div>
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
