@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row default-dashboard">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          @include('komponen.information_user', ['title_card' => "Riwayat Kecelakaan Kerja", 'informasi_apa' => "informasi riwayat kecelakaan kerja"])
        </div>
        <div class="card-body">
            <h1 class="mb-2 text-center">Formulir Riwayat Kecelakaan Kerja</h1>
            <div id="editor_kecelakaan_kerja"></div>
            <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                <button class="btn btn-danger w-100 mt-2" id="bersihkan_data_riwayat_kecelakaan_kerja"><i class="fa fa-refresh"></i> Bersihkan Data</button>                   
                <button class="mt-2 btn btn-success w-100" id="btnSimpanRiwayatKecelakaanKerja">Simpan Riwayat Kecelakaan Kerja</button>
            </div>
            @if(isset($data['dataNavigasi']))
                @include('komponen.navigasi_riwayat_informasi', $data['dataNavigasi'])
            @endif
        </div>
        <div class="card-footer">
            <h1 class="mb-2 text-center">Daftar Kecelakaan Kerja</h1>
            <input type="text" class="form-control" id="kotak_pencarian_daftarpasien" placeholder="Cari data...">
            <div class="table-responsive theme-scrollbar">
              <table class="display" id="datatables_daftar_kecelakaan_kerja"></table>
            </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="modalLihatRiwayatKecelakaanKerja" tabindex="-1" role="dialog" aria-labelledby="modalLihatRiwayatKecelakaanKerjaLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nama Pasien : <span id="modal_nama_peserta_parameter"></span></h5>
                <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
            </div>
            <div class="modal-body">
              <div id="editor_riwayat_kecelakaan_kerja"></div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
  </div>
@endsection
@section('css_load')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
table.dataTable tbody td.focus {
  outline: 1px dotted rgb(255, 153, 0);
  outline-offset: -2px;
}
</style>
@endsection
@section('js_load')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('vendor/erayadigital/riwayat/kecelakaan_kerja.js') }}"></script>
<script>
  let param_nomor_identitas = '{{$data['nomor_identitas']}}'
  let param_nama_peserta = '{{$data['nama_peserta']}}'
</script>
@endsection
