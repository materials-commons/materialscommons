<?php

namespace App\Http\Requests\Activities\Files;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddFilesToActivityRequest extends FormRequest
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
            'files'             => 'required|array',
            'files.*.id'        => 'required|integer',
            'files.*.direction' => ['required', Rule::in(['in', 'out'])],
        ];
    }
}
