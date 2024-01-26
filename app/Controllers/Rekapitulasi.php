<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use \Hermawan\DataTables\DataTable;
use App\Models\CrudModel;

class Rekapitulasi extends BaseController
{
    public function cpns()
    {
      return view('rekapitulasi/cpns');
    }

    public function getCpns()
    {
        $db = db_connect();
        $builder = $db->table('tr_usulan_jabatan')
                      ->select('tr_usulan_jabatan.unor_id,
                      ref_unor.nama,
                      tr_usulan_jabatan.jabatan,
                      tr_usulan_jabatan.pendidikan,
                      SUM(tr_usulan_jabatan.bezzeting) AS jumlahbez,
                      SUM(tr_usulan_jabatan.kebutuhan) AS jumlahkeb')
                      ->groupby('tr_usulan_jabatan.unor_id,ref_unor.nama,tr_usulan_jabatan.jabatan,tr_usulan_jabatan.pendidikan')
                      ->join('ref_unor', 'ref_unor.id = tr_usulan_jabatan.unor_id');

        return DataTable::of($builder)->toJson(true);
    }
}
