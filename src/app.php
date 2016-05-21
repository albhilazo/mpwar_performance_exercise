<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;

$app = new Application();

$app->register(new ValidatorServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new DoctrineServiceProvider);
$app->register(new DoctrineOrmServiceProvider);
$app->register(new Performance\DomainServiceProvider());
$app->register(new HttpCacheServiceProvider(), [
    'http_cache.esi'       => null,
    'http_cache.options'   => [
        'debug'                  => false,
        'default_ttl'            => 20,
        'private_headers'        => ['Authorization', 'Cookie'],
        'allow_reload'           => false,
        'allow_revalidate'       => false,
        'stale_while_revalidate' => 2,
        'stale_if_error'         => 60
    ]
]);

return $app;
