<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class PrincipalController extends Controller
{

    public function index()
    {
        return view('accueil');
    }


    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function create(Request $req)
    {
        //validation
        $req->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mot_de_passe' => [
                'required', 'confirmed', Password::min(8)
                    ->letters()
                    ->numbers()
                    ->mixedCase()
                    ->symbols()
                    ->uncompromised()
            ],
        ]);

        $nom = $req->input('nom');
        $prenom = $req->input('prenom');
        // chaque utlisateur a un unique identifiant
        $random_id = rand(time(), 100000000);
        $email = $req->input('email');

        // télécharger un fichier
        $extensions = ["jpg", "png", "jpeg", "jfif"]; // extensions permis
        if (in_array($req->image->extension(), $extensions)) { // si l'extension d'image correspond aux extensions permis.

            $image_nom = time() . '.' . $req->image->extension();
            $image_chemin = $req->file('image')->storeAs(
                "fichier_telecharger",
                $image_nom,
                'public'
            );

            $image_url = Storage::url($image_chemin);

            $status = "en ligne";

            $mdp = Hash::make($req->input('mot_de_passe'));

            $user = User::create([
                'nom' => $nom,
                'prenom' => $prenom,
                'unique_id' => $random_id,
                'email' => $email,
                'image' => $image_url,
                'status' => $status,
                'mdp' => $mdp,
            ]);

            $req->session()->put('LoggedUser', $user);

            return redirect('/dashboard');
        } else {
            return back()->with("erreur_img", "Selectionner une image png, jpeg, jpg, jfif, s'il vous plaît.");
        }
    }

    public function connection()
    {
        $unique_id = session()->get('LoggedUser.unique_id');
        $users = User::where('unique_id', $unique_id)->get();
        return view('dashboard', [
            'users' => $users,
        ]);
    }

    public function generate(Request $request)
    {
        $userInfo = User::where('email', '=', $request->email)->first();

        if (!$userInfo) {
            return back()->with('erreur', 'L\'email entré ne possède pas encore un compte.');
        } else {
            // verifier mot de passe
            if (Hash::check($request->mdp, $userInfo->mdp)) {

                $request->session()->put('LoggedUser', $userInfo);
                $unique_id = session()->get('LoggedUser.unique_id');

                $user = User::where('unique_id', $unique_id)->update([
                    'status' => 'en ligne',
                ]);

                if ($user) {
                    $users = User::where('unique_id', $unique_id)->get();
                    return view('dashboard', [
                        'users' => $users,
                    ]);
                }
            } else {
                return back()->with('erreur', 'Le mot de passe est incorrect.');
            }
        }
    }

    public function destroy($id)
    {
        $user = User::where('unique_id', $id)->update([
            'status' => 'hors ligne',
        ]);

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
}
