<?php

namespace App\Actions\Notifications;

use App\Models\Notification;

class DeleteNotificationAction
{
    public function deleteNotificationForUser($user, $model)
    {
        Notification::where('owner_id', $user->id)
                    ->where('notifyable_id', $model->id)
                    ->where('notifyable_type', get_class($model))
                    ->delete();
    }

    public function deleteNotificationForEmail($email, $model)
    {
        Notification::where('email', $email)
                    ->where('notifyable_id', $model->id)
                    ->where('notifyable_type', get_class($model))
                    ->delete();
    }
}