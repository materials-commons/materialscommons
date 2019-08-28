<?php

namespace App\Http\Requests\Directories;

use Illuminate\Foundation\Http\FormRequest;

class MoveDirectoryRequest extends FormRequest
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
            'to_directory_id' => 'required|integer',
        ];
    }
}
