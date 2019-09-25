<?php

namespace App\Http\Requests\Etl;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddSampleAndFilesToProcessRequest extends FormRequest
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
            'project_id'                => 'required',
            'experiment_id'             => 'required',
            'process_id'                => 'required',
            'sample_id'                 => 'required',
            'property_set_id'           => 'required',
            'transform'                 => 'boolean',
            'files_by_name'             => 'required|array',
            'files_by_name.*.path'      => 'required|string',
            'files_by_name.*.direction' => ['required', Rule::in(['in', 'out'])],
        ];
    }
}
