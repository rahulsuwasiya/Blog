<?php
session_start();
include 'db.php'; // Your database connection file

if (!isset($_SESSION['user_id'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $tags = $_POST['tags'] ?? '';  // Tags are optional
    $user_id = $_SESSION['user_id'];

    // Check if a draft exists
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = :user_id AND status = 'draft'");
    $stmt->execute([':user_id' => $user_id]);
    $draft = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($draft) {
        // Update existing draft
        $sql = "UPDATE posts SET title = :title, content = :content, tags = :tags, updated_at = NOW() WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $draft['id'],
            ':title' => $title,
            ':content' => $content,
            ':tags' => $tags,
        ]);
    } else {
        // Insert new draft
        $sql = "INSERT INTO posts (user_id, title, content, tags, status) VALUES (:user_id, :title, :content, :tags, 'draft')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':title' => $title,
            ':content' => $content,
            ':tags' => $tags,
        ]);
    }

    echo json_encode(['message' => 'Draft saved successfully']);
    exit;
}
?>
