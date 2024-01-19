<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Pengguna</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:;" class="btn btn-success" onclick="add()">Tambah Pengguna</a></li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <p>Pengguna yang ditambahkan hanya dapat mengakses Usulan PPPK</p>
          <div class="card-body">
            <table class="table table-bordered table-striped datatable">
              <thead>
                <tr class="text-center">
                  <th>NIP</th>
                  <th>NAMA</th>
                  <th>SATKER</th>
                  <th>AKSI</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($users as $row) {
                ?>
                  <tr>
                    <td><?= $row->nip?></td>
                    <td><?= $row->nama?></td>
                    <td><?= $row->satuan_kerja?></td>
                    <td><a href="<?= site_url('users/delete/'.encrypt($row->id))?>" onclick="return confirm('Pengguna akan dihapus?')">Delete</a></td>
                  </tr>
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
        <h5 class="modal-title" id="myModalLabel">Tambah Pegawai</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="" action="" method="post">
          <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" class="form-control" name="nip" id="nip" value="">
          </div>
          <div class="mb-3">
            <label for="nama" class="form-label">NAMA</label>
            <input type="text" class="form-control" name="nama" id="nama" value="">
          </div>
          <div class="mb-3">
            <label for="satker" class="form-label">SATUAN KERJA</label>
            <select class="form-select" name="satker" id="satker">
              <?php foreach ($satker as $row) {
                echo '<option value="'.encrypt($row->kode_satuan_kerja).'">'.$row->satuan_kerja.'</option>';
              } ?>
            </select>
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
<script type="text/javascript">

function add() {
  $('#addmodal').modal('show');
}

function insert() {
  axios.post('<?= site_url()?>/users/add', {
    nip: $('#nip').val(),
    nama: $('#nama').val(),
    satuan_kerja: $( "#satker option:selected" ).text(),
    kode_satker: $('#satker').val()
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
