<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * CrmServiceProvider — mendaftarkan route CRM secara modular.
 * Dikelola tim CRM (Anggota 3).
 *
 * Pendaftaran: jalankan
 *   php artisan make:provider CrmServiceProvider
 * atau tambahkan class ini pada ->withProviders() di bootstrap/app.php.
 */
class CrmServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware('web')
            ->group(base_path('routes/crm.php'));
    }
}
