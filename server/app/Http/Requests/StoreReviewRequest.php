<?php

namespace App\Http\Requests;

use App\Enums\MediaType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "content" => ["required", "string"],
            "rating" => ["required", "integer"],
            "media_id" => ["required", "integer"],
            "media_type" => ["required", Rule::enum(MediaType::class)],
        ];
    }
}
