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
            'name'                        => 'required|string|max:200',
            'description'                 => 'nullable|string|max:8192',
            'summary'                     => 'nullable|string|max:100',
            'license'                     => 'nullable|string|max:256',
            'authors'                     => 'nullable|string|max:2048',
            'funding'                     => 'nullable|string|max:8192',
            'action'                      => 'nullable|string',
            'experiments'                 => 'nullable|array',
            'communities'                 => 'nullable|array',
            'tags'                        => 'nullable|array',
            'tags.*.value'                => 'required|string',
            'papers'                      => 'nullable|array',
            'papers.*.name'               => 'required|string',
            'papers.*.reference'          => 'required|string',
            'papers.*.doi'                => 'nullable|string',
            'papers.*.url'                => 'nullable|url',
            'existing_papers'             => 'nullable|array',
            'existing_papers.*.name'      => 'required|string',
            'existing_papers.*.reference' => 'required|string',
            'existing_papers.*.doi'       => 'nullable|string',
            'existing_papers.*.url'       => 'nullable|url',
            'ds_authors'                  => 'nullable|array',
            'ds_authors.*.name'           => 'required|string',
            'ds_authors.*.affiliations'   => 'nullable|string',
            'ds_authors.*.email'          => 'required|email',
            'file1_id'                    => 'nullable|integer',
            'file2_id'                    => 'nullable|integer',
            'file3_id'                    => 'nullable|integer',
            'file4_id'                    => 'nullable|integer',
            'file5_id'                    => 'nullable|integer',
        ];
    }

    protected function prepareForValidation()
    {
        if (is_string($this->tags)) {
            $this->merge([
                'tags' => json_decode($this->tags, true),
            ]);
        }
    }
}
