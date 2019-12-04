<?php

namespace App\Http\Requests\Published\Datasets\Comments;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDatasetCommentRequest extends FormRequest
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
            'title' => 'required|string|max:80',
            'body'  => 'required|string|max:2048',
        ];
    }
}
