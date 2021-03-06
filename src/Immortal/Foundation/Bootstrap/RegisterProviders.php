<?php

namespace Immortal\Foundation\Bootstrap;

use Immortal\Contracts\Foundation\Application;

class RegisterProviders
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Immortal\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $app->registerConfiguredProviders();
    }
}
