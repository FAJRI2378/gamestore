<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gate untuk memastikan hanya admin yang bisa mengakses
        Gate::define('isAdmin', function (User $user) {
            return $user->hasRole('admin'); // Pastikan role admin sudah benar
        });
        
    }
}
