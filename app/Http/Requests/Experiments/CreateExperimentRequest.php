<?php

namespace App\Http\Requests\Experiments;

use Illuminate\Foundation\Http\FormRequest;

class CreateExperimentRequest extends FormRequest
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
            'name'        => 'required|string|max:80',
            'description' => 'nullable|string|max:8192',
            'summary'     => 'nullable|string|max:100',
            'project_id'  => 'required|integer',
            'file_id'     => 'nullable|integer',
            'sheet_id' => 'nullable|integer',
            'sheet_url'   => 'nullable|url',
            'loading'     => 'boolean',
        ];
    }
}
