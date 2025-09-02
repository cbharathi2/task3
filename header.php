<?php require_once __DIR__.'/config.php'; ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>MyBlog</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
<style>
body { max-width: 900px; margin: 2rem auto; background:#121212; color:#eee; }
a { color:#1e90ff; }
.actions { display:flex; gap:.5rem; flex-wrap:wrap; }
input, textarea { background:#222; color:#eee; }
header nav ul li a { color:#eee; }
footer { text-align:center; margin-top:2rem; }
</style>
</head>
<body>
<header>
<nav>
<ul>
    <li><strong><a href="/myblog/index.php">MyBlog</a></strong></li>
</ul>
<ul>
<?php if(is_logged_in()): ?>
    <li>Hi, <?=htmlspecialchars(current_user()['username'])?></li>
    <li><a href="/myblog/create.php">New Post</a></li>
    <li><a href="/myblog/logout.php">Logout</a></li>
<?php else: ?>
    <li><a href="/myblog/login.php">Login</a></li>
    <li><a href="/myblog/register.php">Register</a></li>
<?php endif; ?>
</ul>
</nav>
</header>
<main>
