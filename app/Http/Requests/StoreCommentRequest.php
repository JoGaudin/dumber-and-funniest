<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
            'league_id' => 'required|exists:leagues,league_id',
            'user_id' => 'required|exists:users,id',
            'comment_type_id' => 'required|exists:comment_types,comment_type_id',
            'comment' => 'required|string|max:1000',
        ];
    }
}
