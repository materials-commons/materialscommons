<?php

namespace App\Http\Requests\Datasets;

use Illuminate\Foundation\Http\FormRequest;

class ChangeFileSelectionRequest extends FormRequest
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
            "include_files" => "array|required",
            "exclude_files" => "array|required",
            "include_dirs"  => "array|required",
            "exclude_dirs"  => "array|required",
        ];
    }
}
