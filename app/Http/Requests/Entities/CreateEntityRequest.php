<?php

namespace App\Http\Requests\Entities;

use Elegant\Sanitizer\Laravel\SanitizesInput;
use Illuminate\Foundation\Http\FormRequest;

class CreateEntityRequest extends FormRequest
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
            'description'        => 'nullable|string|max:8192',
            'summary'            => 'nullable|string|max:100',
            'category'           => 'required|in:experimental,computational',
            'project_id'         => 'nullable|integer',
            'experiment_id'      => 'nullable|integer',
            'activity_id'        => 'required|integer',
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
