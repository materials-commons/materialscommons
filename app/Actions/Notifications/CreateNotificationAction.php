<?php

namespace App\Actions\Notifications;

use App\Models\Notification;
use App\Traits\Notifications\NotificationChecker;

class CreateNotificationAction
{
    use NotificationChecker;
    
    public function addNotificationForUser($user, $model)
    {
        $modelClass = get_class($model);

        if ($this->userAlreadySetForNotification($user->id, $modelClass, $model->id)) {
            return $model;
        }

        $notification = Notification::create([
            'owner_id'        => $user->id,
            'notifyable_id'   => $model->id,
            'notifyable_type' => $modelClass,
        ]);

        $notification->save();

        return $model;
    }

    public function addNotificationForEmail($email, $name, $model)
    {
        $modelClass = get_class($model);

        if ($this->emailAlreadySetForNotification($email, $modelClass, $model->id)) {
            return $model;
        }

        $notification = Notification::create([
            'email'           => $email,
            'name'            => $name,
            'notifyable_id'   => $model->id,
            'notifyable_type' => $modelClass,
        ]);

        $model->notifications()->save($notification);

        return $model;
    }
}