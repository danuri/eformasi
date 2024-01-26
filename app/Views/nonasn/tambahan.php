<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Formasi Non ASN Kemenag - Tambahan</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addmodal">Tambah Baru</button></li>
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

        <div class="card">
								<div class="card-header">
					                <h6 class="mb-0">Final Usulan</h6>
								</div>

				                <div class="card-body">
                          <div class="alert alert-info alert-dismissible fade show">
              						<i class="ph-info me-2"></i>
              						<span class="fw-semibold">Perhatian!</span> Pastikan usulan sudah sesuai. Ketika Anda mengunggah Pengantar dan SPTJM, maka Anda tidak lagi dapat menambah/mengubah data.
              				    </div>
				                	<form action="<?= site_url('pppk/finaltambahan')?>" method="post" enctype="multipart/form-data">
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

<div id="addmodal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" data-bs-backdrop="static" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header p-3 bg-soft-info">
        <h5 class="modal-title" id="myModalLabel">Formulir PPPK Tambahan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="" action="" method="post" id="addform">
          <p>Tidak ada validasi NIK dengan DUKCAPIL. Pastikan NIK dan Nama sudah sesuai.</p>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="pendidikan" class="form-label">NIK</label>
              </div>
              <div class="col-lg-9">
                <input type="number" class="form-control" name="nik" id="nik" value="">
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="pendidikan" class="form-label">Nama</label>
              </div>
              <div class="col-lg-9">
                <input type="text" class="form-control" name="nama" id="nama" value="">
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="pendidikan" class="form-label">Pendidikan</label>
              </div>
              <div class="col-lg-9">
                <select class="form-select" name="pendidikan" id="pendidikan">
                </select>
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="jabatan" class="form-label">Jabatan</label>
              </div>
              <div class="col-lg-9">
                <select class="form-select select2" name="jabatan" id="jabatan">
                  <?php foreach ($jabatan as $row) {
                    echo '<option value="'.$row->nama_jabatan.'">'.$row->nama_jabatan.' ('.$row->group.')</option>';
                  } ?>
                </select>
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="penempatan" class="form-label">Unit Penempatan</label>
              </div>
              <div class="col-lg-9">
                <select class="form-select" name="unor" id="unor">
                </select>
              </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="$('#addform').submit()">Simpan</button>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {

    $('#jabatan').select2({
      dropdownParent: $('#addmodal')
    });
  });

  $('#unor').select2({
    dropdownParent: $('#addmodal'),
    ajax: {
      url: '<?= site_url() ?>pppk/search',
      data: function (params) {
        var query = {
          search: params.term,
          type: 'public'
        }

        return query;
      },
      processResults: function (data) {
        return {
          results: data
        };
      },
      processResults: (data, params) => {
          const results = data.map(item => {
            return {
              id: item.id,
              text: item.nama,
            };
          });
          return {
            results: results,
          }
        },
    },
    placeholder: 'Cari Unor',
    minimumInputLength: 5,
  });

  $('#pendidikan').select2({
    dropdownParent: $('#addmodal'),
    ajax: {
      url: '<?= site_url() ?>pppk/searchpendidikan',
      data: function (params) {
        var query = {
          search: params.term,
          type: 'public'
        }

        return query;
      },
      processResults: function (data) {
        return {
          results: data
        };
      },
      processResults: (data, params) => {
          const results = data.map(item => {
            return {
              id: item.nama,
              text: item.nama,
            };
          });
          return {
            results: results,
          }
        },
    },
    placeholder: 'Cari Pendidikan',
    minimumInputLength: 5,
  });
</script>
<?= $this->endSection() ?>
