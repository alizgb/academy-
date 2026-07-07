<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("
    SELECT co.*, cat.name as category_name
    FROM courses co JOIN categories cat ON cat.id = co.category_id
    WHERE co.slug = ? AND co.is_published = 1
");
$stmt->execute([$slug]);
$course = $stmt->fetch();

if (!$course) {
    header('Location: /courses.php');
    exit;
}

$is_enrolled = false;
if (is_logged_in()) {
    $stmt = $pdo->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([current_user_id(), $course['id']]);
    $is_enrolled = (bool)$stmt->fetch();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll'])) {
    if (!is_logged_in()) {
        header('Location: /register.php');
        exit;
    }
    csrf_check();
    $stmt = $pdo->prepare("INSERT IGNORE INTO enrollments (user_id, course_id) VALUES (?, ?)");
    $stmt->execute([current_user_id(), $course['id']]);
    header('Location: /dashboard.php');
    exit;
}

$page_title = $course['title'];
include __DIR__ . '/includes/header.php';
?>

<section class="section" style="padding-top:56px;">
    <div class="container" style="max-width: 820px;">
        <span class="eyebrow"><?= htmlspecialchars($course['category_name']) ?></span>
        <h1 style="font-size: 36px; margin: 16px 0;"><?= htmlspecialchars($course['title']) ?></h1>
        <p style="color: var(--text-muted); font-size: 17px; margin-bottom: 28px;"><?= htmlspecialchars($course['short_description']) ?></p>

        <div style="display:flex; gap:24px; flex-wrap:wrap; margin-bottom: 36px; padding: 20px 0; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
            <div><strong>Level:</strong> <?= htmlspecialchars(ucfirst($course['level'])) ?></div>
            <div><strong>Duration:</strong> <?= $course['duration_weeks'] ? $course['duration_weeks'] . ' weeks' : 'Self-paced' ?></div>
            <?php if ($course['teacher_name']): ?><div><strong>Instructor:</strong> <?= htmlspecialchars($course['teacher_name']) ?></div><?php endif; ?>
        </div>

        <div style="color: var(--text-muted); line-height: 1.8; margin-bottom: 40px; white-space: pre-line;">
            <?= htmlspecialchars($course['description'] ?: 'Full curriculum details coming soon.') ?>
        </div>

        <?php if ($is_enrolled): ?>
            <a href="/dashboard.php" class="btn btn-primary btn-lg">Go to your dashboard</a>
        <?php else: ?>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <button type="submit" name="enroll" value="1" class="btn btn-primary btn-lg">
                    <?= is_logged_in() ? 'Enroll in this course' : 'Sign up to enroll' ?>
                </button>
            </form>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
