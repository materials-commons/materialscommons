<?php

namespace App\Http\Requests\Experiments;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExperimentRequest extends FormRequest
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
            'name'        => 'nullable|string|max:80',
            'description' => 'nullable|string|max:8192',
            'summary'     => 'nullable|string|max:100',
        ];
    }
}
