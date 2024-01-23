<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Formasi Non ASN Kemenag - Tambahan</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#" class="btn btn-primary">Tambah Data</a></li>
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
                  <th>DATA</th>
                  <th>AKSI</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($nonasn as $row) {
                  $status = 'Belum Dilengkapi';
                ?>
                  <tr>
                    <td><?= $row->nik?></td>
                    <td><?= $row->nama?></td>
                    <td>
                      Pendidikan: <?= $row->pendidikan?><br>
                      Jabatan: <?= $row->jabatan?><br>
                      Unit: <?= $row->unit_nama?><br>
                    </td>
                    <td><a href="<?= site_url('pppk/tambahan/delete/'.encrypt($row->id))?>" onclick="return confirm('Data akan dihapus?')">Delete</a></td>
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
