<?php

namespace App\Services;

use App\Actions\ImageUploadAction;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public $ImageUploadAction;

    public function __construct(ImageUploadAction $ImageUploadAction)
    {
        $this->ImageUploadAction = $ImageUploadAction;
    }


    public function createUser(Request $request): User
    {
        // identifiant aléatoire pour remplacer la véritable identifiant de l'user
        $randomUserId = rand(time(), 100000000);
        $imageUrl = $this->ImageUploadAction->handle($request->file('image'));

        $user = User::create([
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
            'unique_id' => $randomUserId,
            'email' => $request->input('email'),
            'image' => $imageUrl,
            'mot_de_passe' => Hash::make(
                $request->input('mot_de_passe')
            ),
        ]);

        Auth::login($user);

        return $user;
    }
}
