<?php
$page_title = 'Students';
include __DIR__ . '/includes/admin-header.php';

$students = $pdo->query("
    SELECT u.*, COUNT(e.id) as enrolled_count
    FROM users u
    LEFT JOIN enrollments e ON e.user_id = u.id
    WHERE u.role = 'student'
    GROUP BY u.id
    ORDER BY u.created_at DESC
")->fetchAll();
?>

<div class="dash-header"><h1>Students</h1><p>All registered student accounts.</p></div>

<table class="data-table">
    <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Enrolled courses</th><th>Joined</th></tr></thead>
    <tbody>
        <?php foreach ($students as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s['full_name']) ?></td>
            <td><?= htmlspecialchars($s['email']) ?></td>
            <td><?= htmlspecialchars($s['phone'] ?: '—') ?></td>
            <td><?= $s['enrolled_count'] ?></td>
            <td><?= date('M j, Y', strtotime($s['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($students)): ?>
        <tr><td colspan="5">No students registered yet.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>
