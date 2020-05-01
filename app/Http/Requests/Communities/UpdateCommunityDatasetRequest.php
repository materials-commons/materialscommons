<?php

namespace App\Http\Requests\Communities;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommunityDatasetRequest extends FormRequest
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
            'community_id' => 'required',
            'dataset_id'   => 'required',
        ];
    }
}
