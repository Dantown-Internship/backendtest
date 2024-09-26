<?php

namespace App\Services;

use App\Models\EventLogs;

class EventLog {

    public static function log( string $message, ?int $userId = null )
    {
        EventLogs::create(['event' => $message, 'user_id' => $userId ]);
    }
}