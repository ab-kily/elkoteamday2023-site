<?php

namespace GT\Site\Model;

use GT\Site\Component\Mapper;
use GT\Site\Model\SparepartType;
use GT\Site\Model\SparepartSubtype;

class Sparepart extends Mapper {
    public $primaryKey = 'sparepart_id';
    protected $setDtOnCreate = 'dt_created';
    protected $setDtOnUpdate = 'dt_updated';
    public function __construct() {
        parent::__construct( \Base::instance()->get('DB'), 'Sparepart' );
    }

    public static function fromArray(array $data, array|string $searchAttr = null) {
        foreach($data as $k=>$v) {
            if(in_array($k,['sparepart_type_id'])) {
                if(is_numeric($data[$k])) {
                    $st = SparepartType::getById($data[$k]);
                } else {
                    $st = SparepartType::fromArray([
                        'name'=>$data[$k],
                    ],'name');
                }
                if(!$st) {
                    throw new \Exception('Unable to get or create SparepartType');
                }
                $data[$k] = $st->sparepart_type_id;
            } elseif(in_array($k,['sparepart_subtype_id'])) {
                if(is_numeric($data[$k])) {
                    $ss = SparepartSubtype::getById($data[$k]);
                } else {
                    $ss = SparepartSubtype::fromArray([
                        'name'=>$data[$k],
                    ],'name');
                }
                if(!$ss) {
                    throw new \Exception('Unable to get or create SparepartSubtype');
                }
                $data[$k] = $ss->sparepart_subtype_id;
            }
        }
        return parent::fromArray($data, $searchAttr);
    }

    public function toArray($override=[]) {
        return parent::toArray($override);
    }

    public function setAttributes(array $data) {
        if($data['media'] ?? null) {
            //check if it is a data url
            $f3 = \Base::instance();
            if(strpos($data['media'],'data:') === 0) {
                $mimes = new MimeTypes;

                list($prefix,$encoded) = explode(',',$data['media'],2);
                $mime = substr($prefix,strlen('data:'),strpos($prefix,';')-strlen('data:'));
                $encoded = str_replace(' ','+',$encoded);
                $ext = $mimes->getExtension($mime);
                $upload_dir = $f3->get('UPLOADS');
                $uuid = Uuid::uuid4();
                $fname = $uuid.'.'.$ext;
                $save_path = implode('/',[$upload_dir,$fname]);
                if(file_put_contents($save_path,base64_decode($encoded))) {
                    $data['media'] = $fname;
                    $data['media_mime'] = $mime;
                } else {
                    $data['media'] = '';
                    $data['media_mime'] = '';
                }
            } else {
                $upload_dir = $f3->get('UPLOADS');
                $fname = $data['media'];
                $path = implode('/',[$upload_dir,$fname]);
                if(!file_exists($path)) {
                    $data['media'] = '';
                    $data['media_mime'] = '';
                }
            }
        } else {
            $data['media'] = '';
            $data['media_mime'] = '';
        }
        foreach(['period_start','period_end'] as $attr) {
            if($data[$attr] ?? null) {
                $data[$attr] = date('Y-m-d H:i:s',strtotime($data[$attr]));
            }
        }
        foreach(['community_ids'] as $attr) {
            if($data[$attr] ?? null) {
                if(is_array($data[$attr])) {
                    $data[$attr] = implode(',',$data[$attr]);
                }
            }
        }
        foreach(['description','finish_description'] as $attr) {
            if($data[$attr] ?? null) {
                $config = \HTMLPurifier_Config::createDefault();
                $config->set('HTML.Allowed', 'b,i,u,s,b,a[href],code[class],pre', 'UTF-8');
                $filter = new \HTMLPurifier($config);
                $data[$attr] = preg_replace('/<(p|div)>/im','',$data[$attr]);
                $data[$attr] = preg_replace('/<\/(p|div)>/im',"\n",$data[$attr]);
                $data[$attr] = preg_replace('/<br>/im',"\n",$data[$attr]);
                $data[$attr] = preg_replace('/&nbsp;/im'," ",$data[$attr]);
                $data[$attr] = $filter->purify($data[$attr]);
            }

        }
        return parent::setAttributes($data);
    }
}
