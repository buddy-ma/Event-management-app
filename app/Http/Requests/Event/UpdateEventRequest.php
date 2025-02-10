<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by EventPolicy
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'start_date' => [
                'sometimes',
                'date',
                'after:now',
            ],
            'end_date' => [
                'sometimes',
                'nullable',
                'date',
                'after:start_date',
            ],
            'address' => ['sometimes', 'string', 'max:255'],
            'max_participants' => [
                'sometimes',
                'integer',

                'min:1',
                function ($attribute, $value, $fail) {
                    $currentParticipants = $this->route('event')->participants()->count();
                    if ($value < $currentParticipants) {
                        $fail("Cannot reduce maximum participants below current participant count ({$currentParticipants}).");
                    }
                },
            ],
            'category' => ['sometimes', 'string', 'in:social,sports,education,entertainment,other'],
            'status' => ['sometimes', 'string', 'in:draft,published,cancelled'],
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