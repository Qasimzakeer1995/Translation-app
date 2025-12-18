<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\TranslationRepositoryInterface;
use App\Repositories\TranslationRepository;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            TranslationRepositoryInterface::class,
            TranslationRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
    }
}
