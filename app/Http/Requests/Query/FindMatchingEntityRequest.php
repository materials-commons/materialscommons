<?php

namespace App\Http\Requests\Query;

use Illuminate\Foundation\Http\FormRequest;

class FindMatchingEntityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "activities"                     => "nullable|array",
            "activities.*.name"              => "required|string",
            "activities.*.operator" => "required|in:in",
            "activity_attributes"            => "nullable|array",
            "activity_attributes.*.name"     => "required|string",
            "activity_attributes.*.operator" => "required|in:having,having-like,eq,ne,lt,lte,gt,gte,like",
            "activity_attributes.*.value"    => "required|string",
            "entity_attributes"              => "nullable|array",
            "entity_attributes.*.name"       => "required|string",
            "entity_attributes.*.operator"   => "required|in:having,having-like,eq,ne,lt,lte,gt,gte,like",
            "entity_attributes.*.value"      => "required|string",
        ];
    }
}
