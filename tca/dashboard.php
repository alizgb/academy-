<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';
require_login();

$uid = current_user_id();

// Enrolled courses
$stmt = $pdo->prepare("
    SELECT co.*, cat.name as category_name
    FROM enrollments e
    JOIN courses co ON co.id = e.course_id
    JOIN categories cat ON cat.id = co.category_id
    WHERE e.user_id = ?
    ORDER BY e.enrolled_at DESC
");
$stmt->execute([$uid]);
$enrolled_courses = $stmt->fetchAll();

$course_ids = array_column($enrolled_courses, 'id');

// Upcoming live sessions across enrolled courses
$upcoming_sessions = [];
if (!empty($course_ids)) {
    $placeholders = implode(',', array_fill(0, count($course_ids), '?'));
    $stmt = $pdo->prepare("
        SELECT ls.*, co.title as course_title
        FROM live_sessions ls
        JOIN courses co ON co.id = ls.course_id
        WHERE ls.course_id IN ($placeholders) AND ls.session_date >= NOW()
        ORDER BY ls.session_date ASC
        LIMIT 10
    ");
    $stmt->execute($course_ids);
    $upcoming_sessions = $stmt->fetchAll();
}

// Recent recordings across enrolled courses
$recent_recordings = [];
if (!empty($course_ids)) {
    $placeholders = implode(',', array_fill(0, count($course_ids), '?'));
    $stmt = $pdo->prepare("
        SELECT r.*, co.title as course_title
        FROM recordings r
        JOIN courses co ON co.id = r.course_id
        WHERE r.course_id IN ($placeholders)
        ORDER BY r.uploaded_at DESC
        LIMIT 6
    ");
    $stmt->execute($course_ids);
    $recent_recordings = $stmt->fetchAll();
}

$page_title = 'Dashboard';
include __DIR__ . '/includes/header.php';
?>

<div class="dash-layout">
    <aside class="dash-sidebar">
        <a href="/index.php" class="logo">
            <span class="logo-mark">TC</span>
            Tech Career
        </a>
        <nav class="dash-nav">
            <a href="/dashboard.php" class="active">&#8862; Overview</a>
            <a href="/courses.php">&#9636; Browse Courses</a>
            <a href="/live-sessions.php">&#9737; Live Sessions</a>
            <a href="/recordings.php">&#9654; Recordings</a>
            <a href="/logout.php">&#8592; Log out</a>
        </nav>
    </aside>

    <main class="dash-main">
        <div class="dash-header">
            <h1>Welcome back, <?= htmlspecialchars(explode(' ', current_user_name())[0]) ?>.</h1>
            <p>Here's what's happening in your courses.</p>
        </div>

        <div class="dash-grid">
            <div class="stat-card">
                <div class="num"><?= count($enrolled_courses) ?></div>
                <div class="label">Enrolled courses</div>
            </div>
            <div class="stat-card">
                <div class="num"><?= count($upcoming_sessions) ?></div>
                <div class="label">Upcoming live sessions</div>
            </div>
            <div class="stat-card">
                <div class="num"><?= count($recent_recordings) ?></div>
                <div class="label">Recordings available</div>
            </div>
        </div>

        <div class="section-header" style="margin-bottom: 24px;">
            <h2 style="font-size: 20px;">Upcoming live sessions</h2>
        </div>
        <?php if (empty($upcoming_sessions)): ?>
            <div class="empty-state">No upcoming live sessions scheduled yet. Check back soon.</div>
        <?php else: ?>
            <?php foreach ($upcoming_sessions as $s): ?>
            <div class="session-row">
                <div class="info">
                    <h4><?= htmlspecialchars($s['title']) ?></h4>
                    <span><?= htmlspecialchars($s['course_title']) ?> &middot; <?= date('D, M j g:i A', strtotime($s['session_date'])) ?></span>
                </div>
                <a href="<?= htmlspecialchars($s['teams_link']) ?>" target="_blank" rel="noopener" class="btn btn-primary">Join on Teams</a>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="section-header" style="margin: 44px 0 24px;">
            <h2 style="font-size: 20px;">Recent recordings</h2>
        </div>
        <?php if (empty($recent_recordings)): ?>
            <div class="empty-state">No recordings available yet for your enrolled courses.</div>
        <?php else: ?>
            <div class="course-grid">
                <?php foreach ($recent_recordings as $r): ?>
                <div class="course-card">
                    <div class="course-thumb">&#9654; <?= htmlspecialchars($r['course_title']) ?></div>
                    <div class="course-card-body">
                        <span class="course-tag"><?= htmlspecialchars($r['course_title']) ?></span>
                        <h3><?= htmlspecialchars($r['title']) ?></h3>
                        <p class="course-card-desc"><?= htmlspecialchars(mb_strimwidth($r['description'] ?? '', 0, 80, '...')) ?></p>
                        <a href="/watch.php?id=<?= $r['id'] ?>" class="btn btn-outline btn-block">Watch now</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($enrolled_courses)): ?>
        <div class="cta-banner" style="margin-top: 40px;">
            <h2>You're not enrolled in any courses yet.</h2>
            <p>Browse our tracks and start learning today.</p>
            <a href="/courses.php" class="btn btn-primary btn-lg">Browse Courses</a>
        </div>
        <?php endif; ?>
    </main>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
