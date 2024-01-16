<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NonasnModel;
use App\Models\PendidikanModel;
use App\Models\JabatanModel;
use App\Models\UnorModel;

class Pppk extends BaseController
{
    public function index()
    {
      $model = new NonasnModel;
      $data['satker'] = $model->getCount(session('kodesatker'));
      return view('nonasn/index', $data);
    }

    public function data($kode)
    {
      $kode = decrypt($kode);
      $model = new NonasnModel;
      $data['nonasn'] = $model->where(['KODE_SATKER'=>$kode,'status_nonasn'=>'NON ASN'])->findAll();
      return view('nonasn/data', $data);
    }

    public function edit($id)
    {
      $id = decrypt($id);

      $model = new NonasnModel;
      $data['nonasn'] = $model->find($id);

      $pend = new PendidikanModel;
      $data['pendidikan'] = $pend->findAll();

      $jabm = new JabatanModel;
      $data['jabatan'] = $jabm->findAll();

      return view('nonasn/edit', $data);
    }

    public function searchunor()
    {
      $model = new UnorModel;
      $search = $this->request->getVar('search');

      $data = $model->like('nama', $search, 'both')->findAll();
      return $this->response->setJSON($data);
    }

    public function searchpendidikan()
    {
      $model = new PendidikanModel;
      $search = $this->request->getVar('search');

      $data = $model->like('nama', $search, 'both')->findAll();
      return $this->response->setJSON($data);
    }

    public function status($id,$status)
    {
      $id = decrypt($id);

      $model = new NonasnModel;
      $update = $model->update($id,['status_pemetaan'=>$status]);

      return redirect()->back()->with('message', 'Status diperbaharui');
    }

    public function editsave($id)
    {
      if (! $this->validate([
            'pendidikan'  => 'required',
            'jabatan'  => 'required',
            'unor'  => 'required'
        ])) {

          return redirect()->back()->with('message', 'Data harus lengkap');
        }

      $model = new NonasnModel;

      $munor = new UnorModel;
      $unorid = $this->request->getVar('unor');
      $namaunor = $munor->find($unorid)->nama;

      $id = decrypt($id);
      $param = [
        'pendidikan_baru' => $this->request->getVar('pendidikan'),
        'jabatan_baru' => $this->request->getVar('jabatan'),
        'unit_penempatan_baru' => $unorid,
        'unit_penempatan_nama_baru' => $namaunor,
      ];

      $update = $model->update($id,$param);

      return redirect()->back()->with('message', 'Data telah diupdate');
    }
}
