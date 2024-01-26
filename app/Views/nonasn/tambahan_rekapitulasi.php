<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Rekapitulasi Usulan CPPPK - Tambahan</h4>

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
                    <td><?= $row->jabatan;?></td>
                    <td><?= $row->jumlah;?></td>
                    <td><?= $row->unit_nama;?></td>
                  </tr>
                  <?php $no++; } ?>
                </tbody>
              </table>
          </div>
        </div>

        <div class="card">
								<div class="card-header">
					                <h6 class="mb-0">Final Usulan</h6>
								</div>

				                <div class="card-body">
                          <div class="alert alert-info alert-dismissible fade show">
              						<i class="ph-info me-2"></i>
              						<span class="fw-semibold">Perhatian!</span> Pastikan usulan sudah sesuai. Ketika Anda mengunggah Pengantar dan SPTJM, maka Anda tidak lagi dapat menambah/mengubah data.
              				    </div>

                          <?php if(!empty($user->lampiran_pppk_tambahan)){ ?>
                          <div class="alert alert-warning alert-dismissible fade show">
                            <i class="ph-info me-2"></i>
                            <span class="fw-semibold"><a href="https://docu.kemenag.go.id:9000/sscasn/2024/eformasi/<?= $user->lampiran_pppk_tambahan?>" target="_blank">Lihat Dokumen</a></div>
                          <?php } ?>

				                	<form action="<?= site_url('pppk/tambahan/final')?>" method="post" enctype="multipart/form-data">
										<div class="mb-3">
											<label class="form-label">Pengantar Surat Usulan dan SPTJM (Usulan CPPPK Tambahan):</label>
											<input type="file" class="form-control" name="lampiran" placeholder="PDF">
										</div>

										<div class="d-flex align-items-center">
											<button type="submit" class="btn btn-primary ms-3">Submit <i class="ph-paper-plane-tilt ms-2"></i></button>
										</div>
									</form>
								</div>
              </div>
      </div>
    </div>
</div>
<?= $this->endSection() ?>
