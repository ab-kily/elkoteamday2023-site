<?php

namespace GT\Site\Component;

class Mapper extends \DB\SQL\Mapper {

    public $primaryKey;
    protected $setDtOnCreate = null;
    protected $setDtOnUpdate = null;

    public static function getById($id) {
        $class = static::class;
        $model = new $class();
        if(!$model->primaryKey) {
            throw new \Exception('No primaryKey defined for model '.static::class);
        }
        $model->load([$model->primaryKey.' = ?',$id]);
        return $model->loaded() ? $model : null;
    }

    public static function findAllByAttrs($attrs,$opts=[]) {
        $class = static::class;
        $model = new $class();
        $str = [];
        $params = [];
        foreach($attrs as $k=>$v) {
            $str[] = $k.' = ?';
            $params[] = $v;
        }
        return $model->find([implode(' AND ',$str),...$params],$opts);
    }

    public static function findByAttrs($attrs,$opts=[]) {
        $opts = array_merge($opts,[
            'limit'=>1,
        ]);
        $items = static::findAllByAttrs($attrs,$opts);
        return count($items) ? $items[0] : null;
    }

    public static function findAll(array $query=[],$params=null) {
        $class = static::class;
        $model = new $class();
        return $model->find($query,$params);
    }

    public static function fromArray(array $data,array|string $searchAttr = null) {
        $class = static::class;
        $model = new $class;
        if(isset($data[$model->primaryKey])) {
            try {
                $model = $class::getById($data[$this->primaryKey]);
            } catch(\Exception $e) {
                Log::error("SQL: ".\Base::instance()->get('DB')->log());
                Log::error($e);
                throw new \Exception('Unable to create '.$class);
            }
        } elseif($searchAttr) {
            if(is_string($searchAttr)) {
                $searchmodel = $class::findByAttrs([$searchAttr=>$data[$searchAttr]]);
            } else {
                $searchmodel = $class::findByAttrs($searchAttr);
            }
            if($searchmodel) $model = $searchmodel;
        }
        $model->setAttributes($data);
        $model->save();
        return $model;
    }

    public function save() {
        if($this->setDtOnCreate && $this->isNew()) {
            $this->{$this->setDtOnCreate} = date('Y-m-d H:i:s');
        }
        if($this->setDtOnUpdate) {
            $this->{$this->setDtOnUpdate} = date('Y-m-d H:i:s');
        }
        return parent::save();
    }

    public function isNew() {
        return !(bool)$this->query;
    }

    public function getAttributes() {
        return array_keys($this->fields);
    }

    public function setAttributes(array $data) {
        foreach($data as $attr=>$val) {
            if($this->exists($attr)) {
                $this->$attr = $val;
            }
        }
    }

    public function toArray($override=[]) {
        $data = [];
        foreach($this->getAttributes() as $attr) {
            $data[$attr] = $override[$attr] ?? $this->$attr;
        }
        return $data;
    }

    public function toJson() {
        return json_encode($this->toArray());
    }
}
