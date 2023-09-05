<?php

namespace GT\Site\Component;

use GT\Site\Exception\AppException;

class AccountController extends BaseController {

    protected $account;

    protected function init() {
        $uid = $this->f3->get('PARAMS.uid');
        if(!$uid) {
            $this->f3->error(403,'No account available: '.print_r($this->f3->get('PARAMS'),true));
        }
        if($data = $this->db->exec("
                SELECT * FROM account WHERE uid = ?
            ",$uid)) {
            $this->account = $data[0];
            $this->f3->set('UI',$this->f3->get('APP.views').'/'.$this->account['name'].'/');

            $assets = $this->f3->get('APP.assets').'/'.$uid;
            if(!file_exists($assets)) {
                symlink($this->f3->get('UI').'assets',$assets);
            }
        } else {
            throw new AppException(403,'No account found: ',$this->f3->get('PARAMS'));
        }
    }

}
