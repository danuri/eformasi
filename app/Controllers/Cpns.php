<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnorStrukturModel;
use App\Models\JabatanModel;
use App\Models\UsulanJabatanModel;

class Cpns extends BaseController
{
    public function index()
    {
        $jabm = new JabatanModel;
        $data['jabatan'] = $jabm->findAll();

        $model = new UnorStrukturModel;
        $data['unor'] = $model->where('id',session('unorid'))->orWhere('atasan_id',session('unorid'))->orderBy('corder', 'ASC')->findAll();
        return view('cpns', $data);
    }

    public function sub($id)
    {
        $jabm = new JabatanModel;
        $data['jabatan'] = $jabm->findAll();

        $model = new UnorStrukturModel;
        $data['unor'] = $model->where('atasan_id',$id)->orderBy('corder', 'ASC')->findAll();
        return view('cpns', $data);
    }

    public function insert()
    {
      $model = new UsulanJabatanModel;

      $unorid = $this->request->getVar('unor_id');
      $jabatan = $this->request->getVar('jabatan');

      $cek = $model->where(['unor_id'=>$unorid,'jabatan'=>$jabatan])->first();

      if($cek){
        return $this->response->setJSON(['message'=>'Terdapat duplikasi']);
      }else{
        $param = [
          'unor_id' => $unorid,
          'jabatan' => $jabatan,
          'bezzeting' => $this->request->getVar('bezzeting'),
          'kebutuhan' => $this->request->getVar('kebutuhan'),
        ];

        $insert = $model->insert($param);

        return $this->response->setJSON(['message'=>'ok']);
      }

    }

    public function delete($id)
    {
      $id = decrypt($id);

      $model = new UsulanJabatanModel;
      $delete = $model->delete($id);
      session()->setFlashdata('message', 'Data berhasil dihapus');
      return redirect()->back();
    }

    public function dxuf()
    {
      // $data =
      $json = array('value'=>$data);
      return $this->response->setJSON($json);
    }
}