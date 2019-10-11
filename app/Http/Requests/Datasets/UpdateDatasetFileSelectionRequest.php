<?php

namespace App\Http\Requests\Datasets;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDatasetFileSelectionRequest extends FormRequest
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
            'project_id'          => 'required',
            'include_file'        => 'string',
            'remove_include_file' => 'string',
            'exclude_file'        => 'string',
            'remove_exclude_file' => 'string',
            'include_dir'         => 'string',
            'remove_include_dir'  => 'string',
            'exclude_dir'         => 'string',
            'remove_exclude_dir'  => 'string',
        ];
    }
}
