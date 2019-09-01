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
            if (!strpos($expression, '[')) {
                list($partial, $bladeArgs, $lazy) = array_pad(explode(',', $expression), 3, null);
            } else {
                $exploded = explode(',', $expression);
                $partial = reset($exploded);
                preg_match('/\[(.*?)]/', $expression, $pregArray);
                $bladeArgs = $pregArray[0] . ']';
                $lazy = trim(end($exploded));
            }

            $lazyHtml = '';
            if (!is_null($lazy) && strtolower($lazy == "true")) {
                $lazyHtml = 'data-render-lazy=\'true\'';
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
