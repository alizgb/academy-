<?php
$page_title = 'Course';
include __DIR__ . '/includes/admin-header.php';

$id = (int)($_GET['id'] ?? 0);
$course = ['title'=>'','short_description'=>'','description'=>'','teacher_name'=>'','category_id'=>'','level'=>'beginner','duration_weeks'=>'','price'=>0,'is_published'=>1];
$errors = [];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if ($found) $course = $found;
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY sort_order")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $title = trim($_POST['title']);
    $slug = trim($_POST['slug']) ?: strtolower(preg_replace('/[^a-z0-9]+/', '-', $title));
    $slug = trim($slug, '-');
    $category_id = (int)$_POST['category_id'];
    $short_description = trim($_POST['short_description']);
    $description = trim($_POST['description']);
    $teacher_name = trim($_POST['teacher_name']);
    $level = $_POST['level'];
    $duration_weeks = $_POST['duration_weeks'] !== '' ? (int)$_POST['duration_weeks'] : null;
    $price = (float)$_POST['price'];
    $is_published = isset($_POST['is_published']) ? 1 : 0;

    if ($title === '' || !$category_id) {
        $errors[] = 'Title and category are required.';
    }

    if (empty($errors)) {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE courses SET title=?, slug=?, category_id=?, short_description=?, description=?, teacher_name=?, level=?, duration_weeks=?, price=?, is_published=? WHERE id=?");
            $stmt->execute([$title, $slug, $category_id, $short_description, $description, $teacher_name, $level, $duration_weeks, $price, $is_published, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO courses (title, slug, category_id, short_description, description, teacher_name, level, duration_weeks, price, is_published) VALUES (?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$title, $slug, $category_id, $short_description, $description, $teacher_name, $level, $duration_weeks, $price, $is_published]);
        }
        header('Location: /admin/courses.php');
        exit;
    }
    $course = $_POST;
}
?>

<div class="dash-header"><h1><?= $id ? 'Edit course' : 'Add course' ?></h1></div>

<?php if (!empty($errors)): ?><div class="form-error"><?= htmlspecialchars(implode(' ', $errors)) ?></div><?php endif; ?>

<form method="POST" style="max-width: 640px;">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($course['title']) ?>" required>
    </div>
    <div class="form-group">
        <label>URL slug (optional — auto-generated from title if left blank)</label>
        <input type="text" name="slug" value="<?= htmlspecialchars($course['slug'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label>Category</label>
        <select name="category_id" required>
            <option value="">Select category</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= ($course['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Short description (shown on course cards)</label>
        <input type="text" name="short_description" value="<?= htmlspecialchars($course['short_description']) ?>">
    </div>
    <div class="form-group">
        <label>Full description</label>
        <textarea name="description" rows="6"><?= htmlspecialchars($course['description']) ?></textarea>
    </div>
    <div class="form-group">
        <label>Teacher name</label>
        <input type="text" name="teacher_name" value="<?= htmlspecialchars($course['teacher_name']) ?>">
    </div>
    <div class="form-group">
        <label>Level</label>
        <select name="level">
            <option value="beginner" <?= ($course['level'] ?? '') === 'beginner' ? 'selected' : '' ?>>Beginner</option>
            <option value="intermediate" <?= ($course['level'] ?? '') === 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
            <option value="advanced" <?= ($course['level'] ?? '') === 'advanced' ? 'selected' : '' ?>>Advanced</option>
        </select>
    </div>
    <div class="form-group">
        <label>Duration (weeks, optional)</label>
        <input type="number" name="duration_weeks" value="<?= htmlspecialchars($course['duration_weeks'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label>Price (0 for free)</label>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($course['price'] ?? 0) ?>">
    </div>
    <div class="form-group">
        <label><input type="checkbox" name="is_published" value="1" <?= !empty($course['is_published']) ? 'checked' : '' ?> style="width:auto; margin-right:8px;"> Published (visible to students)</label>
    </div>
    <button type="submit" class="btn btn-primary btn-lg"><?= $id ? 'Save changes' : 'Create course' ?></button>
    <a href="/admin/courses.php" class="btn btn-outline btn-lg">Cancel</a>
</form>

<?php include __DIR__ . '/includes/admin-footer.php'; ?>
