<?php

namespace GT\Site\Component;

class BaseController {

    protected $db;
    protected $f3;
    protected $logger;
    protected $params = [];
    protected $_di;

    public function __construct(\DB\SQL $db, \Base $f3, $params = []) {

        $this->db = $db;
        $this->f3 = $f3;
        $this->params = $params?:[];

        $this->init();

    }

    protected function di($class) {
        return $this->f3->get('CONTAINER')($class);
    }

    protected function init() {
        file_put_contents('/tmp/bot_log.txt',date('Y-m-d H:i:s ').json_encode(json_decode(file_get_contents('php://input')),JSON_PRETTY_PRINT)."\n",FILE_APPEND);
        $this->logger = $this->di(Logger::class);
    }

}
