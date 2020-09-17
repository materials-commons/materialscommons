<?php

namespace App\Http\Requests\Datasets;

use Illuminate\Foundation\Http\FormRequest;

class DatasetRequest extends FormRequest
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
            'name'                              => 'required|string|max:200',
            'description'                       => 'nullable|string|max:8192',
            'summary'                           => 'nullable|string|max:100',
            'license'                           => 'nullable|string|max:256',
            'action'                            => 'nullable|string',
            'experiments'                       => 'nullable|array',
            'communities'                       => 'nullable|array',
            'tags'                              => 'nullable|array',
            'tags.*.value'                      => 'required|string',
            'mc_authors'                        => 'required|array',
            'additional_authors'                => 'nullable|array',
            'additional_authors.*.name'         => 'required|string',
            'additional_authors.*.email'        => 'required|email',
            'additional_authors.*.affiliations' => 'required|string',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tags' => json_decode($this->tags, true),
        ]);
    }
}
