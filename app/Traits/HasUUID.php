<?php

namespace App\Traits;

use Ramsey\Uuid\Uuid;

trait HasUUID
{
    public static function bootHasUUID()
    {
        static::creating(function ($model) {
            $uuidFieldName = $model->getUUIDFieldName();
            if (empty($model->$uuidFieldName)) {
                $model->$uuidFieldName = static::generateUUID();
            }
        });
    }

    public static function generateUUID()
    {
        return Uuid::uuid4()->toString();
    }

    public static function findByUuid($uuid)
    {
        return static::byUUID($uuid)->first();
    }

    public function scopeByUUID($query, $uuid)
    {
        return $query->where($this->getUUIDFieldName(), $uuid);
    }

    public function getUUIDFieldName()
    {
        if (!empty($this->uuidFieldName)) {
            return $this->uuidFieldName;
        }
        return 'uuid';
    }
}
