<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use App\Http\ViewComposers\UserFieldsComposer;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component('shared._card', 'card');
  
        // 2-13-Compartir datos entre vistas de Laravel con View Composers
        // View::composer(['users._fields'], UserFieldsComposer::class);

        // 2-14-View Components y creación de directivas personalizadas para Laravel y Blade
        Blade::directive('render', function ($expression) {
            $parts = explode(',', $expression, 2);

            $component = $parts[0];
            $args = trim($parts[1] ?? '[]');

            return "<?php echo app('App\Http\ViewComponents\\\\'.{$component}, {$args})->toHtml() ?>";
        });
    }
}
