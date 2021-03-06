<?php

namespace App\Http\Requests\Etl;

use Illuminate\Foundation\Http\FormRequest;

class GetFileByPathRequest extends FormRequest
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
            'project_id' => 'required',
            'path'       => 'required|string|max:2048',
        ];
    }
}
