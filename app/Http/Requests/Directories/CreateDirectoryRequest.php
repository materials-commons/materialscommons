<?php

namespace App\Http\Requests\Directories;

use App\Rules\Directories\IsValidDirectoryPath;
use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class CreateDirectoryRequest extends FormRequest
{
    use SanitizesInput;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     *  Validation rules to be applied to the input.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'         => ['required', 'string:2048', new IsValidDirectoryPath()],
            'description'  => 'string:2048',
            'project_id'   => 'required:integer',
            'directory_id' => 'required|integer',
        ];
    }

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'name' => 'normalizePath'
        ];
    }
}
