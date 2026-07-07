<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';
require_login();

$uid = current_user_id();
$stmt = $pdo->prepare("SELECT course_id FROM enrollments WHERE user_id = ?");
$stmt->execute([$uid]);
$course_ids = array_column($stmt->fetchAll(), 'course_id');

$sessions = [];
if (!empty($course_ids)) {
    $placeholders = implode(',', array_fill(0, count($course_ids), '?'));
    $stmt = $pdo->prepare("
        SELECT ls.*, co.title as course_title
        FROM live_sessions ls
        JOIN courses co ON co.id = ls.course_id
        WHERE ls.course_id IN ($placeholders)
        ORDER BY ls.session_date DESC
    ");
    $stmt->execute($course_ids);
    $sessions = $stmt->fetchAll();
}

$page_title = 'Live Sessions';
include __DIR__ . '/includes/header.php';
?>

<div class="dash-layout">
    <aside class="dash-sidebar">
        <a href="/index.php" class="logo"><span class="logo-mark">TC</span> Tech Career</a>
        <nav class="dash-nav">
            <a href="/dashboard.php">&#8862; Overview</a>
            <a href="/courses.php">&#9636; Browse Courses</a>
            <a href="/live-sessions.php" class="active">&#9737; Live Sessions</a>
            <a href="/recordings.php">&#9654; Recordings</a>
            <a href="/logout.php">&#8592; Log out</a>
        </nav>
    </aside>
    <main class="dash-main">
        <div class="dash-header">
            <h1>Live sessions</h1>
            <p>All scheduled Teams sessions across your enrolled courses.</p>
        </div>

        <?php if (empty($sessions)): ?>
            <div class="empty-state">No live sessions found. Enroll in a course to see scheduled sessions here.</div>
        <?php else: ?>
            <?php foreach ($sessions as $s):
                $is_past = strtotime($s['session_date']) < time();
            ?>
            <div class="session-row">
                <div class="info">
                    <h4><?= htmlspecialchars($s['title']) ?></h4>
                    <span><?= htmlspecialchars($s['course_title']) ?> &middot; <?= date('D, M j, Y g:i A', strtotime($s['session_date'])) ?></span>
                </div>
                <?php if ($is_past): ?>
                    <span class="badge badge-muted">Ended</span>
                <?php else: ?>
                    <a href="<?= htmlspecialchars($s['teams_link']) ?>" target="_blank" rel="noopener" class="btn btn-primary">Join on Teams</a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
