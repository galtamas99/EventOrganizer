<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'title'       => ['required','string','max:255'],
            'description' => ['required','string'],
            'starts_at'   => ['required','date'],
            'location'    => ['required','string','max:255'],
            'capacity'    => ['required','integer','min:0'],
            'ticket_price'=> ['required','integer','min:0'],
            'category'    => ['nullable','string','max:100'],
            'status'      => ['required','in:draft,published,cancelled'],
        ];
    }
}
