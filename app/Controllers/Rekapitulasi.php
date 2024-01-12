<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CrudModel;

class Rekapitulasi extends BaseController
{
    public function index()
    {
      $crud = new CrudModel;
      $data['rekap'] = $crud->getRekap();

      return view('rekapitulasi', $data);
    }
}
