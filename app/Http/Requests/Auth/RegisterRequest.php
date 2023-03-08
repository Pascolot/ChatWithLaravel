<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

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
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'max:255', 'unique:users'],
            'mot_de_passe' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->mixedCase()
                    ->symbols()
                    ->uncompromised(3)
            ],
            'image' => ['required', 'file', 'mimes:jpeg,jpg,png,jfif', 'max:5120'], // max image upload 5Mo
        ];
    }

    public function messages()
    {
        return [
            'nom.required' => 'Le nom est requis.',
            'prenom.required' => 'Le prenom est requis.',
            'email.required' => 'L\'email est requis.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'mot_de_passe.required' => 'Le mot de passe est requis.',
            'mot_de_passe.confirmed' => 'Les deux mots de passe entrés ne sont pas identiques.',
            'image.required' => 'L\'image est requis.',
            'image.file' => 'Le fichier n\'est pas un image.',
            'image.mimes' => 'Selectioner un image en jpeg, jpg, png ou jfif, s\'il vous plaît.',
            'image.max' => 'Le fichier est trop volumineux (max 5Mo).',
        ];
    }
}
