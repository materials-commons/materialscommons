<?php

namespace App\Actions\Notifications;

use App\Models\Notification;
use App\Traits\Notifications\NotificationChecker;

class DeleteNotificationAction
{
    use NotificationChecker;

    public function deleteNotificationForUser($user, $model)
    {
        $modelClass = get_class($model);

        if (! $this->userAlreadySetForNotification($user->id, $modelClass, $model->id)) {
            // No notifications for user/object so nothing to delete
            return;
        }

        Notification::where('owner_id', $user->id)
                    ->where('notifyable_id', $model->id)
                    ->where('notifyable_type', $modelClass)
                    ->delete();
    }

    public function deleteNotificationForEmail($email, $model)
    {
        $modelClass = get_class($model);

        if (! $this->emailAlreadySetForNotification($email, $modelClass, $model->id)) {
            // No notifications for email/object so nothing to delete
            return;
        }

        Notification::where('email', $email)
                    ->where('notifyable_id', $model->id)
                    ->where('notifyable_type', $modelClass)
                    ->delete();
    }
}