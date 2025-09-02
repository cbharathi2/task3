<?php
require_once __DIR__.'/config.php';
require_login();
verify_csrf();
$id=(int)($_POST['id']??0);
$stmt=$pdo->prepare("SELECT user_id FROM posts WHERE id=?");
$stmt->execute([$id]);
$owner=$stmt->fetchColumn();
if($owner!==current_user()['id']) die("Forbidden");
$stmt=$pdo->prepare("DELETE FROM posts WHERE id=?");
$stmt->execute([$id]);
header('Location: index.php'); exit;
