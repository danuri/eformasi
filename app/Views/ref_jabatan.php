<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Referensi Jabatan Pelaksana</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Referensi</a></li>
                        <li class="breadcrumb-item active">Jabatan Pelaksana</li>
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
                  <th>JABATAN</th>
                  <th>PENDIDIKAN</th>
                  <th>DESKRIPSI</th>
                  <th>PNS</th>
                  <th>PPPK</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                foreach ($jabatans as $row) {
                ?>
                  <tr>
                    <td><?= $row->nama_jabatan;?></td>
                    <td><?= $row->pendidikan;?></td>
                    <td><?= $row->deskripsi;?></td>
                    <td><?= $row->cpns;?></td>
                    <td><?= $row->pppk;?></td>
                  </tr>
                  <?php $no++; } ?>
                </tbody>
              </table>
          </div>
        </div>
      </div>
    </div>
</div>
<?= $this->endSection() ?>
