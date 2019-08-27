<?php

namespace App\Rules\Directories;

use Illuminate\Contracts\Validation\Rule;

class IsValidDirectoryPath implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->isValidDirectoryPath($value);
    }

    private function isValidDirectoryPath($directoryPath)
    {
        if ($directoryPath == '/') {
            return false;
        }

        return preg_match('#^([-\.\w/ ]+)$#', $directoryPath) > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid directory path: :value';
    }
}
