<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateContactRequest extends BaseFormRequest
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
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('contacts')->ignore($this->contact->id),
            ],
            'phone_number' => [
                'required',
                'phone:LK',
                Rule::unique('contacts')->ignore($this->contact->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.phone' => 'The :attribute field must be a valid phone number.',
        ];
    }
}
