<?php

namespace App\Models;

use CodeIgniter\Model;

class CrudModel extends Model
{

      protected $db;

      public function __construct()
      {
        $this->db = \Config\Database::connect('default', false);

      }

      public function getRow($table,$where)
      {
        $builder = $this->db->table($table);
        $query = $builder->getWhere($where);

        return $query->getRow();
      }

      public function getResult($table,$where=false)
      {
        $builder = $this->db->table($table);

        if($where){
          $query = $builder->getWhere($where);
        }else{
          $query = $builder->get();
        }

        return $query->getResult();
      }

      public function updateValidasi($nik,$data)
      {
        $builder = $this->db->table('nonasn');
        $builder->set($data);
        $builder->where('NIK', $nik);
        $builder->like('KODE_SATKER', kodekepala(session('kodesatker')), 'after');
        $builder->update();
      }

      public function query($query)
      {
        $query = $this->db->query($query);
        return $query;
      }

      public function getFormasi($satker)
      {
        $query = $this->db->query("SELECT
                                	usulan_formasi.id AS tt_key,
                                	usulan_formasi.parent_id AS tt_parent,
                                	usulan_formasi.nama_jabatan,
                                	usulan_formasi.posisi,
                                	usulan_formasi.abk,
                                	usulan_formasi.id_unit,
                                	usulan_formasi.penempatan,
                                	usulan_formasi.pns,
                                	usulan_formasi.pppk,
                                	usulan_formasi.cpns,
                                	usulan_formasi.riil,
                                	usulan_formasi.usul_pns,
                                	usulan_formasi.usul_pppk,
                                	usulan_formasi.mhpk,
                                	usulan_formasi.kualifikasi_pendidikan,
                                	usulan_formasi.created_by
                                FROM
                                	usulan_formasi
                                WHERE created_by='$satker' ORDER BY id ASC")->getResult();
        return $query;
      }

      public function getRekap()
      {
        $kodesatker = session('kodesatker');
        $query = $this->db->query("SELECT
                                  	a.*,
                                  	b.nama_jabatan AS sat2,
                                  	c.nama_jabatan AS sat3,
                                  	d.nama_jabatan AS sat4
                                  FROM
                                  	usulan_formasi AS a
                                  	LEFT JOIN
                                  	usulan_formasi AS b
                                  	ON
                                  		a.kode_parent = b.kode_satker
                                  	LEFT JOIN
                                  	usulan_formasi AS c
                                  	ON
                                  		b.kode_parent = c.kode_satker
                                  	LEFT JOIN
                                  	usulan_formasi d
                                  	ON
                                  		c.kode_parent = d.kode_satker
                                  WHERE
                                  	a.posisi = '5b' AND
                                  	(a.usul_pppk > 0 OR a.usul_pns > 0) AND a.created_by='$kodesatker'")->getResult();
        return $query;
      }

      public function getKuota()
      {
        $satker = session('idsatker');
        $query = $this->db->query("SELECT a.kelompok, a.jumlah AS kuota,
                                  (SELECT SUM(b.jumlah) FROM formasi b WHERE b.kategori=a.kelompok AND b.idsatker='$satker') formasi
                                  FROM porsi a WHERE a.idsatker='$satker'")->getResult();
        return $query;
      }

      public function getPorsi()
      {
        $satker = session('idsatker');
        $query = $this->db->query("SELECT SUM(a.jumlah) AS kuota,
                                  (SELECT SUM(b.jumlah) FROM formasi b WHERE b.idsatker='$satker') formasi
                                  FROM porsi a WHERE a.idsatker='$satker'")->getRow();
        return $query;
      }

      public function viewdokumen($id)
      {
        $query = $this->db->query("SELECT satker,
                                  (SELECT lampiran FROM tr_dokumen WHERE id_satker=satker.id AND id_dokumen='$id') lampiran
                                  FROM satker
                                  WHERE `level`='1' ORDER BY kodesatker ASC")->getResult();
        return $query;
      }

      public function getAllKuota()
      {
        $satker = session('idsatker');
        $query = $this->db->query("SELECT a.kelompok, SUM(a.jumlah) AS kuota FROM porsi a GROUP BY a.kelompok")->getResult();
        return $query;
      }

      public function getPendidikan()
      {
        $query = $this->db->query("SELECT jabatan, kelompok2 FROM jabatan GROUP BY jabatan, kelompok2")->getResult();
        return $query;
      }

      public function searchPendidikan($search,$jenjang)
      {
        $query = $this->db->query("SELECT pendidikan AS `id`,pendidikan AS `text` FROM pendidikan WHERE (pendidikan LIKE '%$search%' OR id='$search') AND level='$jenjang' LIMIT 0,20")->getResult();
        return $query;
      }

      public function searchRefPendidikan($search,$jenjang)
      {
        $query = $this->db->query("SELECT kode AS `id`,nama AS `text` FROM ref_pendidikan WHERE (nama LIKE '%$search%' OR kode LIKE '%$search%') AND grup_pendidikan='$jenjang' LIMIT 0,50")->getResult();
        return $query;
      }

      public function searchRefJabatan($search)
      {
        $query = $this->db->query("SELECT kode AS `id`,nama AS `text` FROM ref_jabatan WHERE nama LIKE '%$search%' OR kode LIKE '%$search%' LIMIT 0,50")->getResult();
        return $query;
      }

      public function searchRefJenisJabatan($search)
      {
        $query = $this->db->query("SELECT id AS `id`,nama AS `text` FROM ref_jenis_jabatan WHERE nama LIKE '%$search%' OR id LIKE '%$search%' LIMIT 0,50")->getResult();
        return $query;
      }

      public function searchLokasi($search)
      {
        $query = $this->db->query("SELECT id AS `id`, CONCAT(nama,' (',jenis_kabupaten,')') AS `text` FROM ref_lokasi WHERE nama LIKE '%$search%'")->getResult();
        return $query;
      }

      public function sumNonJabatan()
      {
        $query = $this->db->query("SELECT jenis_jabatan_umum as jenis, COUNT(*) AS jumlah FROM
                                  (SELECT
                                  	nonasn.ID,
                                  	ref_jabatan.kode,
                                  	ref_jabatan.nama,
                                  	ref_jabatan.jenis_jabatan_umum_id,
                                  	ref_jabatan.jenis_jabatan_umum
                                  FROM
                                  	nonasn
                                  	INNER JOIN
                                  	ref_jabatan
                                  	ON
                                  		nonasn.KODE_JABATAN = ref_jabatan.kode) a
                                  GROUP BY jenis_jabatan_umum")->getResult();
        return $query;
      }

      public function sumNonSatker()
      {
        $query = $this->db->query("SELECT satker,
                                  (SELECT COUNT(ID) FROM nonasn WHERE ID_PARENT=satker.id) AS jumlah
                                  FROM satker WHERE level='1'")->getResult();
        return $query;
      }

      public function sumNonSubSatker($parent)
      {
        $query = $this->db->query("SELECT satker,
                                  (SELECT COUNT(ID) FROM nonasn WHERE KODE_SATKER=satker.kodesatker) AS jumlah
                                  FROM satker WHERE parent='$parent' GROUP BY satker")->getResult();
        return $query;
      }

      public function editNon($id)
      {
        $kode = kodekepala(session('kodesatker'));
        $query = $this->db->query("SELECT * FROM nonasn WHERE ID = '$id' AND KODE_SATKER LIKE '$kode%'")->getRow();
        return $query;
      }

      public function detailNon($id)
      {
        $kode = kodekepala(session('kodesatker'));
        $query = $this->db->query("SELECT
                                	a.*,
                                	b.nama AS lokasi,
                                	b.jenis_kabupaten,
                                	c.nama AS pendidikan,
                                	d.nama AS pendidikansk,
                                	e.nama AS jenisjabatan
                                FROM
                                	nonasn AS a
                                	INNER JOIN
                                	ref_lokasi AS b
                                	ON
                                		a.ID_TEMPAT_LAHIR = b.id
                                	INNER JOIN
                                	ref_pendidikan AS c
                                	ON
                                		a.KODE_PENDIDIKAN = c.kode
                                	INNER JOIN
                                	ref_pendidikan AS d
                                	ON
                                		a.KODE_PENDIDIKAN_SK = d.kode
                                	INNER JOIN
                                	ref_jenis_jabatan AS e
                                	ON
                                		a.JENIS_PENANDATANGAN = e.id
                                WHERE a.ID = '$id' AND a.KODE_SATKER LIKE '$kode%'")->getRow();
        return $query;
      }

      public function detailNonAll($id)
      {
        $query = $this->db->query("SELECT
                                	a.*,
                                	b.nama AS lokasi,
                                	b.jenis_kabupaten,
                                	c.nama AS pendidikan,
                                	d.nama AS pendidikansk,
                                	e.nama AS jenisjabatan
                                FROM
                                	nonasn AS a
                                	INNER JOIN
                                	ref_lokasi AS b
                                	ON
                                		a.ID_TEMPAT_LAHIR = b.id
                                	INNER JOIN
                                	ref_pendidikan AS c
                                	ON
                                		a.KODE_PENDIDIKAN = c.kode
                                	INNER JOIN
                                	ref_pendidikan AS d
                                	ON
                                		a.KODE_PENDIDIKAN_SK = d.kode
                                	INNER JOIN
                                	ref_jenis_jabatan AS e
                                	ON
                                		a.JENIS_PENANDATANGAN = e.id
                                WHERE a.ID = '$id'")->getRow();
        return $query;
      }

      public function getNon($jumlah)
      {
        $query = $this->db->query("SELECT * FROM nonasn WHERE TOBKN IS NULL AND AKHIR_TMT IS NOT NULL ORDER BY UPDATED_AT ASC LIMIT 0,$jumlah")->getResult();
        return $query;
      }

      public function getNonpilih($parent)
      {
        $query = $this->db->query("SELECT * FROM nonasn WHERE TOBKN IS NULL AND AKHIR_TMT IS NOT NULL AND ID_PARENT='$parent' LIMIT 0,50")->getResult();
        return $query;
      }

      public function getNonNik($nik)
      {
        $query = $this->db->query("SELECT * FROM nonasn WHERE NIK='$nik'")->getResult();
        return $query;
      }

      public function getNonsatker($satker)
      {
        $query = $this->db->query("SELECT * FROM nonasn WHERE TOBKN IS NULL AND AKHIR_TMT IS NOT NULL AND KODE_SATKER='$satker' LIMIT 0,100")->getResult();
        return $query;
      }

      public function getNons()
      {
        $kode = kodekepala(session('kodesatker'));
        $query = $this->db->query("SELECT
                                  	nonasn.*,
                                  	rp.nama AS NAMA_PENDIDIKAN,
                                  	sat.satker AS DIBUAT_OLEH
                                  FROM
                                  	nonasn
                                  	LEFT JOIN
                                  	ref_pendidikan AS rp
                                  	ON
                                  		nonasn.KODE_PENDIDIKAN = rp.kode
                                  	LEFT JOIN
                                  	satker AS sat
                                  	ON
                                  		nonasn.CREATED_BY = sat.admin
                                  WHERE
                                  	nonasn.KODE_SATKER LIKE '$kode%'")->getResult();
        return $query;
      }

      public function getSkttLokasi($kode)
      {
        $query = $this->db->query("SELECT kode_tilok, tilok, kode_lokasi, lokasi, COUNT(nik) jumlah FROM sktt_peserta WHERE kode_satker='$kode' GROUP BY kode_tilok,tilok, kode_lokasi, lokasi ORDER BY kode_lokasi ASC")->getResult();
        return $query;
      }

      public function getSkttTilok($kode)
      {
        $query = $this->db->query("SELECT kode_tilok, tilok, COUNT(nik) jumlah FROM sktt_peserta WHERE kode_lokasi='$kode' GROUP BY kode_tilok,tilok")->getResult();
        return $query;
      }

      public function getSanggahNilai($kode)
      {
        $query = $this->db->query("SELECT * FROM pppk_sanggah_nilai WHERE lokasi_kode='$kode'")->getResult();
        return $query;
      }

      public function getPesertaSktt($kode)
      {
        $query = $this->db->query("SELECT
                                  	sktt_peserta.no_peserta,
                                  	sktt_peserta.nama,
                                  	sktt_peserta.jabatan,
                                  	sktt_peserta.sesi,
                                  	sktt_tilok.tilok,
                                  	sktt_lokasi.lokasi
                                  FROM
                                  	sktt_peserta
                                  	INNER JOIN
                                  	sktt_tilok
                                  	ON
                                  		sktt_peserta.kode_tilok = sktt_tilok.id
                                  	INNER JOIN
                                  	sktt_lokasi
                                  	ON
                                  		sktt_tilok.kode_lokasi = sktt_lokasi.kode
                                    WHERE sktt_peserta.kode_lokasi_jabatan='$kode'
                                      ")->getResult();
        return $query;
      }

      public function nonasnreject()
      {
        $kode = kodekepala(session('kodesatker'));
        $query = $this->db->query("SELECT * FROM nonasn WHERE TOBKN='9' AND KODE_SATKER LIKE '$kode%'")->getResult();
        return $query;
      }

      public function dokumen()
      {
        $kode = session('idsatker');
        $query = $this->db->query("SELECT a.*,
                                  (SELECT lampiran FROM tr_dokumen WHERE id_dokumen=a.id AND id_satker='$kode') lampiran
                                  FROM tm_dokumen a")->getResult();
        return $query;
      }
}
