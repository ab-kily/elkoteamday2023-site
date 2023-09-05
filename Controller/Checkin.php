<?php

namespace GT\Site\Controller;

use GT\Site\Component\BaseController;

class Checkin extends BaseController {

	public function index() {

		if(!$data = $this->f3->get('POST.data')) {
			return print(json_encode(['status'=>false]));
		}  else {
            $list = $this->db->exec("
                INSERT INTO Checkin(lastname, firstname, position, email, phone, office, transfer_to, transfer_from, dt_added)
                VALUES (:lastname, :firstname, :position, :email, :phone, :office, :transfer_to, :transfer_from, :date)
            ",array_merge($data,['date'=>date('Y-m-d H:i:s')]));

			return print(json_encode(['status'=>true,'data'=>$list]));
		}
	}
}
