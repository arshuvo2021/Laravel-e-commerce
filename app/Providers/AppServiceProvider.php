<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrap();

        // Log all API requests
        Request::macro('isApi', function () {
            return str_starts_with($this->path(), 'api/');
        });

        $this->app['router']->matched(function ($event) {
            $route = $event->route;
            $request = $event->request;
            if ($request->isApi()) {
                Log::channel('api')->info('API Request', [
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                    'user_id' => $request->user()?->id,
                    'input' => $request->except(['password', 'password_confirmation']),
                ]);
            }
        });
    }
}
