<?php

namespace App\Http\Requests\Etl;

use Illuminate\Foundation\Http\FormRequest;
use function is_array;
use function is_numeric;
use function is_string;

class CreateWorkflowRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "activities"                                       => 'required|array',
            "activities.*.name"                                => 'required|string',
            "activities.*.description"                         => "string|nullable",
            "activities.*.summary"                             => "string|nullable",
            "activities.*.atype"                               => "string|nullable",
            "activities.*.category"                            => "nullable|in:experimental,computational",
            "activities.*.files"                               => "array|nullable",
            "activities.*.files.*"                             => "string",
            "activities.*.attributes"                          => "array|nullable",
            "activities.*.attributes.*.name"                   => "string|required",
            "activities.*.attributes.*.description"            => "string|nullable",
            "activities.*.attributes.*.unit"                   => "string|nullable",
            "activities.*.attributes.*.value"                  => [
                function ($attr, $value, $fail) {
                    if (!$this->isValidValue($value)) {
                        $fail("value is not a supported type");
                    }
                }
            ],
            "activities.*.entities"                            => "array|nullable",
            "activities.*.entities.*.name"                     => "string|required",
            "activities.*.entities.*.description"              => "string|nullable",
            "activities.*.entities.*.files"                    => "array|nullable",
            "activities.*.entities.*.files.*"                  => "string",
            "activities.*.entities.*.attributes"               => "array|nullable",
            "activities.*.entities.*.attributes.*.name"        => "string|required",
            "activities.*.entities.*.attributes.*.description" => "string|nullable",
            "activities.*.entities.*.attributes.*.unit"        => "string|nullable",
            "activities.*.entities.*.attributes.*.value"       => [
                function ($attr, $value, $fail) {
                    if (!$this->isValidValue($value)) {
                        $fail("value is not a supported type");
                    }
                }
            ],
            "entities"                                         => "array|nullable",
            "entities.*.name"                                  => "string|required",
            "entities.*.description"                           => "string|nullable",
            "entities.*.states"                                => "array|nullable",
            "entities.*.states.*.activity"                     => "string|required",
            "entities.*.states.*.attributes"                   => "array|nullable",
            "entities.*.states.*.attributes.*.name"            => "string|required",
            "entities.*.states.*.attributes.*.description"     => "string|nullable",
            "entities.*.states.*.attributes.*.unit"            => "string|nullable",
            "entities.*.states.*.attributes.*.value"           => [
                function ($attr, $value, $fail) {
                    if (!$this->isValidValue($value)) {
                        $fail("value is not a supported type");
                    }
                }
            ],
            "entities.*.states.*.files"                        => "array|nullable",
            "entities.*.states.*.files.*"                      => "string",
            "experiment_id"                                    => "nullable|integer",
            "category"                                         => "nullable|in:experimental,computational"
        ];
    }

    private function isValidValue($value): bool
    {
        if (is_array($value)) {
            return true;
        }

        if (is_string($value)) {
            return true;
        }

        if (is_numeric($value)) {
            return true;
        }

        return false;
    }
}
