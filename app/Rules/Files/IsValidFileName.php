<?php

namespace App\Rules\Files;

use Illuminate\Contracts\Validation\Rule;

class IsValidFileName implements Rule
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
        return $this->isValidFileName($value);
    }

    private function isValidFileName($file) {
        return preg_match('/^([-\.\w ]+)$/', $file) > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid file name: :value';
    }
}
