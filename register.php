<?php
require __DIR__ . '/config.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    // --- Validation ---
    if ($username === '') $errors[] = 'Username required';
    if (strlen($username) < 3) $errors[] = 'Username must be at least 3 characters';
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) $errors[] = 'Username can only contain letters, numbers, and underscores';

    if ($password === '') $errors[] = 'Password required';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters';
    if ($password !== $confirm) $errors[] = 'Passwords do not match';

    if (!$errors) {
        // Check if username exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        if ($stmt->fetch()) {
            $errors[] = 'Username already taken';
        } else {
            // Hash password
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Assign role = user (default)
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
            $stmt->execute([
                'username' => $username,
                'password' => $hash,
                'role'     => 'user'
            ]);

            header('Location: /myblog/login.php');
            exit;
        }
    }
}

include __DIR__ . '/header.php';
?>

<h2>Register</h2>
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
    <label>Confirm Password <input type="password" name="confirm" required></label><br>
    <button type="submit">Register</button>
</form>

<p>Already registered? <a href="/myblog/login.php">Login</a></p>

<?php include __DIR__ . '/footer.php'; ?>
