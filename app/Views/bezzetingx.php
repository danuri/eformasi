<?= $this->extend('template') ?>

<?= $this->section('style') ?>
<link rel="stylesheet" href="<?= base_url() ?>assets/libs/treetables/tree-table.min.css" type="text/css" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Pengajuan Formasi</h4>

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
          <div class="card border card-border-danger">
            <div class="card-body">
              <table class="table table-bordered" id="formasitable">
                <thead class="text-center">
                  <tr>
                    <th rowspan="3">SATUAN ORGANISASI/JABATAN</th>
                    <th colspan="5">KONDISI SAAT INI</th>
                    <th colspan="4">USULAN ASN 2023</th>
                  </tr>
                  <tr>
                    <th rowspan="2">ABK</th>
                    <th colspan="3">ASN</th>
                    <th rowspan="2">RILL</th>
                    <th colspan="4">FORMASI</th>
                  </tr>
                  <tr>
                    <th>PNS</th>
                    <th>CPNS</th>
                    <th>PPPK</th>
                    <th>PNS</th>
                    <th>PPPK</th>
                    <th>MHPK</th>
                    <th>TOTAL</th>
                  </tr>
                  <tr>
                    <th>1</th>
                    <th>1</th>
                    <th>1</th>
                    <th>1</th>
                    <th>1</th>
                    <th>1</th>
                    <th>1</th>
                    <th>1</th>
                    <th>1</th>
                    <th>10</th>
                  </tr>
                </thead>
                <tbody>

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
            <label for="posisi" class="form-label">Posisi Jabatan</label>
            <select name="posisi" id="posisi" class="form-select">
              <option value="0a">PIMPINAN TERTINGGI</option>
              <option value="0b">WAKIL PIMPINAN</option>
              <option value="1a">JPT UTAMA (I/A)</option>
              <option value="1b">JPT MADYA (I/A)</option>
              <option value="1c">JPT MADYA (I/B)</option>
              <option value="2a">JPT PRATAMA (II/A)</option>
              <option value="2b">JPT PRATAMA (II/B)</option>
              <option value="3a">ADMINSTRATOR (III/A)</option>
              <option value="3b">ADMINSTRATOR (III/B)</option>
              <option value="4a">PENGAWAS (IV/A)</option>
              <option value="4b">PENGAWAS (IV/B)</option>
              <option value="5a">JABATAN PELAKSANA</option>
              <option value="5b">FUNGSIONAL KEAHLIAN</option>
              <option value="5b1">AHLI UTAMA/PROFESOR</option>
              <option value="5b2">AHLI MADYA/LEKTOR KEPALA</option>
              <option value="5b3">AHLI MUDA/LEKTOR</option>
              <option value="5b4">AHLI PERTAMA/ASISTEN AHLI</option>
              <option value="5c">FUNGSIONAL KETERAMPILAN</option>
              <option value="5c1">PENYELIA</option>
              <option value="5c2">MAHIR</option>
              <option value="5c3">TERAMPIL</option>
              <option value="5c4">PEMULA</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="jabatan" class="form-label">Nama Jabatan</label>
            <input type="text" class="form-control" id="jabatan" name="nama_jabatan" placeholder="">
            <input type="hidden" name="parent" id="parent" value="">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="submit()">Simpan</button>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="<?= base_url() ?>assets/libs/treetables/treeTable.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('#formasitable').treeTable({
            "ajax": {
              url: "<?= site_url('bezzeting/dxuf')?>",
              dataSrc: 'data'
            },
            "collapsed": false,
            "columns": [
                {
                    "data": "nama_jabatan"
                },
                {
                    "data": "abk"
                },
                {
                    "data": "pns"
                },
                {
                    "data": "cpns"
                },
                {
                    "data": "pppk"
                },
                {
                    "data": "riil"
                },
                {
                    "data": "usul_pns"
                },
                {
                    "data": "usul_pppk"
                },
                {
                    "data": "mhpk"
                },
                {
                    "data": "mhpk"
                }
            ],
            "order": [[1, 'desc']]
        });

});

function addchild(id) {
  $('#parent').val(id);
  $('#addmodal').modal('show');
}

function submit() {
  axios.post('<?= site_url()?>/bezzeting/insert', {
    posisi: $('#posisi').val(),
    nama_jabatan: $('#jabatan').val(),
    parent: $('#parent').val()
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
