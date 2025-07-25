<?php

namespace App\Http\Requests\Files;

use App\Rules\Files\IsValidFileName;
use Illuminate\Foundation\Http\FormRequest;

class RenameFileRequest extends FormRequest
{
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:80', new IsValidFileName()],
            'url'  => 'nullable|url|max:2048',
            'project_id' => 'required|integer',
        ];
    }
}
