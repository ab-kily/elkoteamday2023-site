<?php

namespace GT\Site;

use Psr\Container\ContainerInterface;
use GT\Site\Component\Logger;

/** @var \Base $f3 */

$builder = new \DI\ContainerBuilder();
//$builder->enableCompilation(realpath(__DIR__ . '/../tmp'));
$builder->writeProxiesToFile(true, realpath(__DIR__ . '/../tmp/proxies'));
$builder->addDefinitions([
    \DB\SQL::class => function(ContainerInterface $c) use($f3){
        return $f3->get('DB');
    },
    \Base::class => function(ContainerInterface $c) use($f3){
        return $f3;
    },
    \GT\Site\Component\Logger::class => function(ContainerInterface $c) use($f3) {
        return Logger::getInstance();
    },
]);
$di = $builder->build();
$f3->set('CONTAINER', function($class) use($di){
    return $di->get($class);
});
