<?php

namespace App\Http\Requests\Files;

use Illuminate\Foundation\Http\FormRequest;

class MoveFileRequest extends FormRequest
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
            'directory_id' => 'required|integer',
            'project_id'   => 'required|integer',
        ];
    }
}
