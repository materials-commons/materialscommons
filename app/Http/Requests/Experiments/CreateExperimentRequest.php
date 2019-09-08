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
            'description' => 'string|max:2048',
            'project_id'  => 'required|integer',
        ];
    }
}
