<?php

namespace App\Providers;

use App\Scramble\Support\ExceptionToResponseExtensions\AuthorizationExceptionToResponseExtension as AppAuthorizationExceptionToResponseExtension;
use App\Scramble\Support\ExceptionToResponseExtensions\NotFoundExceptionToResponseExtension as AppNotFoundExceptionToResponseExtension;
use App\Scramble\Support\ExceptionToResponseExtensions\ValidationExceptionToResponseExtension as AppValidationExceptionToResponseExtension;
use Dedoc\Scramble\Support\ExceptionToResponseExtensions\AuthorizationExceptionToResponseExtension;
use Dedoc\Scramble\Support\ExceptionToResponseExtensions\NotFoundExceptionToResponseExtension;
use Dedoc\Scramble\Support\ExceptionToResponseExtensions\ValidationExceptionToResponseExtension;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = AliasLoader::getInstance();
        $loader->alias(NotFoundExceptionToResponseExtension::class, AppNotFoundExceptionToResponseExtension::class);
        $loader->alias(ValidationExceptionToResponseExtension::class, AppValidationExceptionToResponseExtension::class);
        $loader->alias(AuthorizationExceptionToResponseExtension::class, AppAuthorizationExceptionToResponseExtension::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
