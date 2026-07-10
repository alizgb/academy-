<?php
$page_title = 'Overview';
include __DIR__ . '/includes/admin-header.php';

$counts = [
    'students' => $pdo->query("SELECT COUNT(*) c FROM users WHERE role = 'student'")->fetch()['c'],
    'courses' => $pdo->query("SELECT COUNT(*) c FROM courses")->fetch()['c'],
    'sessions' => $pdo->query("SELECT COUNT(*) c FROM live_sessions WHERE session_date >= NOW()")->fetch()['c'],
    'recordings' => $pdo->query("SELECT COUNT(*) c FROM recordings")->fetch()['c'],
];

$recent_students = $pdo->query("SELECT full_name, email, created_at FROM users WHERE role = 'student' ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>

<div class="dash-header">
    <h1>Admin overview</h1>
    <p>Manage courses, live sessions, recordings, and students.</p>
</div>

<div class="dash-grid">
    <div class="stat-card"><div class="num"><?= $counts['students'] ?></div><div class="label">Students</div></div>
    <div class="stat-card"><div class="num"><?= $counts['courses'] ?></div><div class="label">Courses</div></div>
    <div class="stat-card"><div class="num"><?= $counts['sessions'] ?></div><div class="label">Upcoming sessions</div></div>
    <div class="stat-card"><div class="num"><?= $counts['recordings'] ?></div><div class="label">Recordings</div></div>
</div>

<div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom: 40px;">
    <a href="/admin/course-form.php" class="btn btn-primary">+ Add Course</a>
    <a href="/admin/session-form.php" class="btn btn-outline">+ Schedule Live Session</a>
    <a href="/admin/recording-form.php" class="btn btn-outline">+ Upload Recording</a>
</div>

<div class="section-header" style="margin-bottom: 20px;"><h2 style="font-size:18px;">Recent students</h2></div>
<table class="data-table">
    <thead><tr><th>Name</th><th>Email</th><th>Joined</th></tr></thead>
    <tbody>
        <?php foreach ($recent_students as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s['full_name']) ?></td>
            <td><?= htmlspecialchars($s['email']) ?></td>
            <td><?= date('M j, Y', strtotime($s['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($recent_students)): ?>
        <tr><td colspan="3">No students yet.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>
