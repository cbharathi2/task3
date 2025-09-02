<?php
require __DIR__.'/config.php';

$search = trim($_GET['search'] ?? '');
$params = [];
$sql = "SELECT p.id, p.title, p.content, p.created_at, u.username
        FROM posts p
        LEFT JOIN users u ON p.user_id = u.id";

if($search !== '') {
    $sql .= " WHERE p.title LIKE :s OR p.content LIKE :s OR u.username LIKE :s";
    $params[':s'] = "%$search%";
}

$sql .= " ORDER BY p.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();

include __DIR__.'/header.php';
?>

<h2>All Posts</h2>
<form method="get" style="margin-bottom:1rem;">
<input type="text" name="search" placeholder="Search posts or usernames" value="<?=htmlspecialchars($search)?>">
<button type="submit">Search</button>
</form>

<?php if(!$posts): ?>
<article>No posts found.</article>
<?php endif; ?>

<?php foreach($posts as $post): ?>
<article style="border:1px solid #444; padding:1rem; margin-bottom:1rem;">
<header>
<h3><?=htmlspecialchars($post['title'])?></h3>
<small>By <?=htmlspecialchars($post['username'] ?? 'Anonymous')?> · <?=htmlspecialchars($post['created_at'])?></small>
</header>
<p><?=nl2br(htmlspecialchars($post['content']))?></p>
<?php if(is_logged_in() && $post['username'] === current_user()['username']): ?>
<div class="actions">
    <a href="/myblog/edit.php?id=<?= $post['id'] ?>">Edit</a>
    <form action="/myblog/delete.php" method="post" onsubmit="return confirm('Delete this post?');">
        <input type="hidden" name="csrf_token" value="<?=csrf_token()?>">
        <input type="hidden" name="id" value="<?=$post['id']?>">
        <button type="submit" class="secondary">Delete</button>
    </form>
</div>
<?php endif; ?>
</article>
<?php endforeach; ?>

<?php include __DIR__.'/footer.php'; ?>
