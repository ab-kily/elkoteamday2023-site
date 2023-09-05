<?php

namespace GT\Site\Controller;

use GT\Site\Component\BaseController;

class Index extends BaseController {

    public function index() {
        echo \View::instance()->render('index.html','text/html',[]);
    }
}
