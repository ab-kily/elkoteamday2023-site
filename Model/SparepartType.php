<?php

namespace GT\Site\Model;

use GT\Site\Component\Mapper;

class SparepartType extends Mapper {
    public $primaryKey = 'sparepart_type_id';
    protected $setDtOnCreate = 'dt_created';
    public function __construct() {
        parent::__construct( \Base::instance()->get('DB'), 'SparepartType' );
    }
}
