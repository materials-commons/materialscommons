<?php

namespace App\Http\Requests\Communities;

use Illuminate\Foundation\Http\FormRequest;

class CreateCommunityRequest extends FormRequest
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
            'name'        => 'required|string|max:80',
            'description' => 'required|string|max:8192',
            'summary'     => 'required|string|max:100',
            'public'      => 'boolean',
        ];
    }
}
