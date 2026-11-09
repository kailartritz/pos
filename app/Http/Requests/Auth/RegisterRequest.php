<?php

namespace App\Http\Requests\Auth;

use App\Services\Users\DuplicateAccountGuard;
use App\Support\Constants;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'state' => ['required', 'string', Rule::in(Constants::usStateCodes())],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $exists = app(DuplicateAccountGuard::class)->exists(
                    $this->string('first_name')->toString(),
                    $this->string('last_name')->toString(),
                    $this->date('date_of_birth')->toDateString(),
                    $this->string('state')->toString(),
                );

                if ($exists) {
                    $validator->errors()->add(
                        'first_name',
                        'An account with these details already exists.',
                    );
                }
            },
        ];
    }
}
