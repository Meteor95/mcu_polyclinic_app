@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Rekap Pemeriksaan Spirometr Pada Perusahaan</h4><span>Pada tabel ini adalah rekap pemeriksaan spirometro pada perusahaan, silahkan pilih perusahaan dan periode untuk melihat rekap pemeriksaan tanda vital. Jadi nantinya tim AMC dapat melihat seberapa banyak perusahaan mengirimkan pegawainya untuk dicek kesehatannya di AMC ini</span>
        </div>
        <div class="card-body">
            <div class="row mb-2">
              <div class="col-md-6">
                <label for="tanggal_awal">Tanggal Awal</label>
                <input type="text" class="form-control" id="tanggal_awal" placeholder="Pilih Tanggal Awal">
              </div>
              <div class="col-md-6">
                <label for="tanggal_akhir">Tanggal Akhir</label>
                <input type="text" class="form-control" id="tanggal_akhir" placeholder="Pilih Tanggal Akhir">
              </div>
            </div>
            <div class="row mb-2">
              <!-- Kolom 2 -->
              <div class="col-md-12">
                 <select class="form-select" id="select2_perusahaan" name="select2_perusahaan" required></select>
              </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-12">
                    <button id="btn_baca_data_rekap" class="btn btn-block w-100 btn-success"><i class="fa fa-search"></i> Baca Data Rekap </button>
                </div>
            </div>
            <hr>
            <div class="table-responsive theme-scrollbar">
                <table class="display" id="datatables_spirometri_perusahaan"></table>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
@section('css_load')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('mofi/assets/css/vendors/flatpickr/flatpickr.min.css') }}">
@endsection
@section('js_load')
<script>
    let id_perusahaan = {!! json_encode($ids) !!};
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('mofi/assets/js/flat-pickr/flatpickr.js') }}"></script>
<script src="https://cdn.datatables.net/rowgroup/1.5.1/js/dataTables.rowGroup.min.js"></script>
<script src="{{ asset('vendor/erayadigital/laporan/rekap_spirometri.js') }}?v={{ filemtime(public_path('vendor/erayadigital/laporan/rekap_spirometri.js')) }}"></script>
@endsection