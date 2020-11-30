<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

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

    /* Builder::macro('whereQuery', function ($subquery, $value) {
        $this->addBinding($subquery->getBindings());
        $this->where(DB::raw("({$subquery->toSql()})"), $value);
    }); */
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