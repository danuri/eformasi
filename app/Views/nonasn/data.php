<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Pemetaan Formasi Non ASN Kemenag</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('pppk/inject/'.encrypt($kode))?>" class="btn btn-warning">Inject Formasi</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('pppk/export/'.encrypt($kode))?>" class="btn btn-success">Export Excel</a></li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-bordered table-striped datatable">
              <thead>
                <tr class="text-center">
                  <th>NIK</th>
                  <th>NAMA</th>
                  <th>DATA LAMA</th>
                  <th>DATA BARU</th>
                  <th>STATUS PEGAWAI</th>
                  <th>STATUS NONASN</th>
                  <th>STATUS</th>
                  <th>MAPPING</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($nonasn as $row) {
                  if($row->jabatan_baru == null){
                    $status = 'Belum Dilengkapi';
                  }else{
                    $status = 'Sudah Dilengkapi';
                  }
                ?>
                  <tr>
                    <td><?= $row->NIK?></td>
                    <td><?= $row->NAMA?></td>
                    <td>
                      Pendidikan: <?= $row->NAMA_PENDIDIKAN?><br>
                      Jabatan: <?= $row->NAMA_JABATAN?><br>
                      Unit: <?= $row->UNOR_NAMA?><br>
                    </td>
                    <td>
                      Pendidikan: <?= $row->pendidikan_baru?><br>
                      Jabatan: <?= $row->jabatan_baru?><br>
                      Unit: <?= $row->unit_penempatan_nama_baru?><br>
                    </td>
                    <td><?= $row->status_nonasn?></td>
                    <td><?= $row->status_pemetaan?></td>
                    <td><?= $status?></td>
                    <td><a href="<?= site_url('pppk/edit/'.encrypt($row->ID))?>" target="_blank">Ubah</a></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>
<?= $this->endSection() ?>
