<?php

namespace App\Http\Controllers;

use App\Models\CustomerAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the unified login form.
     */
    public function showLogin()
    {
        // If already logged in, redirect to appropriate dashboard
        if (Auth::guard('web')->check()) {
            return $this->redirectByRole(Auth::guard('web')->user());
        }
        if (Auth::guard('nasabah')->check()) {
            return redirect()->route('nasabah.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login — auto-detect whether username belongs to users or customer_accounts.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // 1) Try pegawai (users table) first
        $user = User::where('username', $username)->first();
        if ($user) {
            if ($user->status !== 'Aktif') {
                return back()->with('error', 'Akun Anda tidak aktif. Hubungi Administrator.')->withInput();
            }

            if (Hash::check($password, $user->password)) {
                Auth::guard('web')->login($user);
                $request->session()->regenerate();
                return $this->redirectByRole($user);
            }

            return back()->with('error', 'Username atau password salah.')->withInput();
        }

        // 2) Try nasabah (customer_accounts table)
        $customerAccount = CustomerAccount::where('username', $username)->first();
        if ($customerAccount) {
            if (Hash::check($password, $customerAccount->password)) {
                // Check if nasabah is active
                $nasabah = $customerAccount->nasabah;
                if (!$nasabah || $nasabah->status !== 'Aktif') {
                    return back()->with('error', 'Rekening Anda tidak aktif. Hubungi Administrator.')->withInput();
                }

                Auth::guard('nasabah')->login($customerAccount);
                $request->session()->regenerate();
                return redirect()->route('nasabah.dashboard');
            }

            return back()->with('error', 'Username atau password salah.')->withInput();
        }

        return back()->with('error', 'Username tidak ditemukan.')->withInput();
    }

    /**
     * Logout user (either guard).
     */
    public function logout(Request $request)
    {
        if (Auth::guard('nasabah')->check()) {
            Auth::guard('nasabah')->logout();
        } else {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    /**
     * Redirect user based on role.
     */
    private function redirectByRole(User $user)
    {
        return match ($user->role) {
            'Administrator' => redirect()->route('admin.dashboard'),
            'Supervisor' => redirect()->route('supervisor.dashboard'),
            'Teller' => redirect()->route('teller.dashboard'),
            default => redirect()->route('login'),
        };
    }
}
