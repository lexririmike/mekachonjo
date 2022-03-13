<?php

namespace Mekachonjo\Payment\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Mekachonjo\Payment\Commands\SetWebhookCommand;
use Mekachonjo\Payment\Middlewares\PaymentTypeMiddleware;
use Mekachonjo\Payment\Middlewares\UpdateTypeMiddleware;

class ChonjopayServiceProvider extends ServiceProvider
{
    /**
     * Register Chonjopay service.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            \Mekachonjo\Payment\Interfaces\Chonjopay::class,
            config('chonjopay.service')
        );

        $this->app->singleton(
			\Mekachonjo\Payment\Abstractions\Kernel::class,
            config('chonjopay.kernel')
        );
    }

    /**
     * Boots Chonjopay service.
     *
     * @return void
     */
    public function boot()
    {
        $this->publish();

        $this->declareMacros();

        if ($this->app->runningInConsole()) {
            $this->commands([
                SetWebhookCommand::class
            ]);
        }

		$this->makeMiddlewareAliases();
    }

    /**
     * Publishes anything that chonjopay service needs
     *
     * @return void
     */
    public function publish()
    {
        $this->publishes([
            __DIR__.'../config/chonjopay.php' => function_exists('config_path') ? config_path('chonjopay.php') : base_path('config/chonjopay.php')
        ], 'chonjopay-config');

        $this->publishes([
            __DIR__.'../database/migrations' => database_path('migrations')
        ], 'chonjopay-migrations');

        $this->publishes([
            __DIR__.'../Kernel.php' => function_exists('app_path') ? app_path('Chonjopay/Kernel.php') : base_path('app/Chonjopay/Kernel.php')
        ], 'chonjopay-kernel');
    }

    /**
     * Declares service macros
     *
     * @return void
     */
    public function declareMacros()
    {
        Blueprint::macro('chonjopay', function () {
            static::bigInteger('chonjopay_id')->nullable();
            static::text('handler')->nullable()->comment('Full classname of current responsible handler');
        });
    }

	/**
	 * Makes middleware aliases for entire app.
	 *
	 * @throws \Illuminate\Contracts\Container\BindingResolutionException
	 */
	public function makeMiddlewareAliases()
	{
		$router = $this->app->make(Router::class);

		$router->aliasMiddleware('update-type', UpdateTypeMiddleware::class);
		$router->aliasMiddleware('payment-type', PaymentTypeMiddleware::class);
	}
}
