<?php

namespace App\Http\Requests\Directories;

use App\Rules\Files\IsValidFileName;
use Illuminate\Foundation\Http\FormRequest;

class RenameDirectoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // IsValidFileName because name cannot contain slashes
            // and a valid file name is a valid directory name.
            'name' => ['required', 'string:2048', new IsValidFileName()],
        ];
    }
}
