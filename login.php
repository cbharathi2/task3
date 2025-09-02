<?php
require __DIR__ . '/config.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = 'All fields are required';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Store session info
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role']; // <- role added

            header('Location: /myblog/index.php');
            exit;
        } else {
            $errors[] = 'Invalid credentials';
        }
    }
}

include __DIR__ . '/header.php';
?>

<h2>Login</h2>
<?php if ($errors): ?>
    <div style="color:red;">
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <label>Username <input type="text" name="username" required></label><br>
    <label>Password <input type="password" name="password" required></label><br>
    <button type="submit">Login</button>
</form>

<p>No account? <a href="/myblog/register.php">Register</a></p>

<?php include __DIR__ . '/footer.php'; ?>
