<?php

namespace App\Traits;

trait GetRequestParameterId
{
    public function getParameterId($parameterName)
    {
        $parameter = request()->route($parameterName);

        // Route Model binding will return the object rather than the id when the route uses the binding.
        // To handle that case we check if $parameter is actually an object and if it is get the id,
        // otherwise we check if the route has a $parameterName in it.
        if (gettype($parameter) == "object") {
            return $parameter->id;
        }

        if ($parameter == '') {
            // If we are here then the route didn't have a $parameterName parameter,
            // but there could be a {$parameterName}_id parameter.
            return request()->input("{$parameterName}_id");
        }

        return $parameter;
    }
}
