<?php

namespace Immortal\Foundation\Bootstrap;

use Immortal\Log\Writer;
use Monolog\Logger as Monolog;
use Immortal\Contracts\Foundation\Application;

class ConfigureLogging
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Immortal\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $log = $this->registerLogger($app);

        // If a custom Monolog configurator has been registered for the application
        // we will call that, passing Monolog along. Otherwise, we will grab the
        // the configurations for the log system and use it for configuration.
        if ($app->hasMonologConfigurator()) {
            call_user_func(
                $app->getMonologConfigurator(), $log->getMonolog()
            );
        } else {
            $this->configureHandlers($app, $log);
        }
    }

    /**
     * Register the logger instance in the container.
     *
     * @param  \Immortal\Contracts\Foundation\Application  $app
     * @return \Immortal\Log\Writer
     */
    protected function registerLogger(Application $app)
    {
        $app->instance('log', $log = new Writer(
            new Monolog($app->environment()), $app['events'])
        );

        return $log;
    }

    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Immortal\Contracts\Foundation\Application  $app
     * @param  \Immortal\Log\Writer  $log
     * @return void
     */
    protected function configureHandlers(Application $app, Writer $log)
    {
        $method = 'configure'.ucfirst($app['config']['app.log']).'Handler';

        $this->{$method}($app, $log);
    }

    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Immortal\Contracts\Foundation\Application  $app
     * @param  \Immortal\Log\Writer  $log
     * @return void
     */
    protected function configureSingleHandler(Application $app, Writer $log)
    {
        $log->useFiles(
            $app->storagePath().'/logs/zgutu.log',
            $app->make('config')->get('app.log_level', 'debug')
        );
    }

    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Immortal\Contracts\Foundation\Application  $app
     * @param  \Immortal\Log\Writer  $log
     * @return void
     */
    protected function configureDailyHandler(Application $app, Writer $log)
    {
        $config = $app->make('config');

        $maxFiles = $config->get('app.log_max_files');

        $log->useDailyFiles(
            $app->storagePath().'/logs/zgutu.log', is_null($maxFiles) ? 5 : $maxFiles,
            $config->get('app.log_level', 'debug')
        );
    }

    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Immortal\Contracts\Foundation\Application  $app
     * @param  \Immortal\Log\Writer  $log
     * @return void
     */
    protected function configureSyslogHandler(Application $app, Writer $log)
    {
        $log->useSyslog(
            'zgutu',
            $app->make('config')->get('app.log_level', 'debug')
        );
    }

    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Immortal\Contracts\Foundation\Application  $app
     * @param  \Immortal\Log\Writer  $log
     * @return void
     */
    protected function configureErrorlogHandler(Application $app, Writer $log)
    {
        $log->useErrorLog($app->make('config')->get('app.log_level', 'debug'));
    }
}
