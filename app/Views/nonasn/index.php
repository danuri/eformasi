<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Pemetaan Formasi Non ASN Kemenag</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('pppk/rekap')?>" class="btn btn-warning">Rekapitulasi</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('pppk/export/0')?>" class="btn btn-success">Export Excel</a></li>
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
                  <th>SATUAN KERJA</th>
                  <th>JUMLAH</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($satker as $row) {
                ?>
                  <tr>
                    <td><?= $row->SATKER?></td>
                    <td><a href="<?= site_url('pppk/data/'.encrypt($row->KODE_SATKER))?>"><?= $row->JUMLAH?></a></td>
                  </tr>
                <?php } ?>
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
	                	<form action="<?= site_url('pppk/final')?>" method="post" enctype="multipart/form-data">
							<div class="mb-3">
								<label class="form-label">Pengantar Surat Usulan dan SPTJM (Usulan CPPPK):</label>
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
