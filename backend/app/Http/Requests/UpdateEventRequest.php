<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
         return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'       => ['sometimes','required','string','max:255'],
            'description' => ['sometimes','required','string'],
            'starts_at'   => ['sometimes','required','date'],
            'location'    => ['sometimes','required','string','max:255'],
            'capacity'    => ['sometimes','required','integer','min:0'],
            'ticket_price'=> ['sometimes','required','integer','min:0'],
            'category'    => ['nullable','string','max:100'],
            'status'      => ['sometimes','required','in:draft,published,cancelled'],
        ];
    }
}
