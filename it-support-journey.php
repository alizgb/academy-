<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';

// Read-only lookup of the real IT Support course — no writes, no schema changes.
// Falls back gracefully if the course isn't published yet.
$it_course = null;
$lessons = [];
try {
    $stmt = $pdo->prepare("
        SELECT co.id, co.slug
        FROM courses co
        JOIN categories cat ON cat.id = co.category_id
        WHERE cat.slug = 'it-support' AND co.is_published = 1
        ORDER BY co.created_at DESC
        LIMIT 1
    ");
    $stmt->execute();
    $it_course = $stmt->fetch();

    if ($it_course) {
        $stmt = $pdo->prepare("
            SELECT title, uploaded_at AS sort_date FROM recordings WHERE course_id = ?
            UNION ALL
            SELECT title, session_date AS sort_date FROM live_sessions WHERE course_id = ?
            ORDER BY sort_date ASC
        ");
        $stmt->execute([$it_course['id'], $it_course['id']]);
        $lessons = $stmt->fetchAll();
    }
} catch (Exception $e) {
    $it_course = null;
    $lessons = [];
}

$course_link = $it_course
    ? '/course.php?slug=' . urlencode($it_course['slug'])
    : '/courses.php?category=it-support';

$page_title = 'Your IT Support Path';
include __DIR__ . '/includes/header.php';
?>
<link rel="stylesheet" href="/assets/css/journey.css">

<div class="journey">
    <main class="journey-stage" id="journey-stage" data-course-link="<?= htmlspecialchars($course_link) ?>" aria-live="polite">
        <noscript>
            <div class="journey-noscript">
                <h1>Ready to start your IT Support journey?</h1>
                <p>
                    Whether you're aiming for your first IT job, breaking out of a low-paying role,
                    already helping people with tech informally, or coming from a tech-student background,
                    the IT Support track is built to take you from where you are to being ready for real IT work,
                    one practical step at a time: IT foundations, operating systems administration, and
                    professional troubleshooting methodology.
                </p>
                <a href="<?= htmlspecialchars($course_link) ?>" class="journey-cta-primary">Start My IT Support Journey &rarr;</a>
            </div>
        </noscript>
    </main>
</div>

<script id="journey-lessons" type="application/json"><?= json_encode($lessons, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?></script>
<script src="/assets/js/journey.js"></script>

<?php include __DIR__ . '/includes/footer.php'; ?>
