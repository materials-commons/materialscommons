<?php

namespace App\Actions\Notifications;

use App\Models\Notification;

class CreateNotificationAction
{
    public function addNotificationForUser($user, $model)
    {
        $notification = Notification::create([
            'owner_id'        => $user->id,
            'notifyable_id'   => $model->id,
            'notifyable_type' => get_class($model),
        ]);

        $notification->save();

        return $model;
    }

    public function addNotificationForEmail($email, $name, $model)
    {
        $notification = Notification::create([
            'email'           => $email,
            'name'            => $name,
            'notifyable_id'   => $model->id,
            'notifyable_type' => get_class($model),
        ]);

        $model->notifications()->save($notification);

        return $model;
    }
}