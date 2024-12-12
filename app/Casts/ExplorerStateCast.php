<?php

namespace App\Casts;

use App\DTO\DataHQ\ContextState;
use App\DTO\DataHQ\ExplorerState;
use App\DTO\DataHQ\SubviewState2;
use App\DTO\DataHQ\ViewState;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ExplorerStateCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?ExplorerState
    {
        if (is_null($value)) {
            return null;
        }

        $data = json_decode($value, true);
        $currentTab = $data['currentView'];
        $currentSubview = $data['currentSubview'];
        $contextsArray = [];
        foreach ($data['contexts'] as $contextKey => $context) {
            $tabsArray = [];
            foreach ($context['tabs'] as $tabKey => $tab) {
                $subviewsArray = [];
                foreach ($tab['subviews'] as $subviewKey => $subview) {
                    $subviewsArray[$subviewKey] = new SubviewState2(
                        $subview['type'],
                        $subview['mql'],
                        $subview['xAttrType'],
                        $subview['xAttrName'],
                        $subview['yAttrType'],
                        $subview['yAttrName'],
                    );
                }
                $tabsArray[$tabKey] = new ViewState(collect($subviewsArray));
            }
            $contextsArray[$contextKey] = new ContextState(collect($tabsArray));
        }
        return new ExplorerState($currentSubview, $currentTab, collect($contextsArray));
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!($value instanceof ExplorerState)) {
            return $value;
        }

        return json_encode($value->jsonSerialize());
    }
}
