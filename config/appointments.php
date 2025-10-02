<?php
return [
    // Set a default daily appointment limit (override in .env with APPOINTMENT_DAILY_LIMIT)
    'daily_limit' => env('APPOINTMENT_DAILY_LIMIT', 10),
];
