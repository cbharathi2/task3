<?php
require __DIR__.'/config.php';
require_login();
$errors = [];

if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    if($title==='') $errors[]='Title is required';
    if($content==='') $errors[]='Content is required';
    if(!$errors){
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$title, $content, current_user()['id']]);
        header('Location: /myblog/index.php');
        exit;
    }
}
include __DIR__.'/header.php';
?>
<h2>New Post</h2>
<?php if($errors) foreach($errors as $e) echo "<p>$e</p>"; ?>
<form method="post">
<input type="hidden" name="csrf_token" value="<?=csrf_token()?>">
<label>Title<input type="text" name="title" required></label>
<label>Content<textarea name="content" rows="6" required></textarea></label>
<button type="submit">Create</button>
</form>
<?php include __DIR__.'/footer.php'; ?>
