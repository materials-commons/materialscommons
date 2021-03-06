<?php

namespace App\Http\Requests\Globus;

use Illuminate\Foundation\Http\FormRequest;

class CreateGlobusProjectUploadDownloadRequest extends FormRequest
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
            'project_id'  => 'nullable',
            'name'        => 'required|string|max:80',
            'description' => 'nullable|string|max:2048',
        ];
    }
}
