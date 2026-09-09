<?php require_once __DIR__ . '/../config/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($page_title) ? htmlspecialchars($page_title) . ' | Tech Career Academy' : 'Tech Career Academy — From Zero to Hired in IT' ?></title>
<meta name="description" content="Tech Career Academy trains you for real IT careers with live instructor-led sessions and on-demand recordings — IT Support, Networking, and Computer Skills tracks.">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <a href="/index.php" class="logo">
            <span class="logo-mark">TC</span>
            Tech Career Academy
        </a>
        <div class="nav-links">
            <a href="/courses.php">Courses</a>
            <a href="/index.php#testimonials">Testimonials</a>
            <a href="/index.php#faq">FAQ</a>
        </div>
        <div class="nav-actions">
            <?php if (is_logged_in()): ?>
                <a href="/dashboard.php" class="btn btn-outline">Dashboard</a>
                <?php if (is_admin()): ?>
                    <a href="/admin/index.php" class="btn btn-primary">Admin</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="/login.php" class="btn btn-outline">Login</a>
                <a href="/courses.php?category=it-support" class="btn btn-primary">Explore IT Support</a>
            <?php endif; ?>
            <button class="nav-toggle" aria-label="Toggle menu">&#9776;</button>
        </div>
    </div>
</nav>
