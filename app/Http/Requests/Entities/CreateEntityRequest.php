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
            'description'   => 'string|max:2048',
            'project_id'    => 'required|integer',
            'experiment_id' => 'integer',
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
