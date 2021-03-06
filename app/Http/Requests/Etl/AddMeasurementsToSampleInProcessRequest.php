<?php

namespace App\Http\Requests\Etl;

use Illuminate\Foundation\Http\FormRequest;

class AddMeasurementsToSampleInProcessRequest extends FormRequest
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
            'attributes'                => 'required|array',
            'attributes.*.name'         => 'required|string',
            'attributes.*.id'           => 'required',
            'attributes.*.measurements' => 'array|required',
        ];
    }
}