<?php

namespace App\Traits\Notifications;

use App\Models\Notification;

trait NotificationChecker
{
    private function userAlreadySetForNotification($userId, $modelClass, $modelId): bool
    {
        $count = Notification::where('notifyable_type', $modelClass)
                             ->where('notifyable_id', $modelId)
                             ->where('owner_id', $userId)
                             ->count();
        return $count > 0;
    }

    private function emailAlreadySetForNotification($email, $modelClass, $modelId): bool
    {
        $count = Notification::where('notifyable_type', $modelClass)
                             ->where('notifyable_id', $modelId)
                             ->where('email', $email)
                             ->count();
        return $count > 0;
    }
}