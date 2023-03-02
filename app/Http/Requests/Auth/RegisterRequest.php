<?php

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'nom' => ['required', 'string', 'max:255'], // Using array notation will make it easier to apply custom rule to this field
            'prenom' =>  ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mdp' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->mixedCase()
                    ->symbols()
                    ->uncompromised()
            ],
            'image' => ['required', 'file' /* or 'image' */, 'mimes:jpeg,png,jpeg,jfif', 'max:10240'], // max allowed size 10MB
        ];
    }

    public function messages()
    {
        return [
            'prenom.required' => 'Le prénom est requis',
            'email.required' => 'L\'email est requis',
            'mdp.required' => 'Le mot de passe est requis',
            'mdp.confirmed' => 'Les mots de passe ne correspondent pas',
            'image.required' => 'L\'image est requise',
            'image.image' => 'Le fichier n\'est pas une image',
            'image.mimes' => "Selectionner une image png, jpeg, jpg, jfif, s'il vous plaît.",
            'image.max' => 'Le fichier est trop volumineux (max 10MB)',
        ];
    }
}
