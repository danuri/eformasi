<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
use App\Models\SatkerModel;

class Users extends BaseController
{
    public function index()
    {
      $model = new SatkerModel;
      $data['satker'] = $model->where('kode_atasan',session('kodesatker'))->findAll();

      $model = new UserModel;
      $data['users'] = $model->where('kode_satker_parent',session('kodesatker'))->findAll();
      return view('users/index', $data);
    }

    public function add()
    {

      if (! $this->validate([
          'nip' => "required|is_unique[users.nip]",
          'nama' => "required"
        ])) {
            return $this->response->setJSON(['message'=>'Pastikan data sesuai']);
        }

      $model = new UserModel;

      $param = [
        'nip' => $this->request->getVar('nip'),
        'nama' => $this->request->getVar('nama'),
        'satuan_kerja' => $this->request->getVar('satuan_kerja'),
        'kode_satker' => decrypt($this->request->getVar('kode_satker')),
        'kode_satker_parent' => session('kodesatker'),
        'role' => 2,
        'unor_id' => 9,
        'created_by' => session('nip'),
      ];

      $insert = $model->insert($param);

      return $this->response->setJSON(['message'=>'ok']);
    }
}
