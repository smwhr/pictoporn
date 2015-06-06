<?php
use Silex\Application;
use Silex\Provider;
use Monolog\Handler\ChromePHPHandler;
use Monolog\Handler\RotatingFileHandler;
use User\UserProvider;
use Silex\Provider\FormServiceProvider;

$app            = new Silex\Application();
$app['debug']   = $config['debug'];
$app['config']  = $config;

$app->register(new Provider\DoctrineServiceProvider(), array('db.options' => $config['db.options']));
$app->register(new Alpha\ServiceProvider());


$app->register(new Pictoporn\HubtrafficConnectorProvider());


$app->register(new Silex\Provider\TwigServiceProvider(), array(
  // 'twig.options' => array('cache' => __DIR__.'/../cache', 'strict_variables' => true),
  'twig.options' => array('strict_variables' => true),
  'twig.path' => __DIR__.'/../views',
));