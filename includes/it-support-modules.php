<?php
// Single source of truth for the confirmed IT Support curriculum overview.
// Consumed by course.php (server-rendered) and it-support-journey.php
// (embedded as JSON for journey.js) so the wording can't drift between them.
return [
    ['title' => 'IT Foundations & Computer Architecture', 'desc' => 'How computers, hardware, and operating systems actually work under the hood.'],
    ['title' => 'Operating Systems Administration', 'desc' => 'Configuring, maintaining, and supporting Windows environments like a real IT professional.'],
    ['title' => 'Professional Troubleshooting Methodology', 'desc' => 'A repeatable method for diagnosing real problems — not guesswork.'],
];
