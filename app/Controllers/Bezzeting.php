<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BezzetingModel;
use App\Models\PendidikanModel;
use App\Models\JabatanModel;
use App\Models\CrudModel;
use App\Models\SatkerModel;

class Bezzeting extends BaseController
{
    public function index()
    {
        $model = new BezzetingModel;
        $data['formasi'] = $model->where(['posisi'=>'1a','created_by'=>session('kodesatker')])->findAll();
        $pend = new PendidikanModel;
        $data['pendidikan'] = $pend->findAll();
        $jabm = new JabatanModel;
        $data['jabatan'] = $jabm->findAll();
        return view('bezzeting', $data);
    }

    public function getUnorChild($id)
    {
      $model = new SatkerModel;
      $child = $model->where('kode_atasan',$id)->findAll();

      foreach ($child as $row) {
        echo '<option value="'.$row->kode_satuan_kerja.'">'.$row->satuan_kerja.'</option>';
      }
    }

    public function insert()
    {
      $model = new BezzetingModel;

      $data = array(
                'kode_satker' => $this->request->getVar('kode_satker'),
                'kode_parent' => $this->request->getVar('kode_parent'),
                'nama_jabatan' => $this->request->getVar('nama_jabatan'),
                // 'posisi' => $this->request->getVar('posisi'),
                'created_by' => session('kodesatker'),
              );
      $insert = $model->insert($data);
      session()->setFlashdata('message', 'Data berhasil ditambahkan');

      return $this->response->setJSON(['message'=>'ok']);
    }

    public function dxuf()
    {

      $model = new BezzetingModel;
      // if($this->request->getVar('headId')){
      //   $data = $model->where(['parent_id'=>$this->request->getVar('headId')])->findAll();
      // }else{
      // }
      $data = $model->where(['created_by'=>session('kodesatker')])->findAll();
      // $data = $model->getFormasi(session('kodesatker'));
      $d = [];
      $i = 0;
      foreach ($data as $row) {

        if($row->parent_id == '1'){
            $jabatan = strtoupper($row->nama_jabatan).' <a href="javascript:;" class="badge text-bg-primary" onclick="addchild(\''.$row->kode_satker.'\')" title="Tambah Organisasi"><i class="bx bx-plus-circle"></i></a> <a href="javascript:;" class="badge text-bg-secondary" onclick="addchild2(\''.$row->kode_satker.'\')" title="Tambah Fungsional"><i class="bx bx-plus-circle"></i></a>';
        }else if($row->posisi == '5b'){
            // $jabatan = strtoupper($row->nama_jabatan).' <a href="javascript:;" class="badge text-bg-warning btnaddpendidikan" data-id="'.$row->id.'" onclick="addpendidikan(\''.encrypt($row->id).'\')" title="Kualifikasi Pendidikan"><i class="bx bx-book-bookmark"></i></a> <a href="'.site_url('bezzeting/delete/'.encrypt($row->id)).'" class="badge text-bg-danger" onclick="return confirm(\'Data akan dihapus?\')" title="Jumlah"><i class="bx bx-trash"></i></a>';
            $jabatan = strtoupper($row->nama_jabatan).' <a href="javascript:;" class="badge text-bg-warning" onclick="alert(\'Silakan klik kanan pada Nama Jabatan\')" title="Kualifikasi Pendidikan"><i class="bx bx-book-bookmark"></i></a> <a href="'.site_url('bezzeting/delete/'.encrypt($row->kode_satker)).'" class="badge text-bg-danger" onclick="return confirm(\'Data akan dihapus?\')" title="Jumlah"><i class="bx bx-trash"></i></a>';
        }else{
            $jabatan = strtoupper($row->nama_jabatan).' <a href="javascript:;" class="badge text-bg-primary" onclick="addchild(\''.$row->kode_satker.'\')" title="Tambah Organisasi"><i class="bx bx-plus-circle"></i></a> <a href="javascript:;" class="badge text-bg-secondary" onclick="addchild2(\''.$row->kode_satker.'\')" title="Tambah Fungsional"><i class="bx bx-plus-circle"></i></a> <a href="'.site_url('bezzeting/delete/'.encrypt($row->kode_satker)).'" class="badge text-bg-danger" onclick="return confirm(\'Jika Struktur dihapus, maka semua data dibawahnya akan ikut terhapus. Anda yakin?\')" title="Hapus"><i class="bx bx-trash"></i></a>';
        }

        $d[$i] = [
              'headId' => $row->kode_satker,
              'Id' => $row->id,
              'induk' => $row->kode_parent,
              'nama_jabatan' => $jabatan,
              'posisi' => $row->posisi,
              'abk' => $row->abk,
              'id_unit' => $row->id_unit,
              'penempatan' => $row->penempatan,
              'pns' => $row->pns,
              'pppk' => $row->pppk,
              'cpns' => $row->cpns,
              'riil' => $row->riil,
              'usul_pns' => $row->usul_pns,
              'usul_pppk' => $row->usul_pppk,
              'mhpk' => $row->mhpk,
              'kualifikasi_pendidikan' => $row->kualifikasi_pendidikan,
            ];

            $i++;
      }
      $json = array('value'=>$d);
      return $this->response->setJSON($json);
    }

    public function delete($id)
    {
      $id = decrypt($id);

      $model = new BezzetingModel;
      $delete = $model->delete($id);
      session()->setFlashdata('message', 'Data berhasil dihapus');
      return redirect()->back();
    }

    public function deletex($id)
    {
      // $id = decrypt($id);

      $model = new BezzetingModel;
      $delete = $model->where(['id'=>$id,'created_by'=>session('kodesatker')])->delete();
      session()->setFlashdata('message', 'Data berhasil dihapus');
      return $this->response->setJSON(['message'=>'ok']);
    }

    public function getdata($id)
    {
      // $id = decrypt($id);

      $model = new BezzetingModel;
      $data = (array) $model->find($id);

      if($data['kualifikasi_pendidikan']){
        $pends = unserialize($data['kualifikasi_pendidikan']);
        $pends = implode(",", $pends);
        $data['pends'] = $pends;
      }

      return $this->response->setJSON($data);
    }

    public function savependidikan()
    {
      $model = new BezzetingModel;
      $data = [
        'abk' => $this->request->getVar('abk'),
        'pns' => $this->request->getVar('pns'),
        'cpns' => $this->request->getVar('cpns'),
        'pppk' => $this->request->getVar('pppk'),
        'usul_pns' => $this->request->getVar('usul_pns'),
        'usul_pppk' => $this->request->getVar('usul_pppk'),
        'mhpk' => $this->request->getVar('mhpk'),
        'kualifikasi_pendidikan' => serialize($this->request->getVar('pendidikan')),
      ];
      $model->set($data)->where(['id'=>$this->request->getVar('idpend'),'created_by'=>session('kodesatker')])->update();

      session()->setFlashdata('message', 'Data berhasil disimpan');

      return $this->response->setJSON(['message'=>'ok']);
    }
}
