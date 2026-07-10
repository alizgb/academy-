<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/auth.php';
require_admin();
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($page_title) ? htmlspecialchars($page_title) . ' | Admin' : 'Admin' ?> — Tech Career Academy</title>
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="dash-layout">
    <aside class="dash-sidebar">
        <a href="/index.php" class="logo"><span class="logo-mark">TC</span> Admin</a>
        <nav class="dash-nav">
            <a href="/admin/index.php" class="<?= $current === 'index.php' ? 'active' : '' ?>">&#8862; Overview</a>
            <a href="/admin/courses.php" class="<?= in_array($current, ['courses.php','course-form.php']) ? 'active' : '' ?>">&#9636; Courses</a>
            <a href="/admin/sessions.php" class="<?= in_array($current, ['sessions.php','session-form.php']) ? 'active' : '' ?>">&#9737; Live Sessions</a>
            <a href="/admin/recordings.php" class="<?= in_array($current, ['recordings.php','recording-form.php']) ? 'active' : '' ?>">&#9654; Recordings</a>
            <a href="/admin/students.php" class="<?= $current === 'students.php' ? 'active' : '' ?>">&#9673; Students</a>
            <a href="/dashboard.php">&#8617; Student view</a>
            <a href="/logout.php">&#8592; Log out</a>
        </nav>
    </aside>
    <main class="dash-main">
