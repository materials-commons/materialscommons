<?php

namespace App\Http\Requests\Activities\EntityStates;

use Illuminate\Foundation\Http\FormRequest;

class AddEntityStatesToActivityRequest extends FormRequest
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
            'entity_states'   => 'required|array',
            'entity_states.*' => 'integer',
        ];
    }
}
