<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * ============================================================================
 * AuthController
 * ============================================================================
 *
 * Manages secure user sessions, credential verification, and state termination
 * for platform personnel.
 *
 * Responsibilities:
 * --------------------------------------------------------------------------
 * - Authentication validation 
 * - Session regeneration to mitigate fixation attacks
 * - Graceful session termination (Logout)
 * ============================================================================
 */
class AuthController extends Controller
{
    /**
     * Display the application authentication/login interface.
     *
     * @return View
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Process an inbound authentication request and establish a secure session.
     *
     * @param LoginRequest $request The validated credentials payload.
     * @return RedirectResponse
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        // Debug: See what we're actually getting
        Log::info('Login attempt', [
            'email' => $request->email,
            'remember_input' => $request->input('remember'),
            'remember_boolean' => $request->boolean('remember'),
            'all_input' => $request->all()
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Terminate the active authentication session and invalidate tokens.
     *
     * @param Request $request The current stateful HTTP request.
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
