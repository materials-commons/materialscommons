<?php

namespace App\Http\Requests\Projects;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends FormRequest
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
            'name' => [
                'required', 'string', 'max:80',
                function ($attribute, $value, $fail) {
                    $count = Project::where('name', $value)
                                    ->where('owner_id', auth()->id())
                                    ->count();
                    if ($count != 0) {
                        $fail('User already has a project named '.$value);
                    }
                },
            ],

            'description' => 'string|max:2048',
            'is_active'   => 'boolean',
        ];
    }
}
