<?php

namespace App\Http\Controllers\Auth;

use App\Actions\UpdateStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;


class UserAuthControlleur extends Controller
{
    public function index(): View
    {
        return view('accueil');
    }

    public function dashboard(): View
    {
        return view('dashboard');
    }

    public function login(): View
    {
        return view('auth.login');
    }

    public function userLogin(LoginRequest $request, UpdateStatusAction $updateStatusAction): RedirectResponse
    {
        $request->authentificate();
        $user = auth()->user();
        $updateStatusAction->handle($user, 'en ligne');

        return redirect()->intended('dashboard');
    }

    public function register(): View
    {
        return view('auth.register');
    }

    public function userRegister(RegisterRequest $request, UserService $userService): RedirectResponse
    {
        $user = $userService->createUser($request);

        if ($user) {
            return redirect()->intended('dashboard');
        }
    }

    public function destroy(Request $request, $uniqueId, UpdateStatusAction $updateStatusAction): RedirectResponse
    {
        $user = User::firstWhere('unique_id', $uniqueId);
        $userUpdate = $updateStatusAction->handle($user, 'hors ligne');

        if ($userUpdate) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/');
        }
    }
}
