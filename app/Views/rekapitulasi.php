<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Starter</h4>

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
                  <th rowspan="2">NO</th>
                  <th rowspan="2">NAMA</th>
                  <th rowspan="2">KUALIFIKASI PENDIDIKAN</th>
                  <th colspan="2">ALOKASI</th>
                  <!-- <th rowspan="2">MHPK</th> -->
                  <th rowspan="2">UNIT PENEMPATAN</th>
                </tr>
                <tr>
                  <th>CPNS</th>
                  <th>CPPPK</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                foreach ($rekap as $row) {
                  if($row->kualifikasi_pendidikan){
                    $pends = unserialize($row->kualifikasi_pendidikan);
                    $pends = implode(";", $pends);
                    $pend = $pends;
                  }else{
                    $pend = '';
                  }
                ?>
                  <tr>
                    <td><?= $no;?></td>
                    <td><?= $row->nama_jabatan;?></td>
                    <td><?= strtoupper($pend);?></td>
                    <td><?= $row->usul_pns;?></td>
                    <td><?= $row->usul_pppk;?></td>
                    <!-- <td><?= $row->mhpk;?></td> -->
                    <td><?= strtoupper($row->sat2).' , '.strtoupper($row->sat3).' , '.strtoupper($row->sat4);?></td>
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
