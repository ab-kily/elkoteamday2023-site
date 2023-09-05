<?php

namespace GT\Site;

$f3->set('UI',realpath(__DIR__.'/../views').'/');
$f3->set('DEBUG','3');
$f3->set('UPLOADS',dirname(__DIR__).'/runtime/uploads');

$f3->set('APP.base_url',strpos(__DIR__,'bagger') !== false ? 'https://kilylabs.pagekite.me' : 'https://tcl.forppl.ru');
$f3->set('APP.webroot',dirname(__DIR__));
$f3->set('APP.runtime',dirname(__DIR__).'/runtime');
$f3->set('APP.views',dirname(__DIR__).'/views');
$f3->set('APP.assets',dirname(__DIR__).'/assets');

$f3->set('APP.update_user','gts');
$f3->set('APP.update_pass','Popajit+71');

if(file_exists($dbPath = __DIR__.'/db.php')) {
    require $dbPath;
}
if(file_exists($corsPath = __DIR__.'/cors.php')) {
    require $corsPath;
}
if(file_exists($routesPath = __DIR__.'/routes.php')) {
    require $routesPath;
}
if(file_exists($diPath = __DIR__.'/di.php')) {
    require $diPath;
}
