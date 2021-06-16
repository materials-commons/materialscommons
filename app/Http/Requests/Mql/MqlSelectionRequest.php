<?php

namespace App\Http\Requests\Mql;

use Illuminate\Foundation\Http\FormRequest;

class MqlSelectionRequest extends FormRequest
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
            'activities'    => 'array',
            'process_attrs' => 'array',
            'sample_attrs'  => 'array',
        ];
    }
}
