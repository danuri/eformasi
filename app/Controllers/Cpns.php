<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnorStrukturModel;
use App\Models\JabatanModel;
use App\Models\UsulanJabatanModel;
use App\Models\CrudModel;
use App\Models\UserModel;
use Aws\S3\S3Client;

class Cpns extends BaseController
{
    public function index()
    {
        $muser = new UserModel;
        $user = $muser->where('kode_satker',session('kodesatker'))->first();
        $data['user'] = $user;

        if(!empty($user->lampiran_cpns)){
          $crud = new CrudModel;
          $data['rekap'] = $crud->getRekapCpns();

          return view('cpns_rekapitulasi', $data);
        }

        if(date('Ymd') > '20240125'){
          $crud = new CrudModel;
          $data['rekap'] = $crud->getRekapCpns();


          return view('cpns_rekapitulasi', $data);
        }

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

      $muser = new UserModel;
      $data['user'] = $muser->where('nip',session('nip'))->first();

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

    public function final()
    {
        $validationRule = [
          'lampiran' => [
              'label' => 'Lampiran',
              'rules' => 'uploaded[lampiran]'
                  . '|ext_in[lampiran,pdf,PDF]'
          ],
      ];

    if (! $this->validate($validationRule)) {
          session()->setFlashdata('message', $this->validator->getErrors()['lampiran']);
          return redirect()->back();
    }

    $file_name = $_FILES['lampiran']['name'];
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);

    $file_name = 'sptjm.cpns.'.session('kodesatker').'.'.$ext;
    $temp_file_location = $_FILES['lampiran']['tmp_name'];

    $s3 = new S3Client([
      'region'  => 'us-east-1',
      'endpoint' => 'https://docu.kemenag.go.id:9000/',
      'use_path_style_endpoint' => true,
      'version' => 'latest',
      'credentials' => [
        'key'    => "118ZEXFCFS0ICPCOLIEJ",
        'secret' => "9xR+TBkYyzw13guLqN7TLvxhfuOHSW++g7NCEdgP",
      ],
      'http'    => [
          'verify' => false
      ]
    ]);

    $result = $s3->putObject([
      'Bucket' => 'sscasn',
      'Key'    => '2024/eformasi/'.$file_name,
      'SourceFile' => $temp_file_location,
      'ContentType' => 'application/pdf'
    ]);

    $up = new UserModel;
      $data = [
        'lampiran_cpns' => $file_name
      ];

    $update = $up->where(['kode_satker_parent'=>session('kodesatker')])->set($data)->update();

    session()->setFlashdata('message', 'Dokumen telah diunggah');
    return redirect()->back();
    }
}
