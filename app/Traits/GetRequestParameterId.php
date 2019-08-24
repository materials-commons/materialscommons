<?php

namespace App\Traits;

trait GetRequestParameterId
{
    public function getParameterId($parameterName)
    {
        $parameterId = request()->route($parameterName);

        // Route Model binding will return the object rather than the id when the route uses the binding.
        // To handle that case we check if $objectId is actually an object and if it is get the id,
        // otherwise we check if the route has a $objectName in it.
        if (gettype($parameterId) == "object") {
            $parameterId = $parameterId->id;
        } elseif ($parameterId == '') {
            // If we are here then the route didn't have a $parameterName parameter, but there could be a {$parameterName}_id parameter.
            $parameterId = request()->input("{$parameterName}_id");
        }
        return $parameterId;
    }
}
