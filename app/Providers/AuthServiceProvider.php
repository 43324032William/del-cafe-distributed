<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define admin gate
        Gate::define('admin-only', function ($user) {
            return $user->role === 'admin';
        });

        // Define order ownership gate
        Gate::define('view-order', function ($user, $order) {
            return $user->id === $order->user_id || $user->role === 'admin';
        });

        // Define menu ownership gate
        Gate::define('manage-menu', function ($user) {
            return $user->role === 'admin';
        });
    }
}