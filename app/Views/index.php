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
        <div class="alert alert-success" role="alert">
            <strong> Harap Dibaca! </strong>
            <p>
              <ol>
                <li>Aplikasi ini hanya untuk Jabatan Guru dan Dosen</li>
                <li>Jabatan Dosen
                  <ul>
                    <li>Untuk nama Jabatan, <b>tidak lagi</b> menggunakan nama Mata Kuliah. Hanya menggunakan <b>ASISTEN AHLI - DOSEN</b> / <b>LEKTOR - DOSEN</b></li>
                    <li>Spesifikasi dibedakan pada <b>Unit Penempatan</b> dan <b>Kualifikasi Pendidikan</b></li>
                  </ul>
                </li>
                <li>Jabatan Guru
                  <ul>
                    <li>Tidak perlu mengisi Kualifikasi Pendidikan</li>
                  </ul>
                </li>
              </ol>
            </p>
        </div>
      </div>
    </div>
</div>
<?= $this->endSection() ?>
