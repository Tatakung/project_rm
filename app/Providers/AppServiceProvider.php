<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Paginator::useBootstrapFive();
        // ตั้งค่าวันเวลาปลอม (Fake Date)
        // $fakeDate = env('FAKE_DATE', null);

        // if ($fakeDate) {
        //     Carbon::setTestNow(Carbon::parse($fakeDate));
        // }
    }
}
