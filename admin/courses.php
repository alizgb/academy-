<?php
$page_title = 'Courses';
include __DIR__ . '/includes/admin-header.php';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->execute([(int)$_GET['delete']]);
    header('Location: /admin/courses.php');
    exit;
}

$courses = $pdo->query("
    SELECT co.*, cat.name as category_name,
        (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = co.id) as enrolled_count
    FROM courses co JOIN categories cat ON cat.id = co.category_id
    ORDER BY co.created_at DESC
")->fetchAll();
?>

<div class="dash-header" style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px;">
    <div>
        <h1>Courses</h1>
        <p>Manage all published and draft courses.</p>
    </div>
    <a href="/admin/course-form.php" class="btn btn-primary">+ Add Course</a>
</div>

<table class="data-table">
    <thead><tr><th>Title</th><th>Category</th><th>Teacher</th><th>Status</th><th>Enrolled</th><th></th></tr></thead>
    <tbody>
        <?php foreach ($courses as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['title']) ?></td>
            <td><?= htmlspecialchars($c['category_name']) ?></td>
            <td><?= htmlspecialchars($c['teacher_name'] ?: '—') ?></td>
            <td><span class="badge <?= $c['is_published'] ? 'badge-success' : 'badge-muted' ?>"><?= $c['is_published'] ? 'Published' : 'Draft' ?></span></td>
            <td><?= $c['enrolled_count'] ?></td>
            <td style="display:flex; gap:8px;">
                <a href="/admin/course-form.php?id=<?= $c['id'] ?>" class="btn btn-outline" style="padding:6px 14px; font-size:13px;">Edit</a>
                <a href="/admin/courses.php?delete=<?= $c['id'] ?>" class="btn btn-outline" style="padding:6px 14px; font-size:13px;" onclick="return confirm('Delete this course and all its sessions/recordings/enrollments?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($courses)): ?>
        <tr><td colspan="6">No courses yet. <a href="/admin/course-form.php" style="color: var(--accent-secondary);">Add your first course</a>.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>
