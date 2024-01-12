<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Formasi extends BaseController
{
    public function index()
    {
        //
    }

    public function dxuf()
    {
      // $data =
      $json = array('value'=>$data);
      return $this->response->setJSON($json);
    }
}
