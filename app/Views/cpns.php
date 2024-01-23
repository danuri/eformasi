<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Kebutuhan CPNS 2024</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('cpns/rekap')?>" class="btn btn-warning">Rekapitulasi</a></li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-bordered table-striped ">
              <thead>
                <tr class="text-center">
                  <th>Unit Organisasi</th>
                  <th>Jabatan</th>
                  <th>Bezzeting</th>
                  <th>Kebutuhan</th>
                  <th>Opsi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($unor as $row) {
                ?>
                  <tr>
                    <td colspan="5"><a href="<?= site_url('cpns/sub/'.$row->id)?>"><?= $row->nama_unor?></a> <a href="javascript:;" class="badge text-bg-secondary" onclick="addchild('<?= $row->id?>')" title="Tambah Jabatan"><i class="bx bx-plus-circle"></i></a></td>
                  </tr>
                  <?php
                  $jabatans = getJabatan($row->id);

                  foreach ($jabatans as $row) {?>
                    <tr>
                      <td></td>
                      <td><?= $row->jabatan?></td>
                      <td><?= $row->bezzeting?></td>
                      <td><?= $row->kebutuhan?></td>
                      <td><a href="javascript:;" class="badge text-bg-warning" onclick="edit()" title="Edit Jumlah"><i class="bx bx-pencil"></i></a> <a href="<?= site_url('cpns/delete/'.encrypt($row->id)) ?>" class="badge text-bg-danger" onclick="return confirm('Data akan dihapus?')" title="Hapus Data"><i class="bx bx-trash"></i></a></td>
                    </tr>
                  <?php } ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>

<div id="addmodal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" data-bs-backdrop="static" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header p-3 bg-soft-info">
        <h5 class="modal-title" id="myModalLabel">Formulir Jabatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="" action="" method="post">
          <div class="mb-3">
            <label for="jabatan" class="form-label">Nama Jabatan</label>
            <select class="form-select" name="jabatan" id="jabatan">
              <?php foreach ($jabatan as $row) {
                echo '<option value="'.$row->nama_jabatan.'">'.$row->nama_jabatan.'</option>';
              } ?>
            </select>
            <input type="hidden" name="unorid" id="unorid" value="">
          </div>
          <div class="mb-3">
            <label for="bezzeting" class="form-label">Bezzeting/Jumlah Existing pada Jabatan</label>
            <input type="number" class="form-control" name="bezzeting" id="bezzeting" value="">
          </div>
          <div class="mb-3">
            <label for="kebutuhan" class="form-label">Kebutuhan</label>
            <input type="number" class="form-control" name="kebutuhan" id="kebutuhan" value="">
          </div>
          <div class="mb-3">
            <label for="kebutuhan" class="form-label">Jumlah Ideal Pegawai</label>
            <input type="number" class="form-control" name="ideal" id="ideal" value="" disabled>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="insert()">Simpan</button>
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

  $('#bezzeting').on('keyup',function(event) {
    var bezz = $('#bezzeting').val();
    var keb = $('#kebutuhan').val();
    var ideal = parseInt(bezz) + parseInt(keb);
    $('#ideal').val(ideal);
  });

  $('#kebutuhan').on('keyup',function(event) {
    var bezz = $('#bezzeting').val();
    var keb = $('#kebutuhan').val();
    var ideal = parseInt(bezz) + parseInt(keb);
    $('#ideal').val(ideal);
  });
});

function addchild(id) {
  $('#unorid').val(id);
  $('#addmodal').modal('show');
}

function insert() {
  axios.post('<?= site_url()?>/cpns/insert', {
    unor_id: $('#unorid').val(),
    jabatan: $('#jabatan').val(),
    bezzeting: $("#bezzeting").val(),
    kebutuhan: $('#kebutuhan').val()
  })
  .then(function (response) {
    if(response.data.message == 'ok'){
      location.reload();
    }else{
      alert(response.data.message);
    }
  })
  .catch(function (error) {
    console.log(error);
  });
}
</script>
<?= $this->endSection() ?>
