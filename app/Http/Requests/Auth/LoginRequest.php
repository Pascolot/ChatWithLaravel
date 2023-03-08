<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'mot_de_passe' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'L\'email est requis.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'email.exists' => 'L\'email ne possède pas encore un compte.',
            'mot_de_passe.required' => 'Le mot de passe est requis.',
        ];
    }

    public function authentificate()
    {
        if (!auth()->attempt($this->only('email', 'mot_de_passe'))) {
            throw ValidationException::withMessages([
                'mot_de_passe' => 'Le mot de passe est incorrect.',
            ]);
        }
    }
}
