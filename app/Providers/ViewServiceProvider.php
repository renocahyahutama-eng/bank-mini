<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $userName = 'Guest';
            $userRole = 'Guest';

            if (Auth::guard('web')->check()) {
                $user = Auth::guard('web')->user();
                $userName = $user->name;
                $userRole = $user->role;
            } elseif (Auth::guard('nasabah')->check()) {
                $customerAccount = Auth::guard('nasabah')->user();
                $userName = $customerAccount->nasabah->student_name ?? 'Nasabah';
                $userRole = 'Nasabah';
            }

            $view->with('__currentUserName', $userName);
            $view->with('__currentUserRole', $userRole);
        });
    }

    public function register(): void
    {
        //
    }
}
