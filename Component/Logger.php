<?php

namespace GT\Site\Component;

use Monolog\Logger as MonoLogger;
use Monolog\Handler\StreamHandler;

class Logger {

    protected static $instance;

    public static function getInstance() {
        if(!self::$instance) {
            $f3 = \Base::instance();
            $log = new MonoLogger('tclbot');
            $log->pushHandler(new StreamHandler($f3->get('APP.runtime').'/log/server.log', MonoLogger::DEBUG));

            self::$instance = $log;
        }
        return self::$instance;
    }

    public static function debug($msg) {
        self::getInstance()->debug($msg);
    }

    public static function info($msg) {
        self::getInstance()->info($msg);
    }

    public static function notice($msg) {
        self::getInstance()->notice($msg);
    }

    public static function warning($msg) {
        self::getInstance()->warning($msg);
    }

    public static function error($msg) {
        self::getInstance()->error($msg);
    }

    public static function critical($msg) {
        self::getInstance()->critical($msg);
    }

    public static function alert($msg) {
        self::getInstance()->alert($msg);
    }

    public static function emergency($msg) {
        self::getInstance()->emergency($msg);
    }

}

