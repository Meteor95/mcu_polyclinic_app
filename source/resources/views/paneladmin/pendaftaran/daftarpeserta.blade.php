@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Daftar Peserta</h4><span>Peserta yang terdaftar pada sistem dan belum mendapatkan jadwal. Informasi yang ditampikan berasal dari pendataran via website atau pendaftaran langsung yang datang ke lokasi MCU Arta Medica Clinic. Daftar peserta akan dihapus secara otomatis setelah 7 hari dari tanggal pendaftaran agar tidak menyebabkan duplikasi data.</span>
          <a href="{{ route('admin.pendaftaran.formulir_tambah_peserta') }}" class="btn btn-success w-100 mt-2" id="btn_tambahpeserta">Tambah Peserta MCU</a>
        </div>
        <div class="card-body">
          <input type="text" class="form-control" id="kotak_pencarian_daftarpeserta" placeholder="Cari informasi berdasarkan No Pemesanan, Identifikasi atau Nama Peserta">
          <table class="table display" id="datatables_daftarpeserta"></table>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="modalValidasiPeserta" tabindex="-1" role="dialog" aria-labelledby="modalValidasiPesertaLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Validasi Calon Peserta MCU : <span id="modal_validasi_nama_peserta"></span></h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <table class="table table-striped table-padding-sm-no-datatable">
              <tr><th>Nomor Identitas</th><td>: <span id="modal_nomor_identitas"></span></td></tr>
              <tr><th>Nama Peserta</th><td>: <span id="modal_nama_peserta"></span></td></tr>
              <tr><th>Tempat Lahir</th><td>: <span id="modal_tempat_lahir"></span></td></tr>
              <tr><th>Tanggal Lahir</th><td>: <span id="modal_tanggal_lahir"></span></td></tr>
              <tr><th>Tipe Identitas</th><td>: <span id="modal_tipe_identitas"></span></td></tr>
              <tr><th>Jenis Kelamin</th><td>: <span id="modal_jenis_kelamin"></span></td></tr>
              <tr><th>Status Perkawinan</th><td>: <span id="modal_status_perkawinan"></span></td></tr>
              <tr><th>No Telpon (Whatsapp Direkomendasikan)</th><td>: <span id="modal_no_telepon"></span></td></tr>
              <tr><th>Alamat Surel</th><td>: <span id="modal_alamat_surel"></span></td></tr>
              <tr><th>Alamat Tempat Tinggal</th><td>: <span id="modal_alamat_tempat_tinggal"></span></td></tr>
              <tr><th>Proses Kerja</th><td>: <span id="modal_proses_kerja"></span></td></tr>
            </table>
            <h5 class="text-center"> Pratinjau Lingkungan Kerja</h5>
            <table class="table table-striped table-padding-sm-no-datatable" id="tabel_ini_lingkungan_kerja"></table>
            <h5 class="text-center"> Pratinjau Kecelakaan Kerja</h5>
            <div id="modal_informasi_kecelakaan_kerja"></div>
            <h5 class="text-center"> Pratinjau Kebiasaan Hidup</h5>
            <table class="table table-striped table-padding-sm-no-datatable" id="tabel_ini_kebiasaan_hidup"></table>
            <h5 class="text-center"> Pratinjau Penyakit Terdahulu</h5>
            <table class="table table-striped table-padding-sm-no-datatable" id="tabel_ini_penyakit_terdahulu"></table>
            <h5 class="text-center"> Pratinjau Penyakit Keluarga</h5>
            <table class="table table-striped table-padding-sm-no-datatable" id="tabel_ini_penyakit_keluarga"></table>
            <h5 class="text-center"> Pratinjau Imunisasi</h5>
            <table class="table table-striped table-padding-sm-no-datatable" id="tabel_ini_imunisasi"></table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
            <button type="button" class="btn btn-primary" onclick="jadikan_peserta()"><i class="fa fa-check"></i> Jadikan Peserta</button>
          </div>
      </div>
  </div>
</div>
@endsection
@section('css_load')
<style>
.dtfc-fixed-right {
    background-color: #f6f6f6 !important;
}
.dtfc-fixed-right_header {
    background-color: #ffffff !important;
}
body.dark-only .dtfc-fixed-right_header {
    background-color: #2a3650 !important;
}
</style>
@endsection
@section('js_load')
<script src="https://cdn.datatables.net/fixedcolumns/4.0.2/js/dataTables.fixedColumns.min.js"></script>
<script src="{{ asset('vendor/erayadigital/pendaftaran/peserta.js') }}"></script>
@endsection
