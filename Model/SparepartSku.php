<?php

namespace GT\Site\Model;

use GT\Site\Component\Mapper;

class SparepartSku extends Mapper {
    public $primaryKey = 'sparepart_sku_id';
    protected $setDtOnCreate = 'dt_created';
    public function __construct() {
        parent::__construct( \Base::instance()->get('DB'), 'SparepartSku' );
    }
}
