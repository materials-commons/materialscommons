<?php

namespace App\Http\Requests\Datasets;

use App\Rules\Files\IsValidFileName;
use Illuminate\Foundation\Http\FormRequest;

class ImportDatasetIntoProjectRequest extends FormRequest
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
            'directory' => ['required', 'string', 'max:80', new IsValidFileName()],
        ];
    }
}
