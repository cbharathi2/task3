<?php
require __DIR__ . '/config.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Welcome to MyBlog</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
  <style>
    body {
      max-width: 900px;
      margin: 2rem auto;
      text-align: center;
    }
    .btn-container {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-top: 2rem;
    }
  </style>
</head>
<body>
  <header>
    <h1>Welcome to MyBlog!</h1>
  </header>

  <p>
    MyBlog is a simple PHP + MySQL web application where users can create, read, update, 
    and delete posts. Share your thoughts, read public blogs, and explore content from other users.
  </p>

  <div class="btn-container">
    <?php if (!is_logged_in()): ?>
      <a href="register.php" class="button">Register</a>
      <a href="login.php" class="button secondary">Login</a>
    <?php else: ?>
     <a href="index.php" class="button">Go to Blog</a>
       <a href="logout.php" class="button secondary">Logout</a>
    <?php endif; ?>
  </div>

  <footer style="margin-top:3rem;">
    <small>© <?= date('Y') ?> MyBlog</small>
  </footer>
</body>
</html>
