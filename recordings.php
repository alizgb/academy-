<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';
require_login();

$uid = current_user_id();
$stmt = $pdo->prepare("SELECT course_id FROM enrollments WHERE user_id = ?");
$stmt->execute([$uid]);
$course_ids = array_column($stmt->fetchAll(), 'course_id');

$recordings = [];
if (!empty($course_ids)) {
    $placeholders = implode(',', array_fill(0, count($course_ids), '?'));
    $stmt = $pdo->prepare("
        SELECT r.*, co.title as course_title
        FROM recordings r
        JOIN courses co ON co.id = r.course_id
        WHERE r.course_id IN ($placeholders)
        ORDER BY r.uploaded_at DESC
    ");
    $stmt->execute($course_ids);
    $recordings = $stmt->fetchAll();
}

$page_title = 'Recordings';
include __DIR__ . '/includes/header.php';
?>

<div class="dash-layout">
    <aside class="dash-sidebar">
        <a href="/index.php" class="logo"><span class="logo-mark">TC</span> Tech Career</a>
        <nav class="dash-nav">
            <a href="/dashboard.php">&#8862; Overview</a>
            <a href="/courses.php">&#9636; Browse Courses</a>
            <a href="/live-sessions.php">&#9737; Live Sessions</a>
            <a href="/recordings.php" class="active">&#9654; Recordings</a>
            <a href="/logout.php">&#8592; Log out</a>
        </nav>
    </aside>
    <main class="dash-main">
        <div class="dash-header">
            <h1>Recorded sessions</h1>
            <p>Rewatch any past session from your enrolled courses, anytime.</p>
        </div>

        <?php if (empty($recordings)): ?>
            <div class="empty-state">No recordings available yet. Enroll in a course to access its recordings here.</div>
        <?php else: ?>
        <div class="course-grid">
            <?php foreach ($recordings as $r): ?>
            <div class="course-card">
                <div class="course-thumb">&#9654; <?= htmlspecialchars($r['course_title']) ?></div>
                <div class="course-card-body">
                    <span class="course-tag"><?= htmlspecialchars($r['course_title']) ?></span>
                    <h3><?= htmlspecialchars($r['title']) ?></h3>
                    <p class="course-card-desc"><?= htmlspecialchars(mb_strimwidth($r['description'] ?? '', 0, 90, '...')) ?></p>
                    <a href="/watch.php?id=<?= $r['id'] ?>" class="btn btn-outline btn-block">Watch now</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
