<?php

namespace App\Http\Requests\Workflows;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkflowRequest extends FormRequest
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
            'workflow'    => 'required|string|max:4096',
            'description' => 'nullable|string|max:8192',
            'summary'     => 'nullable|string|max:100',
            'name'        => 'required|string|max:80',
        ];
    }
}
