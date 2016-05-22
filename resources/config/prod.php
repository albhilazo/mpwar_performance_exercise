<?php

date_default_timezone_set('Europe/Madrid');

$app['twig.path'] = array(__DIR__ . '/../templates');
$app['twig.options'] = [];

$app['db.options'] = [
    "driver"    => "pdo_mysql",
    "host"      => '52.30.109.150',
    "user"      => 'mpwar',
    "password"  => 'performance_pass',
    "dbname"    => 'mpwar_performance_blog',
    "charset"   => "utf8"
];
$app['orm.proxies_dir'] = '/tmp/proxies';
$app['orm.auto_generate_proxies'] = true;
$app['orm.em.options'] = [
    "mappings" => [
        [
            "type" => "simple_yml",
            "namespace" => "Performance",
            "path" => __DIR__ . "/../../src/Performance/Infrastructure/Database/mappings",
        ],
    ]
];
$app['redis.options'] = [
    "scheme" => "tcp",
    "host"   => "52.30.109.150",
    "port"   => 6379
];
$app['assets_path'] = 'http://performance.dev/mpwar_performance_exercise/web/assets';
$app['img_path']    = 'https://s3-eu-west-1.amazonaws.com/mpwarperf';
