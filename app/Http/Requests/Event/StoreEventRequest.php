<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Any authenticated user can create an event
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date', 'after:now'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'address' => ['required', 'string', 'max:255'],
            'max_participants' => ['required', 'integer', 'min:1'],
            'category' => ['required', 'string', 'in:social,sports,education,entertainment,other'],
            'status' => ['required', 'string', 'in:draft,published'],
            'visibility' => ['sometimes', 'string', 'in:public,private'],
            'is_online' => ['sometimes', 'boolean'],
            'online_url' => ['sometimes', 'nullable', 'url'],
        ];
    }



    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'start_date.after' => 'The event must start in the future.',
            'end_date.after' => 'The end date must be after the start date.',
            'max_participants.min' => 'An event must allow at least one participant.',
        ];
    }
}