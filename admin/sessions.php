<?php
$page_title = 'Live Sessions';
include __DIR__ . '/includes/admin-header.php';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM live_sessions WHERE id = ?");
    $stmt->execute([(int)$_GET['delete']]);
    header('Location: /admin/sessions.php');
    exit;
}

$sessions = $pdo->query("
    SELECT ls.*, co.title as course_title
    FROM live_sessions ls JOIN courses co ON co.id = ls.course_id
    ORDER BY ls.session_date DESC
")->fetchAll();
?>

<div class="dash-header" style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px;">
    <div><h1>Live sessions</h1><p>Schedule and manage Microsoft Teams sessions.</p></div>
    <a href="/admin/session-form.php" class="btn btn-primary">+ Schedule Session</a>
</div>

<table class="data-table">
    <thead><tr><th>Title</th><th>Course</th><th>Date &amp; time</th><th></th></tr></thead>
    <tbody>
        <?php foreach ($sessions as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s['title']) ?></td>
            <td><?= htmlspecialchars($s['course_title']) ?></td>
            <td><?= date('M j, Y g:i A', strtotime($s['session_date'])) ?></td>
            <td style="display:flex; gap:8px;">
                <a href="/admin/session-form.php?id=<?= $s['id'] ?>" class="btn btn-outline" style="padding:6px 14px; font-size:13px;">Edit</a>
                <a href="/admin/sessions.php?delete=<?= $s['id'] ?>" class="btn btn-outline" style="padding:6px 14px; font-size:13px;" onclick="return confirm('Delete this session?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($sessions)): ?>
        <tr><td colspan="4">No sessions scheduled yet.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>
