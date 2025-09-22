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
        // Fortify が使う　LoginRequest を自作のものに差し替える
        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ユーザー登録処理
        Fortify::createUsersUsing(CreateNewUser::class);

        // ビュー設定
        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        // 登録後のリダイレクト
        // Fortify::redirects('register', '/mypage/profile');
        $this->app->singleton(RegisterResponse::class, CustomRegisterResponse::class);

        // ログイン後のリダイレクト
        // Fortify::redirects('login', '/');
        $this->app->singleton(LoginResponse::class, function ($app) {
            return new class implements LoginResponse {
                public function toResponse($request)
                {
                    $user = $request->user();

                    // プロフィール未設定なら初回ログインとみなして /mypage/profile へ
                    if (!$user->profile) {
                        return redirect()->route('profile.edit');
                    }

                    // それ以外はトップページへ
                    return redirect()->route('home');
                }
            };
        });

        // ログアウト後のリダイレクト
        // Fortify::redirects('logout', '/login');
        $this->app->singleton(LogoutResponse::class, CustomLogoutResponse::class);         

        // ログイン試行回数制限
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(10)->by((string) $request->email . $request->ip());
        });
    }
}
