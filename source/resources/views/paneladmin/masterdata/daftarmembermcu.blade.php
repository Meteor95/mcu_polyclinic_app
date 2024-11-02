@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Member MCU Artha Medica Clinic</h4><span>Informasi daftar member MCU Artha Medica Clinic yang sudah terdaftar pada sistem dan sudah tervalidasi. Member kami sarankan tidak dihapus dari sistem dikarenakan member yang sudah terdaftar akan digunakan untuk pengambilan data hasil MCU dan saling ketergantungan.</span>
          <button class="mt-2 btn btn-outline-success w-100" id="tambah_member_mcu_baru" type="button"><i class="fa fa-plus"></i> Formulir Tambah Member MCU Baru</button>
        </div>
        <div class="card-body">
          <input type="text" class="form-control mb-3" id="kotak_pencarian_daftarmembermcu" placeholder="Cari data berdasarkan nama member">
          <div class="table-container">
            <table class="table display" id="datatables_daftarmembermcu"></table>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade modal-lg" id="formulir_tambah_member_mcu_baru" tabindex="-1" aria-labelledby="formulir_tambah_member_mcu_baruLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Formulir Tambah Member MCU Baru</h4>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-scrollbar">
                <div class="mb-3">
                    <label for="nomor_identitas" class="form-label">Nomor Identitas (KTP /SIM / Paspor)</label>
                    <input placeholder="Ex: 3602041211870001" type="text" class="form-control" id="nomor_identitas" name="nomor_identitas" required>
                    <div class="invalid-feedback">Masukan nomor identitas yang valid</div>
                    <div class="valid-feedback">Terlihat bagus! Nomor identitas sudah terisi</div>
                </div>
                <div class="mb-3">
                    <label for="nama_peserta" class="form-label">Nama Peserta</label>
                    <input placeholder="Ex: John Doe" type="text" class="form-control" id="nama_peserta" name="nama_peserta" required>
                    <div class="invalid-feedback">Masukan nama peserta yang valid</div>
                    <div class="valid-feedback">Terlihat bagus! Nama peserta sudah terisi</div>
                </div>
                <div class="mb-3">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                    <input placeholder="Ex: Jakarta" type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                    <div class="invalid-feedback">Masukan tempat lahir yang valid</div>
                    <div class="valid-feedback">Terlihat bagus! Tempat lahir sudah terisi</div>
                </div>
                <div class="mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="text" class="form-control" id="tanggal_lahir" name="tanggal_lahir" placeholder="dd-mm-yyyy" required>
                    <div class="invalid-feedback">Masukan tanggal lahir yang valid</div>
                    <div class="valid-feedback">Terlihat bagus! Tanggal lahir sudah terisi</div>
                </div>
                <div class="mb-3">
                    <label for="tipe_identitas" class="form-label">Tipe Identitas</label>
                    <select class="form-select" id="tipe_identitas" name="tipe_identitas" required>
                        <option value="KTP">KTP</option>
                        <option value="SIM">SIM</option>
                        <option value="Paspor">Paspor</option>
                        <option value="Visa">Visa</option>
                    </select>
                    <div class="invalid-feedback">Pilih tipe identitas</div>
                    <div class="valid-feedback">Terlihat bagus! Tipe identitas sudah dipilih</div>
                </div>
                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="Laki-Laki">Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                        <option value="Alien">Alien</option>
                    </select>
                    <div class="invalid-feedback">Pilih jenis kelamin</div>
                    <div class="valid-feedback">Terlihat bagus! Jenis kelamin sudah dipilih</div>
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Ex: Jl. Raya No. 123" required></textarea>
                    <div class="invalid-feedback">Masukan alamat yang valid</div>
                    <div class="valid-feedback">Terlihat bagus! Alamat sudah terisi</div>
                </div>
                <div class="mb-3">
                    <label for="status_kawin" class="form-label">Status Perkawinan</label>
                    <select class="form-select" id="status_kawin" name="status_kawin" required>
                        <option value="Belum Menikah">Belum Menikah</option>
                        <option value="Menikah">Menikah</option>
                        <option value="Cerai Hidup">Cerai Hidup</option>
                        <option value="Cerai Mati">Cerai Mati</option>
                    </select>
                    <div class="invalid-feedback">Pilih status perkawinan</div>
                    <div class="valid-feedback">Terlihat bagus! Status perkawinan sudah dipilih</div>
                </div>
                <div class="mb-3">
                    <label for="no_telepon" class="form-label">Nomor Telepon (Rekomendasi Whatsapp)</label>
                    <input placeholder="Ex: 081234567890" type="tel" class="form-control" id="no_telepon" name="no_telepon" required>
                    <div class="invalid-feedback">Masukan nomor telepon yang valid</div>
                    <div class="valid-feedback">Terlihat bagus! Nomor telepon sudah terisi</div>
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
@component('komponen.css.datatables')
@endcomponent
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
@component('komponen.js.datatables')
@endcomponent
<script src="{{ asset('mofi/assets/js/flat-pickr/flatpickr.js') }}"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.0.2/js/dataTables.fixedColumns.min.js"></script>
<script src="{{ asset('mofi/assets/js/system/master_data/daftarmembermcu.js') }}"></script>
@endsection
