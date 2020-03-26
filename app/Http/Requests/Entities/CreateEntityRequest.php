<?php

namespace App\Http\Requests\Entities;

use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

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
            'name'          => 'required|string|max:80',
            'description'   => 'nullable|string|max:8192',
            'summary'       => 'nullable|string|max:100',
            'project_id'    => 'nullable|integer',
            'experiment_id' => 'nullable|integer',
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
