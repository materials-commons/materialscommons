<?php

namespace App\Http\Requests\EntityStates;

use Illuminate\Foundation\Http\FormRequest;

class CreateEntityStateRequest extends FormRequest
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
            'project_id'         => 'required|integer',
            'entity_id'          => 'required|integer',
            'activity_id'        => 'required|integer',
            'current'            => 'boolean',
            'attributes'         => 'array',
            'attributes.*.value' => 'required',
            'attributes.*.name'  => 'required|string|max:80',
            'attributes.*.unit'  => 'string',
        ];
    }
}
