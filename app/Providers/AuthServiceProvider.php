<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Policies\postPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        Post::class => postPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        Gate::define('VisitAdminPages', function($user){
            return auth()->check() && auth()->user()->is_admin === 1;
        });
    }
}
