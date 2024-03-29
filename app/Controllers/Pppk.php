<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\NonasnModel;
use App\Models\PendidikanModel;
use App\Models\JabatanModel;
use App\Models\UserModel;
use App\Models\UnorModel;
use App\Models\CrudModel;
use App\Models\NonasnTambahanModel;
use CodeIgniter\Files\File;
use Aws\S3\S3Client;

class Pppk extends BaseController
{
    public function index()
    {
      $muser = new UserModel;
      $user = $muser->where('kode_satker',session('kodesatker'))->first();
      $data['user'] = $user;

      if(!empty($user->lampiran_pppk)){
        $crud = new CrudModel;
        $data['rekap'] = $crud->getRekapPppk();

        return view('nonasn/rekapitulasi', $data);
      }

      // if(date('Ymd') > '20240125'){
      //   $crud = new CrudModel;
      //   $data['rekap'] = $crud->getRekapPppk();
      //
      //   return view('nonasn/rekapitulasi', $data);
      // }

      $model = new NonasnModel;
      if(session('role') == 2){
        $data['satker'] = $model->getCountChild(session('kodesatker'));
      }else{
        $data['satker'] = $model->getCount(session('kodesatker'));
      }

      return view('nonasn/index', $data);
    }

    public function data($kode)
    {
      $kode = decrypt($kode);
      $model = new NonasnModel;
      $data['nonasn'] = $model->where(['KODE_SATKER'=>$kode])->findAll();
      $data['kode'] = $kode;
      return view('nonasn/data', $data);
    }

    public function inject($kode)
    {
      $data['kode'] = $kode;
      return view('nonasn/inject', $data);
    }

    public function edit($id)
    {
      $id = decrypt($id);

      $model = new NonasnModel;
      $data['nonasn'] = $model->find($id);

      // $pend = new PendidikanModel;
      // $data['pendidikan'] = $pend->findAll();

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

    public function statuspegawai($id,$status)
    {
      $id = decrypt($id);

      $model = new NonasnModel;
      $update = $model->update($id,['status_nonasn'=>$status]);

      return redirect()->back()->with('message', 'Status Pegawai diperbaharui');
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
        'UPDATED_BY' => session('nip'),
      ];

      $update = $model->update($id,$param);

      return redirect()->back()->with('message', 'Data telah diupdate');
    }

    public function rekap()
    {
      $crud = new CrudModel;
      $data['rekap'] = $crud->getRekapPppk();

      $muser = new UserModel;
      $data['user'] = $muser->where('kode_satker',session('kodesatker'))->first();

      return view('nonasn/rekapitulasi', $data);
    }

    public function import($id)
		{
			$file_excel = $this->request->getFile('lampiran');
			$ext = $file_excel->getClientExtension();
			if($ext == 'xls') {
				$render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
			} else {
				$render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}
			$spreadsheet = $render->load($file_excel);

			$data = $spreadsheet->getActiveSheet()->toArray();
			foreach($data as $x => $row) {
				if ($x == 0) {
					continue;
				}

				$id = $row[0];
				$nik = $row[1];
				$pendidikan = $row[2];
				$jabatan = $row[3];
				$unor = $row[4];


        $munor = new UnorModel;
        $namaunor = $munor->find($unor)->nama;

        $param = [
          'status_nonasn' => 'NON ASN',
          'status_pemetaan' => 'Aktif',
          'pendidikan_baru' => $pendidikan,
          'jabatan_baru' => $jabatan,
          'unit_penempatan_baru' => $unor,
          'unit_penempatan_nama_baru' => $namaunor,
          'UPDATED_BY' => session('nip'),
        ];

        $model = new NonasnModel;
        $update = $model->where(['ID'=>$id,'NIK'=>$nik])->set($param)->update();
			}

			return redirect()->back()->with('message', 'Data telah diimport');
		}

    public function importx($id)
    {
        $validationRule = [
            'lampiran' => [
                'label' => 'File Import',
                'rules' => [
                    'uploaded[lampiran]',
                    'mime_in[lampiran,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet]'
                ],
            ],
        ];
        if (! $this->validate($validationRule)) {
            $data = ['errors' => $this->validator->getErrors()];

            return view('upload_form', $data);
        }

        $img = $this->request->getFile('lampiran');

        if (! $img->hasMoved()) {
            $filepath = WRITEPATH . 'uploads/' . $img->store();

            // $data = ['uploaded_fileinfo' => new File($filepath)];
            $data = new File($filepath);
            // print_r($data);
            $filename = $data->getBasename();

            $inputFileName = './uploads/'.date('Ymd').'/'.$filename;
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        }

        // $data = ['errors' => 'The file has already been moved.'];
        //
        // return view('upload_form', $data);
    }

    public function export($kode)
    {
      if($kode == 0){
        $model = new NonasnModel;
        $nonasn = $model->where(['KODE_SATKER_PARENT'=>session('kodesatker')])->findAll();
      }else{
        $kode = decrypt($kode);
        $model = new NonasnModel;
        $nonasn = $model->where(['KODE_SATKER'=>$kode])->findAll();
      }

      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      $sheet->setCellValue('A1', 'ID');
      $sheet->setCellValue('B1', 'NIK');
      $sheet->setCellValue('C1', 'NAMA');
      $sheet->setCellValue('D1', 'STATUS_PEGAWAI');
      $sheet->setCellValue('E1', 'PENDIDIKAN_LAMA');
      $sheet->setCellValue('F1', 'JABATAN_LAMA');
      $sheet->setCellValue('G1', 'UNIT_LAMA');
      $sheet->setCellValue('H1', 'PENDIDIKAN_BARU');
      $sheet->setCellValue('I1', 'JABATAN_BARU');
      $sheet->setCellValue('J1', 'UNIT_BARU');

      $i = 2;
      foreach ($nonasn as $row) {
        $sheet->setCellValue('A'.$i, $row->ID);
        $sheet->getCell('B'.$i)->setValueExplicit($row->NIK,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);
        $sheet->setCellValue('C'.$i, $row->NAMA);
        $sheet->setCellValue('D'.$i, $row->status_nonasn);
        $sheet->setCellValue('E'.$i, $row->NAMA_PENDIDIKAN);
        $sheet->setCellValue('F'.$i, $row->NAMA_JABATAN);
        $sheet->setCellValue('G'.$i, $row->UNOR_NAMA);
        $sheet->setCellValue('H'.$i, $row->pendidikan_baru);
        $sheet->setCellValue('I'.$i, $row->jabatan_baru);
        $sheet->setCellValue('J'.$i, $row->unit_penempatan_nama_baru);

        $i++;
      }

      $writer = new Xlsx($spreadsheet);
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="Data_nonasn_'.$kode.'.xlsx"');
      $writer->save('php://output');
      exit();
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

    $file_name = 'sptjm.cpppk.'.session('kodesatker').'.'.$ext;
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
        'lampiran_pppk' => $file_name
      ];

    $update = $up->where(['kode_satker_parent'=>session('kodesatker')])->set($data)->update();

    session()->setFlashdata('message', 'Dokumen telah diunggah');
    return redirect()->back();
    }

    public function tambahan()
    {
      $muser = new UserModel;
      $user = $muser->where('kode_satker',session('kodesatker'))->first();
      $data['user'] = $user;

      if(!empty($user->lampiran_pppk_tambahan)){
        $crud = new CrudModel;
        $data['rekap'] = $crud->getRekapPppkTambahan();

        return view('nonasn/tambahan_rekapitulasi', $data);
      }

      if(date('Ymd') > '20240125'){
        $crud = new CrudModel;
        $data['rekap'] = $crud->getRekapPppkTambahan();

        return view('nonasn/tambahan_rekapitulasi', $data);
      }

      $model = new NonasnTambahanModel;
      $kodesatker = kodekepala(session('kodesatker'));
      $data['nonasn'] = $model->like('kode_satker', $kodesatker, 'after')->findAll();

      $jabm = new JabatanModel;
      $data['jabatan'] = $jabm->findAll();

      return view('nonasn/tambahan', $data);
    }

    public function tambahansave()
    {
      if (! $this->validate([
            'nik'  => 'required',
            'nama'  => 'required',
            'pendidikan'  => 'required',
            'jabatan'  => 'required',
            'unor'  => 'required'
        ])) {

          return redirect()->back()->with('message', 'Data harus lengkap');
        }

      $munor = new UnorModel;
      $unorid = $this->request->getVar('unor');
      $namaunor = $munor->find($unorid)->nama;

      $param = [
        'nik' => $this->request->getVar('nik'),
        'nama' => $this->request->getVar('nama'),
        'pendidikan' => $this->request->getVar('pendidikan'),
        'jabatan' => $this->request->getVar('jabatan'),
        'unit_id' => $this->request->getVar('unor'),
        'unit_nama' => $namaunor,
        'kode_satker' => session('kodesatker'),
        'created_by' => session('nip'),
      ];

      $model = new NonasnTambahanModel;

      $insert = $model->insert($param);

      return redirect()->back()->with('message', 'Data telah ditambahkan');
    }

    public function tambahandelete($id)
    {
      $id = decrypt($id);

      $model = new NonasnTambahanModel;

      $delete = $model->delete($id);

      return redirect()->back()->with('message', 'Data telah dihapus');
    }

    public function tambahanrekap()
    {
      $crud = new CrudModel;
      $data['rekap'] = $crud->getRekapPppkTambahan();

      $muser = new UserModel;
      $data['user'] = $muser->where('kode_satker',session('kodesatker'))->first();

      return view('nonasn/tambahan_rekapitulasi', $data);
    }

    public function tambahanfinal()
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

    $file_name = 'sptjm.cpppktambahan.'.session('kodesatker').'.'.$ext;
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
        'lampiran_pppk_tambahan' => $file_name
      ];

    $update = $up->where(['kode_satker_parent'=>session('kodesatker')])->set($data)->update();

    session()->setFlashdata('message', 'Dokumen telah diunggah');
    return redirect()->back();
    }
}
