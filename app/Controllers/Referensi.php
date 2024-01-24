<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\JabatanModel;

class Referensi extends BaseController
{
    public function jabatanpelaksana()
    {
        $model = new JabatanModel;
        $data['jabatans'] = $model->where(['group'=>'pelaksana'])->findAll();

        return view('ref_jabatan', $data);
    }
}
