<?php

namespace App\Traits\Notifications;

use App\Models\Dataset;
use App\Models\Notification;
use App\Models\User;

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

    private function datasetAlreadySetForNotificationForUser(User $user, Dataset $dataset): bool
    {
        return $this->userAlreadySetForNotification($user->id, Dataset::class, $dataset->id);
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