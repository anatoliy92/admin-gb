<?php

namespace Avl\AdminGb;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Config;

class AdminGbServiceProvider extends AuthServiceProvider
{

	/**
   * The policy mappings for the application.
   *
   * @var array
   */
  protected $policies = [
    \Avl\AdminGb\Models\Gb::class => \Avl\AdminGb\Policies\GbPolicy::class,
  ];

		/**
		 * Bootstrap the application services.
		 *
		 * @return void
		 */
		public function boot()
		{
			$this->registerPolicies();

				$this->publishes([
						__DIR__ . '/../public' => public_path('vendor/admingb'),
				], 'public');

				$this->loadRoutesFrom(__DIR__ . '/routes.php');

				$this->loadViewsFrom(__DIR__ . '/../resources/views', 'admingb');
		}

		/**
		 * Register the application services.
		 *
		 * @return void
		 */
		public function register()
		{
				// Добавляем в глобальные настройки системы новый тип раздела
				Config::set('avl.sections.gb', 'Гостевая книга');

				// объединение настроек с опубликованной версией
				$this->mergeConfigFrom(__DIR__ . '/../config/admingb.php', 'admingb');

				// migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

		}

}
