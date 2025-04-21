<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'string'], // Removed 'email' validation to allow usernames
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Get the input value from the email field
        $username = $this->input('email');

        // Try to find the user by exact email match first
        $user = User::where('email', $username)->first();

        // If user not found and the input doesn't contain '@', try to find a teacher with matching username
        if (!$user && !Str::contains($username, '@')) {
            $user = User::where('role', 'teacher')
                        ->where('email', $username)
                        ->first();
        }

        // If we found a user, attempt to authenticate with their actual email
        if ($user) {
            if (Auth::attempt(['email' => $user->email, 'password' => $this->input('password')], $this->boolean('remember'))) {
                RateLimiter::clear($this->throttleKey());
                return;
            }
        } else {
            // Try standard authentication as fallback
            if (Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
                RateLimiter::clear($this->throttleKey());
                return;
            }
        }

        // If we reach here, authentication failed
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
