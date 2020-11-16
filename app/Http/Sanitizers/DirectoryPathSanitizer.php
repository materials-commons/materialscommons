<?php

namespace App\Http\Sanitizers;

use App\Helpers\PathHelpers;
use Elegant\Sanitizer\Contracts\Filter;

class DirectoryPathSanitizer implements Filter
{

    /**
     *  Return the result of applying this filter to the given input.
     *
     * @param  mixed  $value
     *
     * @param  array  $options
     *
     * @return mixed
     */
    public function apply($value, $options = [])
    {
        return PathHelpers::normalizePath($value);
    }
}
