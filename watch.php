<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT r.*, co.title as course_title, co.id as course_id, co.slug as course_slug
    FROM recordings r JOIN courses co ON co.id = r.course_id
    WHERE r.id = ?
");
$stmt->execute([$id]);
$recording = $stmt->fetch();

if (!$recording) {
    header('Location: /recordings.php');
    exit;
}

// Access control: must be enrolled in the course (or be admin)
if (!is_admin()) {
    $stmt = $pdo->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([current_user_id(), $recording['course_id']]);
    if (!$stmt->fetch()) {
        header('Location: /course.php?slug=' . urlencode($recording['course_slug']));
        exit;
    }
}

$page_title = $recording['title'];
include __DIR__ . '/includes/header.php';
?>

<section class="section" style="padding-top: 40px;">
    <div class="container" style="max-width: 900px;">
        <span class="eyebrow"><?= htmlspecialchars($recording['course_title']) ?></span>
        <h1 style="font-size: 28px; margin: 14px 0 24px;"><?= htmlspecialchars($recording['title']) ?></h1>

        <div class="video-wrapper">
            <video controls controlsList="nodownload" preload="metadata">
                <source src="/stream.php?id=<?= (int)$recording['id'] ?>" type="video/mp4">
                Your browser does not support video playback.
            </video>
        </div>

        <?php if ($recording['description']): ?>
        <p style="color: var(--text-muted); line-height: 1.7; margin-top: 20px;">
            <?= htmlspecialchars($recording['description']) ?>
        </p>
        <?php endif; ?>

        <a href="/recordings.php" class="btn btn-outline" style="margin-top: 28px;">&larr; Back to recordings</a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
