<?php
/**
 * ONE-TIME SETUP SCRIPT
 * Visit this file once in your browser after importing database/schema.sql
 * to create your admin account. DELETE THIS FILE FROM YOUR SERVER AFTERWARDS.
 */
require_once __DIR__ . '/config/db.php';

// Safety: if an admin already exists, block access.
$existing = $pdo->query("SELECT COUNT(*) as c FROM users WHERE role = 'admin'")->fetch();
if ($existing['c'] > 0) {
    die('An admin account already exists. For security, this setup script is now disabled. Please delete setup.php from your server.');
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($full_name === '' || $email === '' || $password === '') {
        $errors[] = 'Please fill in all fields.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (strlen($password) < 10) {
        $errors[] = 'Password must be at least 10 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, 'admin')");
        $stmt->execute([$full_name, $email, $hash]);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Setup | Tech Career Academy</title>
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <span class="logo-mark" style="margin: 0 auto 16px;">TC</span>
            <h1>Create admin account</h1>
            <p>This one-time setup creates your administrator login.</p>
        </div>

        <?php if ($success): ?>
            <div class="form-success">Admin account created successfully. You can now <a href="/login.php" style="color: inherit; text-decoration: underline;">log in</a>.</div>
            <p style="color: var(--text-muted); font-size: 13px; margin-top: 16px;">
                Important: delete <code>setup.php</code> from your server now for security.
            </p>
        <?php else: ?>
            <?php if (!empty($errors)): ?>
                <div class="form-error"><?= htmlspecialchars(implode(' ', $errors)) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="full_name">Full name</label>
                    <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Admin email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password (10+ characters)</label>
                    <input type="password" id="password" name="password" required minlength="10">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="10">
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg">Create admin account</button>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
