<?php
require __DIR__.'/config.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=?");
$stmt->execute([$id]);
$post = $stmt->fetch();
if(!$post) { die('Post not found'); }
if($post['user_id'] !== current_user()['id']) { die('Forbidden'); }

$errors = [];
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    if($title==='') $errors[]='Title required';
    if($content==='') $errors[]='Content required';
    if(!$errors){
        $stmt = $pdo->prepare("UPDATE posts SET title=?, content=? WHERE id=?");
        $stmt->execute([$title, $content, $id]);
        header('Location: /myblog/index.php');
        exit;
    }
}
include __DIR__.'/header.php';
?>
<h2>Edit Post</h2>
<form method="post">
<input type="hidden" name="csrf_token" value="<?=csrf_token()?>">
<label>Title<input type="text" name="title" value="<?=htmlspecialchars($post['title'])?>" required></label>
<label>Content<textarea name="content" rows="6" required><?=htmlspecialchars($post['content'])?></textarea></label>
<button type="submit">Save</button>
</form>
<?php include __DIR__.'/footer.php'; ?>
