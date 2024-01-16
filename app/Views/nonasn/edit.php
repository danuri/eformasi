<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Pemetaan Formasi Non ASN Kemenag</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Formasi</a></li>
                        <li class="breadcrumb-item active">Mapping</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <form action="">
              <div class="row">
                <div class="col-6">
                  <div class="row mb-3">
                      <div class="col-lg-3">
                          <label for="pendidikan" class="form-label">NIK</label>
                      </div>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" value="<?= $nonasn->NIK?>" disabled>
                      </div>
                  </div>
                  <div class="row mb-3">
                      <div class="col-lg-3">
                          <label for="jabatan" class="form-label">Nama</label>
                      </div>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" value="<?= $nonasn->NAMA?>" disabled>
                      </div>
                  </div>
                  <div class="row mb-3">
                      <div class="col-lg-3">
                          <label for="penempatan" class="form-label">Tanggal Lahir / JK</label>
                      </div>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" value="<?= $nonasn->TGL_LAHIR.' / '.$nonasn->JENIS_KELAMIN?>" disabled>
                      </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="row mb-3">
                      <div class="col-lg-3">
                          <label for="pendidikan" class="form-label">Pendidikan</label>
                      </div>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" value="<?= $nonasn->NAMA_PENDIDIKAN?>" disabled>
                      </div>
                  </div>
                  <div class="row mb-3">
                      <div class="col-lg-3">
                          <label for="jabatan" class="form-label">Jabatan</label>
                      </div>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" value="<?= $nonasn->NAMA_JABATAN?>" disabled>
                      </div>
                  </div>
                  <div class="row mb-3">
                      <div class="col-lg-3">
                          <label for="penempatan" class="form-label">Unit Penempatan</label>
                      </div>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" value="<?= $nonasn->UNOR_NAMA?>" disabled>
                      </div>
                  </div>
                  <div class="row mb-3">
                      <div class="col-lg-3">
                          <label for="penempatan" class="form-label">Status Non ASN</label>
                      </div>
                      <div class="col-lg-9">
                        <select class="form-select" name="status_pemetaan" id="statuspemetaan">
                          <option value=""></option>
                          <option value="Aktif" <?= ($nonasn->status_pemetaan == 'Aktif')?'selected':'';?>>Aktif</option>
                          <option value="Tidak Aktif" <?= ($nonasn->status_pemetaan == 'Tidak Aktif')?'selected':'';?>>Tidak Aktif</option>
                          <option value="Meninggal Dunia" <?= ($nonasn->status_pemetaan == 'Meninggal Dunia')?'selected':'';?>>Meninggal Dunia</option>
                          <option value="Alih Daya" <?= ($nonasn->status_pemetaan == 'Alih Daya')?'selected':'';?>>Alih Daya (Pengemudi/Petugas Keamanan/Cleaning)</option>
                        </select>
                      </div>
                  </div>
                </div>
              </div>
          </form>
          </div>
        </div>

        <?php if($nonasn->status_pemetaan == 'Aktif'){ ?>

        <div class="card">
          <div class="card-header">

          </div>
          <div class="card-body">
            <form action="" method="post">
              <div class="row mb-3">
                  <div class="col-lg-3">
                      <label for="pendidikan" class="form-label">Pendidikan</label>
                  </div>
                  <div class="col-lg-9">
                    <select class="form-select select2" name="pendidikan" id="pendidikan">
                      <?php
                      foreach ($pendidikan as $row) {
                        echo '<option value="'.$row->pendidikan.'">'.$row->pendidikan.'</option>';
                      }
                      ?>
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
                    <select class="form-select" name="unor" id="unor"></select>
                  </div>
              </div>

              <div class="text-end">
                  <button type="submit" class="btn btn-primary">Simpan</button>
              </div>
          </form>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#statuspemetaan').on('change',function(event) {
      window.location.replace("<?= site_url('pppk/status/'.encrypt($nonasn->ID))?>/"+$('#statuspemetaan').val());
    });

    $('#pendidikan').select2();
    $('#jabatan').select2();
  });

  $('#unor').select2({
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
</script>
<?= $this->endSection() ?>
