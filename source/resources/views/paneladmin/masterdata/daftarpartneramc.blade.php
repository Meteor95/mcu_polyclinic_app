@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Partner Artha Medica Centre</h4><span>Informasi daftar partner Artha Medica Centre yang sudah terdaftar pada sistem dan sudah tervalidasi. Member kami sarankan tidak dihapus dari sistem dikarenakan member yang sudah terdaftar akan digunakan untuk pengambilan data hasil MCU dan saling ketergantungan.</span>
          <button class="mt-2 btn btn-outline-success w-100" id="tambah_partner_amc_baru" type="button"><i class="fa fa-plus"></i> Formulir Tambah Partner AMC Baru</button>
        </div>
        <div class="card-body">
          <input type="text" class="form-control mb-3" id="kotak_pencarian_daftarpartneramc" placeholder="Cari data berdasarkan nama partner AMC">
          <div class="table-container">
            <table class="table display" id="datatables_daftarpartneramc"></table>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade modal-lg" id="formulir_tambah_partner_amc_baru" tabindex="-1" aria-labelledby="formulir_tambah_partner_amc_baruLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Formulir Tambah Partner AMC Baru</h4>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-scrollbar">
                <div class="mb-3">
                    <label for="nomor_identitas" class="form-label">ID Perusahaan</label>
                    <input placeholder="Ex: 3602041211870001" type="text" class="form-control" id="nomor_identitas" name="nomor_identitas" required>
                    <div class="invalid-feedback">Masukan ID perusahaan yang valid</div>
                    <div class="valid-feedback">Terlihat bagus! ID perusahaan sudah terisi</div>
                </div>
                <div class="mb-3">
                    <label for="nama_peserta" class="form-label">Katasandi</label>
                    <input placeholder="Ex: John Doe" type="text" class="form-control" id="nama_peserta" name="nama_peserta" required>
                    <div class="invalid-feedback">Masukan katasandi yang valid</div>
                    <div class="valid-feedback">Terlihat bagus! Katasandi sudah terisi</div>
                </div>
                <div class="mb-3">
                    <label for="email_member_mcu" class="form-label">Alamat Surel</label>
                    <input placeholder="Ex: aries@arthemedica.com" type="text" class="form-control" id="email_member_mcu" name="email_member_mcu">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" type="button" id="simpan_member_mcu_baru">Simpan Data</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('css_load')
<link rel="stylesheet" type="text/css" href="{{ asset('mofi/assets/css/vendors/flatpickr/flatpickr.min.css') }}">
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
<script src="{{ asset('mofi/assets/js/flat-pickr/flatpickr.js') }}"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.0.2/js/dataTables.fixedColumns.min.js"></script>
<script src="{{ asset('vendor/erayadigital/master_data/daftarpartneramc.js') }}?v={{ filemtime(public_path('vendor/erayadigital/master_data/daftarpartneramc.js')) }}"></script>
@endsection 