<?php

namespace App\Casts\DataHQ;

use App\DTO\DataHQ\Chart;
use App\DTO\DataHQ\Explorer;
use App\DTO\DataHQ\Subview;
use App\DTO\DataHQ\View;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ExplorerCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_null($value)) {
            return null;
        }
        $data = json_decode($value, true);
        $explorerType = $data['explorerType'];
        $currentView = $data['currentView'];
        $views = [];
        foreach ($data['views'] as $key => $view) {
            $subviews = [];
            foreach ($view['subviews'] as $subviewKey => $subview) {
                $chart = null;
                $table = null;
                if (!is_null($subview['chart'])) {
                    $chart = Chart::fromArray($subview['chart']);
                }

                if (!is_null($subview['table'])) {
                    $table = $subview['table']; // implement this later
                }

                $subview = new Subview($subview['name'], $subview['description'], $chart, $table);
                $subviews[] = $subview;
            }
            $view = new View($view['name'], $view['description'], $view['mql'], $view['currentSubview'],
                collect($subviews));
            $views[] = $view;
        }

        return new Explorer($explorerType, $currentView, collect($views));
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!($value instanceof Explorer)) {
            return $value;
        }
        return json_encode($value->jsonSerialize());
    }
}
