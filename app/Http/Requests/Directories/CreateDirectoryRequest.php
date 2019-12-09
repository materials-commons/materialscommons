<?php

namespace App\Http\Requests\Directories;

use App\Rules\Files\IsValidFileName;
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
            // IsValidFileName because name cannot contain slashes, we only create one level
            // and not multiple levels. So a valid directory name is also a valid file name.
            'name'         => ['required', 'string', 'max:80', new IsValidFileName()],
            'description'  => 'nullable|string|max:2048',
            'project_id'   => 'required|integer',
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
            //            'name' => 'normalizePath'
        ];
    }
}
