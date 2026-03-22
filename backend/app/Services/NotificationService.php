<?php

namespace App\Services;

use App\Models\PlatformNotification;
use App\Models\User;

class NotificationService
{
    public function notify(User $user, string $type, string $title, ?string $body = null, ?array $data = null): PlatformNotification
    {
        return PlatformNotification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'data' => $data,
        ]);
    }

    public function markRead(PlatformNotification $notification): void
    {
        if ($notification->read_at === null) {
            $notification->update(['read_at' => now()]);
        }
    }
}
