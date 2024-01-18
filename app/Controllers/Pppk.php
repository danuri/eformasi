<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
      $data['nonasn'] = $model->where(['KODE_SATKER'=>$kode])->findAll();
      $data['kode'] = $kode;
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
      ];

      $update = $model->update($id,$param);

      return redirect()->back()->with('message', 'Data telah diupdate');
    }

    public function export($kode)
    {
      $kode = decrypt($kode);
      $model = new NonasnModel;
      $nonasn = $model->where(['KODE_SATKER'=>$kode,'status_nonasn'=>'NON ASN'])->findAll();

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
}
