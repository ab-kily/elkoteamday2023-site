<?php

namespace GT\Site;

if (preg_match('/^\/amdiern.php/', $_SERVER["REQUEST_URI"])) {
    return false;
}
if (preg_match('/^\/runtime\/uploads\//', $_SERVER["REQUEST_URI"])) {
    return false;
}

require __DIR__.'/vendor/autoload.php';

use M1\Env\Parser as EnvParser;

$f3 = \Base::instance();

$env = [];
if(file_exists($envpath = __DIR__.'/.env')) {
    $env = (new EnvParser(file_get_contents($envpath)))->getContent();
}
if(file_exists($configPath = __DIR__.'/config/main.php')) {
    require $configPath;
}
if(!file_exists($assetsPath = __DIR__.'/assets')) {
    @mkdir(__DIR__.'/assets');
}

$f3->run();
