<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
    return [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        'phone' => ['nullable', 'string', 'max:20'],
        'blood_group' => ['nullable', 'string', 'max:5'],
        
        // Add all your new fields here:
        'phone' => ['nullable', 'string', 'max:20'],
        'entry_no' => ['nullable', 'string', 'max:255'],
        'degree' => ['nullable', 'string', 'max:255'],
        'department' => ['nullable', 'string', 'max:255'],
        'year_joining' => ['nullable', 'string', 'max:4'],
        'graduation_year' => ['nullable', 'string', 'max:4'],
        'company' => ['nullable', 'string', 'max:255'],
        'designation' => ['nullable', 'string', 'max:255'],
        'work_industry' => ['nullable', 'string', 'max:255'],
        'skills' => ['nullable', 'string', 'max:500'],
    ];
    }
}