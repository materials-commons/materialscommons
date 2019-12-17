<?php

namespace App\Http\Requests\Datasets;

use Illuminate\Foundation\Http\FormRequest;

class DatasetRequest extends FormRequest
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
            'description' => 'nullable|string|max:2048',
            'project_id'  => 'required',
            'license'     => 'nullable|string|max:256',
            'authors'     => 'nullable|string|max:2048',
            'action'      => 'string|required',
            'experiments' => 'nullable|array',
            'communities' => 'nullable|array',
        ];
    }
}
