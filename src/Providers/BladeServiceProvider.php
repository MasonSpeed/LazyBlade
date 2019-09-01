<?php

namespace captenmasin\LazyBlade\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('lazyinclude', function ($expression) {
            $args = explode(',', preg_replace("/[\(\)]/", '', $expression), 2);
            $partial = $args[0];
            $bladeArgs = $args[1];
            $lazyHtml = '';
            if (isset($args[2])) {
                $lazy = $args[2];
                if ($lazy) {
                    $lazyHtml = 'data-render-lazy=\'true\'';
                }
            }
            return '
                <div 
                data-render-blade=' . $partial . '
                data-render-args=\'<?php echo urlencode(json_encode(' . $bladeArgs . ')); ?>\'
                <?php echo "' . $lazyHtml . '"; ?>></div>
            ';
        });
    }
}
