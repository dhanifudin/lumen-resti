<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RestiServiceProvider extends ServiceProvider
{
    protected $defer = false;
    protected $models = [];

    public function register() {
        $this->app->configure('resti');
        $this->models = config('resti.models');

        foreach($this->models as $route => $model) {
            $this->registerModel($model, $route);
            $this->registerRepository('App\Repositories\EloquentRepository', $route);
            $this->registerController('App\Http\Controllers\RestiController', $route);
        }
    }

    public function boot() {
        foreach ($this->models as $route => $value) {
            $this->app->get("$route", "resti.controller.$route@all");
            $this->app->post("$route", "resti.controller.$route@store");
            $this->app->put("$route", "resti.controller.$route@update");
            $this->app->delete("$route", "resti.controller.$route@destroy");
        }
    }

    protected function registerModel($model, $route) {
        $this->app->bind("resti.model.$route", function($app) use ($model) {
            return $app->make($model);
        });
    }

    protected function registerRepository($repository, $route) {
        $this->app->singleton("resti.repository.$route", function ($app) use ($repository, $route) {
            $instance = $app->make($repository);
            $instance->setModel($app->make("resti.model.$route"));
            return $instance;
        });
    }

    protected function registerController($controller, $route) {
        $this->app->singleton("resti.controller.$route", function ($app) use ($controller, $route) {
            $instance = $app->make($controller);
            $instance->setRepository($app->make("resti.repository.$route"));
            return $instance;
        });
    }
}
