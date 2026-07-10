<?php
$page_title = 'Upload Recording';
include __DIR__ . '/includes/admin-header.php';

$courses = $pdo->query("SELECT id, title FROM courses ORDER BY title")->fetchAll();
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $title = trim($_POST['title']);
    $course_id = (int)$_POST['course_id'];
    $description = trim($_POST['description']);

    if ($title === '' || !$course_id) {
        $errors[] = 'Title and course are required.';
    }

    if (empty($_FILES['video']['name'])) {
        $errors[] = 'Please choose a video file to upload.';
    } elseif ($_FILES['video']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Upload failed (error code ' . $_FILES['video']['error'] . '). The file may exceed your server\'s upload size limit — see note below.';
    } else {
        $allowed = ['mp4', 'webm', 'mov', 'm4v'];
        $ext = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = 'Allowed video formats: ' . implode(', ', $allowed);
        }
    }

    if (empty($errors)) {
        $recordingsDir = __DIR__ . '/../uploads/recordings';
        if (!is_dir($recordingsDir)) mkdir($recordingsDir, 0755, true);

        $filename = uniqid('rec_', true) . '.' . $ext;
        $destination = $recordingsDir . '/' . $filename;

        if (move_uploaded_file($_FILES['video']['tmp_name'], $destination)) {
            $stmt = $pdo->prepare("INSERT INTO recordings (course_id, title, description, file_path) VALUES (?,?,?,?)");
            $stmt->execute([$course_id, $title, $description, 'recordings/' . $filename]);
            $success = true;
        } else {
            $errors[] = 'Could not save the uploaded file. Check folder permissions on /uploads/recordings.';
        }
    }
}
?>

<div class="dash-header"><h1>Upload recording</h1></div>

<?php if ($success): ?>
    <div class="form-success">Recording uploaded successfully. <a href="/admin/recordings.php" style="color:inherit; text-decoration:underline;">View all recordings</a>.</div>
<?php endif; ?>
<?php if (!empty($errors)): ?><div class="form-error"><?= htmlspecialchars(implode(' ', $errors)) ?></div><?php endif; ?>

<form method="POST" enctype="multipart/form-data" style="max-width: 640px;">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <div class="form-group">
        <label>Session title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required placeholder="e.g. Week 2: Ticketing Systems">
    </div>
    <div class="form-group">
        <label>Course</label>
        <select name="course_id" required>
            <option value="">Select course</option>
            <?php foreach ($courses as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($_POST['course_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Description (optional)</label>
        <textarea name="description" rows="3"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
        <label>Video file (MP4, WebM, MOV — up to your server's upload limit)</label>
        <input type="file" name="video" accept=".mp4,.webm,.mov,.m4v" required>
    </div>
    <button type="submit" class="btn btn-primary btn-lg">Upload recording</button>
    <a href="/admin/recordings.php" class="btn btn-outline btn-lg">Cancel</a>
</form>

<p style="color: var(--text-dim); font-size: 13px; margin-top: 24px; max-width: 640px;">
    Note: Hostinger's default PHP upload limit is often only 2–8MB, which is too small for video.
    See the included <code>uploads-config.md</code> guide for how to raise this limit on Business hosting.
</p>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>
