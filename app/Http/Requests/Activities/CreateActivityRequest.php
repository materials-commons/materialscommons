<?php

namespace App\Http\Requests\Activities;

use Illuminate\Foundation\Http\FormRequest;
use Elegant\Sanitizer\Laravel\SanitizesInput;

class CreateActivityRequest extends FormRequest
{
    use SanitizesInput;

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
     *  Validation rules to be applied to the input.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'               => 'required|string|max:80',
            'description'        => 'nullable|string|max:2048',
            'project_id'         => 'required|integer',
            'experiment_id'      => 'integer',
            'attributes'         => 'array',
            'attributes.*.value' => 'required',
            'attributes.*.name'  => 'required|string|max:80',
            'attributes.*.unit'  => 'string',
        ];
    }

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [];
    }
}
