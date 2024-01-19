<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Rekapitulasi Usulan CPPPK</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                        <li class="breadcrumb-item active">Starter</li>
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
                  <th>NO</th>
                  <th>JABATAN</th>
                  <th>ALOKASI</th>
                  <th>UNIT PENEMPATAN</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                foreach ($rekap as $row) {
                ?>
                  <tr>
                    <td><?= $no;?></td>
                    <td><?= $row->jabatan_baru;?></td>
                    <td><?= $row->jumlah;?></td>
                    <td><?= $row->unit_penempatan_nama_baru;?></td>
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
