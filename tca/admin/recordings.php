<?php
$page_title = 'Recordings';
include __DIR__ . '/includes/admin-header.php';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("SELECT file_path FROM recordings WHERE id = ?");
    $stmt->execute([(int)$_GET['delete']]);
    $rec = $stmt->fetch();
    if ($rec) {
        $filePath = __DIR__ . '/../uploads/' . ltrim($rec['file_path'], '/');
        if (is_file($filePath)) @unlink($filePath);
    }
    $stmt = $pdo->prepare("DELETE FROM recordings WHERE id = ?");
    $stmt->execute([(int)$_GET['delete']]);
    header('Location: /admin/recordings.php');
    exit;
}

$recordings = $pdo->query("
    SELECT r.*, co.title as course_title
    FROM recordings r JOIN courses co ON co.id = r.course_id
    ORDER BY r.uploaded_at DESC
")->fetchAll();
?>

<div class="dash-header" style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px;">
    <div><h1>Recordings</h1><p>Upload and manage recorded session videos.</p></div>
    <a href="/admin/recording-form.php" class="btn btn-primary">+ Upload Recording</a>
</div>

<table class="data-table">
    <thead><tr><th>Title</th><th>Course</th><th>Uploaded</th><th></th></tr></thead>
    <tbody>
        <?php foreach ($recordings as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['title']) ?></td>
            <td><?= htmlspecialchars($r['course_title']) ?></td>
            <td><?= date('M j, Y', strtotime($r['uploaded_at'])) ?></td>
            <td style="display:flex; gap:8px;">
                <a href="/watch.php?id=<?= $r['id'] ?>" class="btn btn-outline" style="padding:6px 14px; font-size:13px;">Preview</a>
                <a href="/admin/recordings.php?delete=<?= $r['id'] ?>" class="btn btn-outline" style="padding:6px 14px; font-size:13px;" onclick="return confirm('Delete this recording and its video file?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($recordings)): ?>
        <tr><td colspan="4">No recordings uploaded yet.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>
