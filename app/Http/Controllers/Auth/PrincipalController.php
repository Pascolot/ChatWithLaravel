<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class PrincipalController extends Controller
{
    public function index(): View
    {
        return view('accueil');
    }

    public function login(): View
    {
        return view('auth.login');
    }

    public function register(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        $userData = $request->validated();

        // chaque utlisateur a un unique identifiant
        $userData['unique_id'] = time();
        $userData['mdp'] = Hash::make($request->mdp);
        $userData['image'] = $this->uploadImage($request->file('image'));

        $user = User::create($userData);
        $this->putLoggedUser($user);

        return redirect('/dashboard');
    }

    private function uploadImage(UploadedFile $image): string
    {
        $imageNom = time() . '.' . $image->extension();
        $imageChemin = $image->storeAs(
            "fichier_telecharger",
            $imageNom,
            'public'
        );

        return Storage::url($imageChemin);
    }

    public function connection(): View
    {
        $uniqueId = session()->get('LoggedUser.unique_id');
        $user = User::firstWhere('unique_id', $uniqueId);

        return view('dashboard', [
            'user' => $user,
        ]);
    }

    public function loginUser(LoginRequest $request): View
    {
        $user = User::firstWhere('email', $request->email);

        $this->validatePassword($request->mdp, $user->mdp);
        $this->putLoggedUser($user);
        $this->updateUsertatus($user, 'en ligne');

        return view('dashboard', [
            'user' => $user,
        ]);
    }

    public function logout(int $uniqueId)
    {
        $user = User::firstWhere('unique_id', $uniqueId);
        $this->updateUsertatus($user, 'hors ligne');

        if ($user) {
            if (session()->has('LoggedUser')) {
                session()->pull('LoggedUser');
                return redirect('auth/login');

                /* Si vous ne souhaite pas avoir un message
                'Vous devez se connecter' sur le login lors de la deconnexion
                alors utiliser => return view('auth.login');
                */
            }
        }
    }

    private function updateUsertatus(User $user, string $status): void
    {
        $user->update([
            'status' => $status,
        ]);
    }

    private function putLoggedUser(User $user): void
    {
        request()->session()->put('LoggedUser', $user);
    }

    private function validatePassword(string $mdp, string $hashedMdp)
    {
        if (!Hash::check($mdp, $hashedMdp)) {
            return back()->with('erreur', 'Le mot de passe est incorrect.');
        }
    }
}
