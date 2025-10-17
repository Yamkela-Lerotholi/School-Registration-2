<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// DELETE NEWS HANDLER
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // First get image filename
    $stmt = $pdo->prepare("SELECT image_url FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $newsItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($newsItem) {
        $imagePath = "../uploads/news/" . $newsItem['image_url'];
        if (!empty($newsItem['image_url']) && file_exists($imagePath)) {
            unlink($imagePath); // Delete image file
        }

        // Delete record
        $del = $pdo->prepare("DELETE FROM news WHERE id = ?");
        $del->execute([$id]);
    }
    header('Location: news.php');
    exit;
}

// CREATE NEWS HANDLER
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $t = $_POST['title'] ?? '';
    $c = $_POST['content'] ?? '';
    $img = null;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "../uploads/news/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $tmpName = $_FILES['image']['tmp_name'];
        $originalName = basename($_FILES['image']['name']);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed)) {
            $fileName = uniqid() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
            $target = $uploadDir . $fileName;
            if (move_uploaded_file($tmpName, $target)) {
                $img = $fileName;
            }
        }
    }

    // Save the news post
    $stmt = $pdo->prepare("INSERT INTO news (title, content, image_url, published_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$t, $c, $img]);

    header('Location: news.php');
    exit;
}

// Fetch all news posts
$stmt = $pdo->query("SELECT * FROM news ORDER BY published_at DESC");
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Manage News</title>
<link href="../assets/css/style.css" rel="stylesheet">
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f2f7f2;
    }
    .container {
        padding: 20px;
        max-width: 900px;
        margin: 0 auto;
    }
    .card {
        background: white;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
        position: relative;
    }
    input, textarea {
        width: 100%;
        margin: 5px 0;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
    button {
        background: #2b9348;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
    }
    button:hover {
        background: #55a630;
    }
    img.preview {
        display: block;
        margin-top: 10px;
        max-width: 150px;
        border-radius: 5px;
    }
    .delete-btn {
        background: #e63946;
        color: white;
        border: none;
        padding: 6px 10px;
        border-radius: 5px;
        cursor: pointer;
        float: right;
    }
    .delete-btn:hover {
        background: #b71c1c;
    }
</style>
</head>
<body>
<div class="container">
    <h1>📰 Manage News</h1>

    <form method="post" enctype="multipart/form-data">
        <input name="title" placeholder="Title" required><br>
        <textarea name="content" placeholder="Content" required></textarea><br>
        <label for="image">Upload Image:</label>
        <input type="file" name="image" id="image" accept="image/*"><br>
        <img id="preview" class="preview" src="#" alt="Preview" style="display:none;">
        <button type="submit">Create News</button>
    </form>

    <h2>Existing News</h2>
    <?php foreach($news as $n): ?>
        <div class="card">
            <form method="get" style="display:inline;">
                <button class="delete-btn" type="submit" name="delete" value="<?= $n['id'] ?>" onclick="return confirm('Are you sure you want to delete this news post?');">🗑 Delete</button>
            </form>
            <h3><?= htmlspecialchars($n['title']) ?></h3>
            <p><?= nl2br(htmlspecialchars($n['content'])) ?></p>
            <?php if (!empty($n['image_url'])): ?>
                <img src="../uploads/news/<?= htmlspecialchars($n['image_url']) ?>" width="150">
            <?php endif; ?>
            <small>📅 <?= htmlspecialchars($n['published_at']) ?></small>
        </div>
    <?php endforeach; ?>

    <p><a href="index.php">← Back to Dashboard</a></p>
</div>

<script>
document.getElementById('image').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (file) {
    const preview = document.getElementById('preview');
    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
  }
});
</script>
<?php include '../includes/footer.php'; ?>
</body>
</html>
