<?php

namespace App\Services;

use Filament\Notifications\Notification;

class NotificationService
{
    public static function send(string $title, string $message, string $type = 'info'): void
    {
        $notification = Notification::make()
            ->title(__($title))
            ->body(__($message));

        // Set the notification type dynamically
        match ($type) {
            'success' => $notification->success(),
            'danger' => $notification->danger(),
            'warning' => $notification->warning(),
            default => $notification->info(),
        };

        $notification->send();
    }
}
