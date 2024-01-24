<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnorStrukturModel;
use App\Models\JabatanModel;
use App\Models\UsulanJabatanModel;
use App\Models\CrudModel;

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

    public function getjabatan($id)
    {
      $jabm = new UsulanJabatanModel;
      $data = (array) $jabm->find($id);

      $data['pends'] = [];

      if($data['pendidikan']){
        $pends = unserialize($data['pendidikan']);
        // $pends = implode(",", $pends);
        $data['pends'] = $pends;
      }


      return $this->response->setJSON($data);
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
      $pendidikan = serialize($this->request->getVar('pendidikan'));

      $cek = $model->where(['unor_id'=>$unorid,'jabatan'=>$jabatan,'pendidikan'=>$pendidikan])->first();

      if($cek){
        return $this->response->setJSON(['message'=>'Terdapat duplikasi']);
      }else{
        $param = [
          'unor_id' => $unorid,
          'jabatan' => $jabatan,
          'pendidikan' => $pendidikan,
          'bezzeting' => $this->request->getVar('bezzeting'),
          'kebutuhan' => $this->request->getVar('kebutuhan'),
          'created_by' => session('kodesatker'),
        ];

        $insert = $model->insert($param);

        return $this->response->setJSON(['message'=>'ok']);
      }

    }

    public function save()
    {
      $model = new UsulanJabatanModel;

      $id = $this->request->getVar('id');
      $unorid = $this->request->getVar('unor_id');
      $jabatan = $this->request->getVar('jabatan');
      $pendidikan = $this->request->getVar('pendidikan');

      // $cek = $model->where(['unor_id'=>$unorid,'jabatan'=>$jabatan])->first();

      // if($cek){
      //   return $this->response->setJSON(['message'=>'Terdapat duplikasi']);
      // }else{
        $param = [
          'jabatan' => $jabatan,
          'pendidikan' => serialize($pendidikan),
          'bezzeting' => $this->request->getVar('bezzeting'),
          'kebutuhan' => $this->request->getVar('kebutuhan')
        ];

        $update = $model->set($param)->where(['id' => $id,'unor_id'=>$unorid])->update();

        session()->setFlashdata('message', 'Data telah diupdate');
        return $this->response->setJSON(['message'=>'ok']);
      // }

    }

    public function rekap()
    {
      $crud = new CrudModel;
      $data['rekap'] = $crud->getRekapCpns();

      return view('cpns_rekapitulasi', $data);
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
