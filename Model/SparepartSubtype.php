<?php

namespace GT\Site\Model;

use GT\Site\Component\Mapper;

class SparepartSubtype extends Mapper {
    public $primaryKey = 'sparepart_subtype_id';
    protected $setDtOnCreate = 'dt_created';
    public function __construct() {
        parent::__construct( \Base::instance()->get('DB'), 'SparepartSubtype' );
    }
}
