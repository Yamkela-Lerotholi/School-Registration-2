<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$gallery = getGalleryImages(8);
$grades = getGrades();



// Fetch latest 6 published news items
$stmt = $pdo->query("SELECT * FROM news ORDER BY published_at DESC LIMIT 6");
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Greenfield Academy</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
</head><body>
<nav class="navbar">
  <div class="container">
    <a class="navbar-brand" href="index.php">Greenfield Academy</a>
    <div class="nav-actions">
      <a class="btn btn-ghost" href="login.php">Login</a>
      <a class="btn btn-primary" href="apply.php" id="applyBtn">Apply Now</a>

    </div>
  </div>
</nav>

<header class="hero">
  <div class="overlay"></div>
  <div class="hero-content">
    <h1>Empowering Young Minds — Greenfield Academy</h1>
    <p>Nurturing Minds, Building Character, Shaping Futures</p>
    <a class="cta btn btn-primary" href="apply.php" id="heroApply">Apply Now</a>
  </div>
</header>

<section id="about" class="section white-section">
  <div class="container">
    <h2>About Us</h2>
    <p>Greenfield Academy is a modern school committed to academic excellence and character development. Our experienced teachers and engaging programs help students reach their full potential.</p>
  </div>
</section>

<section id="academics" class="section green-section">
  <div class="container">
    <h2>Academics</h2>
    <div class="grid">
      <?php foreach($grades as $g): ?>
      <div class="card hover-raise">
        <h4><?php echo htmlspecialchars($g['grade_name']); ?></h4>
        <p><?php echo htmlspecialchars($g['description']); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section id="news" class="section white-section" style="background: #f8fff8;">
  <div class="container">
    <h2 style="color:#2b9348; text-align:center; margin-bottom:30px;">📰 Latest News</h2>
    <div class="grid" style="display:grid; grid-template-columns: repeat(auto-fit,minmax(250px,1fr)); gap:20px;">
      <?php if (!empty($news)): ?>
        <?php foreach($news as $n): ?>
          <div class="news-card hover-shake" style="background:white; border-radius:15px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s;">
            <img src="uploads/news/<?php echo htmlspecialchars($n['image_url']); ?>" alt="" style="width:100%; height:180px; object-fit:cover;">
            <div class="news-body" style="padding:15px;">
              <h4 style="color:#f77f00;"><?php echo htmlspecialchars($n['title']); ?></h4>
              <p style="color:#333;"><?php echo htmlspecialchars(substr($n['content'],0,120)); ?>...</p>
              <small style="color:#666;">📅 <?php echo date("F j, Y", strtotime($n['published_at'])); ?></small>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="text-align:center; color:#555;">No news articles available yet. Please check back later!</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<style>
.hover-shake:hover {
  transform: translateY(-6px);
  box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
}
.hover-shake img {
  transition: transform 0.4s ease;
}
.hover-shake:hover img {
  transform: scale(1.05);
}
</style>


<section id="gallery" class="section lightgreen-section">
  <div class="container">
    <h2>Gallery</h2>
    <div class="gallery-grid">
      <?php
      // Fetch all gallery images from the database
      require_once 'includes/config.php';
      $stmt = $pdo->query("SELECT * FROM gallery ORDER BY uploaded_at DESC");
      $gallery = $stmt->fetchAll(PDO::FETCH_ASSOC);
      ?>

      <?php if (!empty($gallery)): ?>
        <?php foreach ($gallery as $g): ?>
          <?php
          // Correct path to uploaded images
          $imagePath = "uploads/gallery/" . htmlspecialchars($g['image_url']);
          ?>
          <div class="gallery-item hover-shake">
            <?php if (!empty($g['image_url']) && file_exists($imagePath)): ?>
              <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($g['title']) ?>">
            <?php else: ?>
              <img src="assets/images/no-image.jpg" alt="No image available">
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No gallery items yet.</p>
      <?php endif; ?>
    </div>
  </div>
</section>


<footer class="footer" style="background:#2b9348; color:white; padding:5px 0; font-size:14px;">
  <div class="container" style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:flex-start; gap:10px;">
    
    <div class="footer-col" style="flex:1; min-width:100px;">
      <h4 style="margin-bottom:5px; font-size:16px;">Greenfield Academy</h4>
      <p style="margin:0;">123 Education Lane, Greenfield</p>
      <p style="margin:0;">Email: <a href="mailto:info@greenfieldacademy.co.za" style="color:#fff; text-decoration:underline;">info@greenfieldacademy.co.za</a></p>
      <p style="margin:0;">Phone: +27 11 234 5678</p>
    </div>

    <div class="footer-col" style="flex:1; min-width:100px;">
      <h4 style="margin-bottom:5px; font-size:16px;">Quick Links</h4>
      <ul style="list-style:none; padding:0; margin:0;">
        <li><a href="#about" style="color:white; text-decoration:none;">About Us</a></li>
        <li><a href="#academics" style="color:white; text-decoration:none;">Academics</a></li>
        <li><a href="#news" style="color:white; text-decoration:none;">News</a></li>
        <li><a href="#gallery" style="color:white; text-decoration:none;">Gallery</a></li>
        <li><a href="apply.php" style="color:white; text-decoration:none;">Apply Now</a></li>
      </ul>
    </div>

    <div class="footer-col" style="flex:1; min-width:160px;">
      <h4 style="margin-bottom:5px; font-size:16px;">Follow Us</h4>
      <p style="margin:0;">
        <a href="#" style="color:white; text-decoration:none;">🌐 Facebook</a><br>
        <a href="#" style="color:white; text-decoration:none;">📸 Instagram</a><br>
        <a href="#" style="color:white; text-decoration:none;">🐦 Twitter</a>
      </p>
    </div>
  </div>

  <div class="footer-bottom" style="text-align:center; border-top:1px solid rgba(255,255,255,0.2); margin-top:10px; padding-top:5px; font-size:13px;">
    <p style="margin:0;">&copy; <?php echo date('Y'); ?> Greenfield Academy. Designed By LeroTech Solutions</p>
  </div>
</footer>

