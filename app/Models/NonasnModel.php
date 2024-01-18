<?php

namespace App\Models;

use CodeIgniter\Model;

class NonasnModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'nonasn';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['status_pemetaan','pendidikan_baru','jabatan_baru','unit_penempatan_baru','unit_penempatan_nama_baru','status_nonasn'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getCount($parent)
    {
      $this->db = \Config\Database::connect('default', false);

      $query = $this->db->query("SELECT KODE_SATKER,SATKER, COUNT(ID) AS JUMLAH FROM nonasn
                                  WHERE KODE_SATKER_PARENT='$parent' GROUP BY KODE_SATKER,SATKER")->getResult();
      return $query;
    }
}
