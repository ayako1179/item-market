<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\LoginRequest;
use App\Http\Responses\RegisterResponse as CustomRegisterResponse;
use App\Http\Responses\LogoutResponse as CustomLogoutResponse;

use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Contracts\LoginResponse;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);

        $this->app->singleton(
            \Laravel\Fortify\Contracts\RegisterResponse::class,
            \App\Http\Response\RegisterResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        $this->app->singleton(RegisterResponse::class, CustomRegisterResponse::class);

        $this->app->singleton(LoginResponse::class, function ($app) {
            return new class implements LoginResponse {
                public function toResponse($request)
                {
                    $user = $request->user();

                    if (!$user->profile) {
                        return redirect()->route('profile.edit');
                    }

                    return redirect()->route('home');
                }
            };
        });

        $this->app->singleton(LogoutResponse::class, CustomLogoutResponse::class);         

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(10)->by((string) $request->email . $request->ip());
        });
    }
}
