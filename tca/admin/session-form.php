<?php
$page_title = 'Live Session';
include __DIR__ . '/includes/admin-header.php';

$id = (int)($_GET['id'] ?? 0);
$session = ['title'=>'','description'=>'','teams_link'=>'','course_id'=>'','session_date'=>'','duration_minutes'=>60];
$errors = [];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM live_sessions WHERE id = ?");
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if ($found) {
        $session = $found;
        $session['session_date'] = date('Y-m-d\TH:i', strtotime($found['session_date']));
    }
}

$courses = $pdo->query("SELECT id, title FROM courses ORDER BY title")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $title = trim($_POST['title']);
    $course_id = (int)$_POST['course_id'];
    $description = trim($_POST['description']);
    $teams_link = trim($_POST['teams_link']);
    $session_date = $_POST['session_date'];
    $duration_minutes = (int)$_POST['duration_minutes'];

    if ($title === '' || !$course_id || $teams_link === '' || $session_date === '') {
        $errors[] = 'Title, course, Teams link, and date/time are required.';
    } elseif (!filter_var($teams_link, FILTER_VALIDATE_URL)) {
        $errors[] = 'Teams link must be a valid URL.';
    }

    if (empty($errors)) {
        $date_formatted = date('Y-m-d H:i:s', strtotime($session_date));
        if ($id) {
            $stmt = $pdo->prepare("UPDATE live_sessions SET title=?, course_id=?, description=?, teams_link=?, session_date=?, duration_minutes=? WHERE id=?");
            $stmt->execute([$title, $course_id, $description, $teams_link, $date_formatted, $duration_minutes, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO live_sessions (title, course_id, description, teams_link, session_date, duration_minutes) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$title, $course_id, $description, $teams_link, $date_formatted, $duration_minutes]);
        }
        header('Location: /admin/sessions.php');
        exit;
    }
    $session = $_POST;
}
?>

<div class="dash-header"><h1><?= $id ? 'Edit session' : 'Schedule live session' ?></h1></div>

<?php if (!empty($errors)): ?><div class="form-error"><?= htmlspecialchars(implode(' ', $errors)) ?></div><?php endif; ?>

<form method="POST" style="max-width: 640px;">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <div class="form-group">
        <label>Session title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($session['title']) ?>" required placeholder="e.g. Week 3: Subnetting Basics">
    </div>
    <div class="form-group">
        <label>Course</label>
        <select name="course_id" required>
            <option value="">Select course</option>
            <?php foreach ($courses as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($session['course_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Microsoft Teams meeting link</label>
        <input type="url" name="teams_link" value="<?= htmlspecialchars($session['teams_link']) ?>" required placeholder="https://teams.microsoft.com/l/meetup-join/...">
    </div>
    <div class="form-group">
        <label>Date &amp; time</label>
        <input type="datetime-local" name="session_date" value="<?= htmlspecialchars($session['session_date']) ?>" required>
    </div>
    <div class="form-group">
        <label>Duration (minutes)</label>
        <input type="number" name="duration_minutes" value="<?= htmlspecialchars($session['duration_minutes']) ?>">
    </div>
    <div class="form-group">
        <label>Notes (optional)</label>
        <textarea name="description" rows="3"><?= htmlspecialchars($session['description']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary btn-lg"><?= $id ? 'Save changes' : 'Schedule session' ?></button>
    <a href="/admin/sessions.php" class="btn btn-outline btn-lg">Cancel</a>
</form>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>
