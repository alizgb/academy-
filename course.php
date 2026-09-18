<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("
    SELECT co.*, cat.name as category_name, cat.slug as category_slug
    FROM courses co JOIN categories cat ON cat.id = co.category_id
    WHERE co.slug = ? AND co.is_published = 1
");
$stmt->execute([$slug]);
$course = $stmt->fetch();

if (!$course) {
    header('Location: /courses.php');
    exit;
}

$is_it_support = ($course['category_slug'] ?? '') === 'it-support';

// Real, live-queried lesson list — never hardcoded. Premium IT Support
// template only; every other course keeps the original template below.
$premium_lessons = [];
if ($is_it_support) {
    try {
        $stmt = $pdo->prepare("
            SELECT title, uploaded_at AS sort_date FROM recordings WHERE course_id = ?
            UNION ALL
            SELECT title, session_date AS sort_date FROM live_sessions WHERE course_id = ?
            ORDER BY sort_date ASC
        ");
        $stmt->execute([$course['id'], $course['id']]);
        $premium_lessons = $stmt->fetchAll();
    } catch (Exception $e) {
        $premium_lessons = [];
    }
}

// Confirmed high-level curriculum overview for the IT Support course only
// (mirrors assets/js/journey.js — kept as static copy, not DB data).
$premium_modules = [
    ['title' => 'IT Foundations & Computer Architecture', 'desc' => 'How computers, hardware, and operating systems actually work under the hood.'],
    ['title' => 'Operating Systems Administration', 'desc' => 'Configuring, maintaining, and supporting Windows environments like a real IT professional.'],
    ['title' => 'Professional Troubleshooting Methodology', 'desc' => 'A repeatable method for diagnosing real problems, not guesswork.'],
];

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
if ($is_it_support): ?>
<link rel="stylesheet" href="/assets/css/journey.css">

<div class="course-premium">
    <div class="course-premium-hero">
        <div class="course-premium-eyebrow"><?= htmlspecialchars($course['category_name']) ?></div>
        <h1><?= htmlspecialchars($course['title']) ?></h1>
        <p><?= htmlspecialchars($course['short_description']) ?></p>

        <div class="course-premium-meta">
            <div><strong>Level</strong><?= htmlspecialchars(ucfirst($course['level'])) ?></div>
            <div><strong>Duration</strong><?= $course['duration_weeks'] ? $course['duration_weeks'] . ' weeks' : 'Self-paced' ?></div>
            <?php if ($course['teacher_name']): ?><div><strong>Instructor</strong><?= htmlspecialchars($course['teacher_name']) ?></div><?php endif; ?>
        </div>
    </div>

    <div class="course-premium-section">
        <div class="course-premium-section-title">This isn't just another course</div>
        <div class="journey-path">
            <?php foreach ($premium_modules as $i => $m): ?>
            <div class="journey-path-step">
                <div class="journey-path-num"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></div>
                <div>
                    <div class="journey-path-title"><?= htmlspecialchars($m['title']) ?></div>
                    <div class="journey-path-desc"><?= htmlspecialchars($m['desc']) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="journey-path-step is-final">
                <div class="journey-path-num">&#10003;</div>
                <div><div class="journey-path-title">Ready for real IT work</div></div>
            </div>
        </div>
    </div>

    <?php if (!empty($premium_lessons)): ?>
    <div class="course-premium-section">
        <div class="journey-lessons">
            <div class="journey-lessons-header">Inside the course</div>
            <?php foreach ($premium_lessons as $i => $l): ?>
            <div class="journey-lesson-row">
                <span class="journey-lesson-index"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>
                <span class="journey-lesson-title"><?= htmlspecialchars($l['title']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="course-premium-section">
        <div class="course-premium-section-title">Full description</div>
        <div class="course-premium-desc"><?= htmlspecialchars($course['description'] ?: 'Full curriculum details coming soon.') ?></div>
    </div>

    <div class="course-premium-cta">
        <?php if ($is_enrolled): ?>
            <a href="/dashboard.php" class="journey-cta-primary">Go to your dashboard</a>
        <?php else: ?>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <button type="submit" name="enroll" value="1" class="journey-cta-primary">
                    <?= is_logged_in() ? 'Start My IT Support Journey &rarr;' : 'Sign up to enroll' ?>
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php else: ?>

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

<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
