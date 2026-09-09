<?php
// Shared presentation helpers used by index.php and course.php.

// Simple inline checkmark, reused across list items.
function tca_check() {
    return '<svg class="check" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M16.5 5.5L8 14 3.5 9.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
}

// A real asset when we have one, otherwise a subtle (not empty-feeling) placeholder.
function tca_asset_slot($label, $src = null, $alt = '') {
    if ($src) {
        return '<div class="asset-slot"><img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($alt) . '"></div>';
    }
    $icon = '<svg class="slot-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.6"/><circle cx="9" cy="10.5" r="1.6" stroke="currentColor" stroke-width="1.6"/><path d="M21 15l-5.5-4.5a1.5 1.5 0 00-2 .1L7 16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>';
    return '<div class="asset-slot">' . $icon . '<span class="label">' . htmlspecialchars($label) . '</span></div>';
}

// Real syllabus data only — recordings + live sessions actually tied to this course.
function tca_course_items($pdo, $course_id) {
    $items = [];
    $stmt = $pdo->prepare("SELECT title FROM recordings WHERE course_id = ? ORDER BY uploaded_at ASC");
    $stmt->execute([$course_id]);
    foreach ($stmt->fetchAll() as $r) {
        $items[] = ['label' => $r['title'], 'type' => 'Recorded'];
    }
    $stmt = $pdo->prepare("SELECT title FROM live_sessions WHERE course_id = ? ORDER BY session_date ASC");
    $stmt->execute([$course_id]);
    foreach ($stmt->fetchAll() as $s) {
        $items[] = ['label' => $s['title'], 'type' => 'Live'];
    }
    return $items;
}

// Academy-wide testimonials and FAQs (schema has no per-course scoping for either).
function tca_get_testimonials($pdo) {
    return $pdo->query("SELECT * FROM testimonials ORDER BY sort_order")->fetchAll();
}

function tca_get_faqs($pdo) {
    $faqs = $pdo->query("SELECT * FROM faqs ORDER BY sort_order")->fetchAll();
    // Always answer this specific purchase objection, regardless of what's seeded in the faqs table.
    $faqs[] = [
        'question' => 'What happens after I enroll?',
        'answer' => "You'll get access to your student dashboard right away — your upcoming live sessions appear there with a direct Teams join link, along with any recordings already available for the course.",
    ];
    return $faqs;
}
