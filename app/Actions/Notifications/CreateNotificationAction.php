<?php

namespace App\Actions\Notifications;

use App\Models\Notification;

class CreateNotificationAction
{
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

    public function userAlreadySetForNotification($userId, $modelClass, $modelId): bool
    {
        $count = Notification::where('notifyable_type', $modelClass)
                             ->where('notifyable_id', $modelId)
                             ->where('owner_id', $userId)
                             ->count();
        return $count > 0;
    }

    public function emailAlreadySetForNotification($email, $modelClass, $modelId): bool
    {
        $count = Notification::where('notifyable_type', $modelClass)
                             ->where('notifyable_id', $modelId)
                             ->where('email', $email)
                             ->count();
        return $count > 0;
    }
}