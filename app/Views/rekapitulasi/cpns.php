<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Rekapitulasi Usulan CPNS</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">CPNS</a></li>
                        <li class="breadcrumb-item active">Rekapitulasi</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <table class="table table-bordered table-striped" id="rekapcpns">
              <thead>
                <tr class="text-center">
                  <th>JABATAN</th>
                  <th>PENDIDIKAN</th>
                  <th>ALOKASI</th>
                  <th>UNIT PENEMPATAN</th>
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
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script type="text/javascript">
$(document).ready(function() {
  $('#rekapcpns').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
          url: '<?= site_url('rekapitulasi/getcpns')?>',
          method: 'POST'
      },
      order: [],
      columnDefs: [
          { targets: 0, orderable: false},
      ],
      columns: [
          {data: 'jabatan', searchable:false},
          {data: 'pendidikan', searchable:false},
          {data: 'jumlahkeb', searchable:false},
          {data: 'nama', name: 'ref_unor.nama'}
      ]
  });
});
</script>
<?= $this->endSection() ?>
