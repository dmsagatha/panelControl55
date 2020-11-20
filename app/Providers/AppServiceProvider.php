<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    Route::resourceVerbs([
      'create' => 'crear',
      'edit'   => 'editar',
    ]);

    Paginator::defaultSimpleView('shared.simple-pagination');
    Paginator::defaultView('shared.pagination');
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
  }
}