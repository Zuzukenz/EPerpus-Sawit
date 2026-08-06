<?php

namespace App\Providers;

use App\Contracts\Repositories\BookRepositoryInterface;
use App\Contracts\Repositories\BorrowingRepositoryInterface;
use App\Contracts\Repositories\MemberRepositoryInterface;
use App\Contracts\Services\BorrowingServiceInterface;
use App\Repositories\BookRepository;
use App\Repositories\BorrowingRepository;
use App\Repositories\MemberRepository;
use App\Services\BorrowingService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Repository Bindings
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(MemberRepositoryInterface::class, MemberRepository::class);
        $this->app->bind(BorrowingRepositoryInterface::class, BorrowingRepository::class);

        // Service Bindings
        $this->app->bind(BorrowingServiceInterface::class, BorrowingService::class);
    }

    public function boot(): void
    {
        //
    }
}