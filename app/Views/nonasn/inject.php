<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Pemetaan Formasi Non ASN Kemenag</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('pppk/data/'.encrypt($kode))?>" class="btn btn-success">Kembali</a></li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <ul>
              <li>Silahkan Download template untuk inject data > <a href="<?= base_url('assets/template_pppk.xlsx')?>">download</a></li>
              <li>Lihat referensi Unor, Jabatan dan Pendidikan > <a href="https://docs.google.com/spreadsheets/d/1X25QIU077oHM4fLIe0bQ9sjOudcScrJkbqm85CWcfMo/edit?usp=sharing" target="_blank">Lihat Referensi</a></li>
              <li>Data yang diinject terbatas pada pemilihan Unor sebelumnya</li>
              <li>Maksimal 100 baris</li>
            </ul>
          </div>
        </div>

        <div class="card card-body">
          <form action="" method="post" enctype="multipart/form-data">
              <div class="row row-cols-lg-auto g-3 align-items-center">
                  <div class="col-12">
                      <input type="file" class="form-control" id="lampiran" placeholder="File" name="lampiran">
                  </div>
                  <div class="col-12">
                      <button type="submit" class="btn btn-primary">Mulai Import</button>
                  </div>
              </div>
          </form>
        </div>
      </div>
    </div>
</div>
<?= $this->endSection() ?>
