<?php

namespace App\Http\Requests\Mql;

use Illuminate\Foundation\Http\FormRequest;

class MqlStoreSaveQueryRequest extends FormRequest
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
            'activities'    => 'array|nullable',
            'process_attrs' => 'array|nullable',
            'sample_attrs'  => 'array|nullable',
            'name'          => 'string|required|max:32',
            'description'   => 'string|nullable|max:2048',
        ];
    }
}
