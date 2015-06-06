<?php

namespace Pictoporn;


use Silex\Application;
use Silex\ServiceProviderInterface;

class HubtrafficConnectorProvider implements ServiceProviderInterface
{
  public function register(Application $app)
    {
        $app['hubtraffic'] = $app->share(function () use ($app) {
            return new HubtrafficConnector($app);
        });
    }

    public function boot(Application $app)
    {
    }
}
