<?php

namespace App\Http\Requests\Files;

use Illuminate\Foundation\Http\FormRequest;

class CopyMoveFilesRequest extends FormRequest
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
            'directory' => 'required|integer',
            'ids'       => 'required|array',
        ];
    }
}
