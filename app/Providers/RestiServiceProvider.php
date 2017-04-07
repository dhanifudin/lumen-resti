<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RestiServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function register()
    {
        $this->app->configure('resti');
        $resources = config('resti.resources');

        foreach((array) $resources as $resource => $config) {
            $model = $config['model'];
            $repository = isset($config['repository'])
                ? $config['repository']
                : 'App\Repositories\EloquentRepository';
            $controller = isset($config['controller'])
                ? $config['controller']
                : 'App\Http\Controllers\RestiController';
            $this->registerModel($resource, $model);
            $this->registerRepository($resource, $repository);
            $this->registerController($resource, $controller);
            if (isset($config['routes'])) {
                $this->registerCustomRoute($resource, $config['routes']);
            }
            $this->registerRoute($resource);
        }
    }

    protected function registerModel($resource, $model)
    {
        $this->app->bind("resti.model.$resource", function ($app) use ($model) {
            return $app->make($model);
        });
    }

    protected function registerRepository($resource, $repository)
    {
        $this->app->singleton(
            "resti.repository.$resource",
            function ($app) use ($repository, $resource) {
            $instance = $app->make($repository);
            $instance->setModel($app->make("resti.model.$resource"));
            return $instance;
        });
    }

    protected function registerController($resource, $controller)
    {
        $this->app->singleton(
            "resti.controller.$resource",
            function ($app) use ($controller, $resource) {
            $instance = $app->make($controller);
            $instance->setRepository($app->make("resti.repository.$resource"));
            return $instance;
        });
    }

    protected function registerCustomRoute($resource, $routes)
    {
        foreach ((array) $routes as $key => $value) {
            $this->app->addRoute(
                $value['method'],
                "$resource/$key",
                "resti.controller.$resource@".$value['action']
            );
        }
    }

    protected function registerRoute($resource)
    {
        $this->app->get("$resource", "resti.controller.$resource@all");
        $this->app->get("$resource/{id}", "resti.controller.$resource@get");
        $this->app->post("$resource", "resti.controller.$resource@store");
        $this->app->put("$resource/{id}", "resti.controller.$resource@update");
        $this->app->delete("$resource/{id}", "resti.controller.$resource@destroy");
    }
}
