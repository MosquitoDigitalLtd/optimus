<?php

namespace Appitized\Optimus;

use Appitized\Optimus\Serializers\ApiDataSerializer;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;

class OptimusServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bind(Optimus::class, function () {
            $manager = new Manager();
            $manager->setSerializer(new ApiDataSerializer());
            $api = new Optimus($manager);
            return $api;
        });

        $this->app->alias(Optimus::class, 'api');
	}

}
