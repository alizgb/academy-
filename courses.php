<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';

$category_slug = $_GET['category'] ?? null;

$sql = "
    SELECT co.*, cat.name as category_name, cat.slug as category_slug
    FROM courses co
    JOIN categories cat ON cat.id = co.category_id
    WHERE co.is_published = 1
";
$params = [];
if ($category_slug) {
    $sql .= " AND cat.slug = ?";
    $params[] = $category_slug;
}
$sql .= " ORDER BY co.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$courses = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories ORDER BY sort_order")->fetchAll();

$page_title = 'Courses';
include __DIR__ . '/includes/header.php';
?>

<section class="section" style="padding-top: 56px;">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">All courses</span>
            <h2>Find your track.</h2>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:40px;">
            <a href="/courses.php" class="btn <?= !$category_slug ? 'btn-primary' : 'btn-outline' ?>">All</a>
            <?php foreach ($categories as $cat): ?>
                <a href="/courses.php?category=<?= urlencode($cat['slug']) ?>" class="btn <?= $category_slug === $cat['slug'] ? 'btn-primary' : 'btn-outline' ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($courses)): ?>
            <div class="empty-state">No courses published in this category yet. Check back soon.</div>
        <?php else: ?>
        <div class="course-grid">
            <?php foreach ($courses as $c): ?>
            <div class="course-card">
                <div class="course-thumb">&#9636; <?= htmlspecialchars($c['category_name']) ?></div>
                <div class="course-card-body">
                    <span class="course-tag"><?= htmlspecialchars(ucfirst($c['level'])) ?></span>
                    <h3><?= htmlspecialchars($c['title']) ?></h3>
                    <p class="course-card-desc"><?= htmlspecialchars($c['short_description']) ?></p>
                    <div class="course-card-footer">
                        <span><?= $c['duration_weeks'] ? $c['duration_weeks'] . ' weeks' : 'Self-paced' ?></span>
                        <?php if ($c['teacher_name']): ?><span><?= htmlspecialchars($c['teacher_name']) ?></span><?php endif; ?>
                    </div>
                </div>
                <div style="padding: 0 22px 22px;">
                    <a href="/course.php?slug=<?= urlencode($c['slug']) ?>" class="btn btn-primary btn-block">View course</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
