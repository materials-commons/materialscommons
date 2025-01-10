<?php

namespace App\Casts;

use App\DTO\DataHQOld\OverviewState;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use function is_null;

class OverviewStateCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?OverviewState
    {
        if (is_null($value)) {
            return null;
        }

        $data = json_decode($value, true);
        return new OverviewState($data['projectContext'], collect($data['experimentContext']),
            collect($data['experimentContext']));
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!($value instanceof OverviewState)) {
            return $value;
        }

        return json_encode($value->jsonSerialize());
    }
}
