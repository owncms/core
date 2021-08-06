<?php

namespace Modules\Core\src\FormBuilder;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Collective\Html\FormBuilder as LaravelForm;
use Collective\Html\HtmlBuilder;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;
use Modules\Core\src\FormBuilder\Traits\ValidatesWhenResolved;

class FormBuilderServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
//        $this->commands('Kris\LaravelFormBuilder\Console\FormMakeCommand');

        $this->registerHtmlIfNeeded();
        $this->registerFormIfHeeded();

        $this->mergeConfigFrom(
            __DIR__ . '/config/config.php',
            'form-builder'
        );

        $this->registerFormHelper();

        $this->app->singleton('form-builder', function ($app) {

            return new FormBuilder($app, $app['form-helper'], $app['events']);
        });

        $this->app->alias('form-builder', 'Modules\Core\src\FormBuilder\FormBuilder');

        $this->app->afterResolving(Form::class, function ($object, $app) {
            $request = $app->make('request');

            if (in_array(ValidatesWhenResolved::class, class_uses($object)) && $request->method() !== 'GET') {
                $form = $app->make('form-builder')->setDependenciesAndOptions($object);
                $form->buildForm();
                $form->redirectIfNotValid();
            }
        });
        //TODO
        include_once(__DIR__ . '/Helpers/helpers.php');
    }

    /**
     * Register the form helper.
     *
     * @return void
     */
    protected function registerFormHelper()
    {
        $this->app->singleton('form-helper', function ($app) {
            $configuration = $app['config']->get('form-builder');
            return new FormHelper($app['view'], $app['translator'], $configuration);
        });
        $this->app->alias('form-helper', 'Modules\Core\src\FormBuilder\FormHelper');
    }

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/Resources/views', 'form-builder');

        $form = $this->app['form'];

        $form->macro('customLabel', function ($name, $value, $options = []) use ($form) {
            if (isset($options['for']) && $for = $options['for']) {
                unset($options['for']);
                return $form->label($for, $value, $options);
            }

            return $form->label($name, $value, $options);
        });
    }

    public function provides()
    {
        return ['form-builder'];
    }

    /**
     * Add Laravel Form to container if not already set.
     *
     * @return void
     */
    private function registerFormIfHeeded()
    {
        if (!$this->app->offsetExists('form')) {

            $this->app->singleton('form', function ($app) {

                // LaravelCollective\HtmlBuilder 5.2 is not backward compatible and will throw an exception
                $version = substr(Application::VERSION, 0, 3);
                if (Str::is('5.4', $version)) {
                    $form = new LaravelForm($app['html'], $app['url'], $app['view'], $app['session.store']->token());
                } else if (Str::is('5.0', $version) || Str::is('5.1', $version)) {
                    $form = new LaravelForm($app['html'], $app['url'], $app['session.store']->token());
                } else {
                    $form = new LaravelForm($app['html'], $app['url'], $app['view'], $app['session.store']->token());
                }

                return $form->setSessionStore($app['session.store']);
            });

            if (!$this->aliasExists('Form')) {
                AliasLoader::getInstance()->alias(
                    'Form',
                    'Collective\Html\FormFacade'
                );
            }
        }
    }

    /**
     * Add Laravel Html to container if not already set.
     */
    private function registerHtmlIfNeeded()
    {
        if (!$this->app->offsetExists('html')) {

            $this->app->singleton('html', function ($app) {
                return new HtmlBuilder($app['url'], $app['view']);
            });

            if (!$this->aliasExists('Html')) {

                AliasLoader::getInstance()->alias(
                    'Html',
                    'Collective\Html\HtmlFacade'
                );
            }
        }
    }

    /**
     * Check if an alias already exists in the IOC.
     *
     * @param string $alias
     * @return bool
     */
    private function aliasExists($alias)
    {
        return array_key_exists($alias, AliasLoader::getInstance()->getAliases());
    }
}
