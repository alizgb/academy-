<?php
/**
 * Secure video streaming endpoint.
 * Recordings are NOT publicly accessible files — they are only served
 * through this script after verifying the logged-in user is enrolled
 * in the course (or is an admin). Supports HTTP Range requests so the
 * video player's seek bar works properly.
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM recordings WHERE id = ?");
$stmt->execute([$id]);
$recording = $stmt->fetch();

if (!$recording) {
    http_response_code(404);
    exit('Not found.');
}

if (!is_admin()) {
    $stmt = $pdo->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->execute([current_user_id(), $recording['course_id']]);
    if (!$stmt->fetch()) {
        http_response_code(403);
        exit('You are not enrolled in this course.');
    }
}

// file_path stored relative to /uploads, e.g. "recordings/abc123.mp4"
$filePath = realpath(__DIR__ . '/uploads/' . ltrim($recording['file_path'], '/'));
$uploadsRoot = realpath(__DIR__ . '/uploads');

// Prevent path traversal
if (!$filePath || strpos($filePath, $uploadsRoot) !== 0 || !is_file($filePath)) {
    http_response_code(404);
    exit('File not found.');
}

$fileSize = filesize($filePath);
$mime = 'video/mp4';

header('Content-Type: ' . $mime);
header('Accept-Ranges: bytes');
header('Cache-Control: private, max-age=0, no-cache');

$start = 0;
$end = $fileSize - 1;

if (isset($_SERVER['HTTP_RANGE'])) {
    if (preg_match('/bytes=(\d*)-(\d*)/', $_SERVER['HTTP_RANGE'], $matches)) {
        $start = $matches[1] === '' ? 0 : (int)$matches[1];
        $end = $matches[2] === '' ? $fileSize - 1 : (int)$matches[2];
        $end = min($end, $fileSize - 1);
        http_response_code(206);
        header("Content-Range: bytes $start-$end/$fileSize");
    }
}

header('Content-Length: ' . ($end - $start + 1));

$fp = fopen($filePath, 'rb');
fseek($fp, $start);
$bufferSize = 8192;
$bytesRemaining = $end - $start + 1;

while ($bytesRemaining > 0 && !feof($fp)) {
    $readSize = min($bufferSize, $bytesRemaining);
    echo fread($fp, $readSize);
    $bytesRemaining -= $readSize;
    flush();
}
fclose($fp);
