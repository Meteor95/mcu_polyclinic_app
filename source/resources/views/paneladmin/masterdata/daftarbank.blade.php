@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
    <div class="card">
        <div class="card-header">
          <h4>Daftar Bank Penerima</h4><span>Informasi bank yang terdaftar pada aplikasi MCU Artha Medica Clinic untuk sementara hanya sebagai tanda pencatat transaksi transfer yang dilakukan saat transaksi MCU. Jadi BANK tidak terhubung secara langsung dengan aplikasi MCU Artha Medica Clinic.</span>
          <button class="mt-2 btn btn-outline-success w-100" id="tambah_bank_baru" type="button"><i class="fa fa-plus"></i> Formulir Tambah Bank Baru</button>
        </div>
        <div class="card-body">
          <div class="col-md-12">
            <input type="text" class="form-control" id="kotak_pencarian_bank" placeholder="Cari data berdasarkan nama bank yang terdaftar di MCU Artha Medica Clinic">
            <div class="table">
              <table class="display" id="datatables_daftarbank"></table>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="formulir_tambah_bank" tabindex="-1" aria-labelledby="formulir_tambah_bankLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formulir_tambah_bankLabel">Formulir Tambah Bank Baru</h5>
                <button type="button btn-danger" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="formulir_tambah_bank_baru">
              <div class="mb-3">
                <label for="kodebank" class="form-label">Kode Bank</label>
                <input placeholder="Ex: EDS" type="text" class="form-control" id="kodebank" name="kodebank" required>
                <div class="invalid-feedback">Masukan kode bank yang valid</div>
                <div class="valid-feedback">Terlihat bagus! Kode bank sudah terisi</div>
              </div>
              <div class="mb-3">
                <label for="namabank" class="form-label">Nama Bank</label>
                <input placeholder="Ex: PT. Eraya Digital Solusindo" type="text" class="form-control" id="namabank" name="namabank" required>
                <div class="invalid-feedback">Masukan nama bank yang valid</div>
                <div class="valid-feedback">Terlihat bagus! Nama bank sudah terisi</div>
              </div>
              <div class="mb-3">
                <label for="keteranganbank" class="form-label">Keterangan</label>
                <input placeholder="Ex: Jl. Raya Bogor KM. 12, Kel. Pasir Jaya, Kec. Bogor Barat, Kota Bogor, Jawa Barat" type="text" class="form-control" id="keteranganbank" name="keteranganbank" required>
                <div class="invalid-feedback">Masukan keterangan bank yang valid</div>
                <div class="valid-feedback">Terlihat bagus! Keterangan bank sudah terisi</div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <button type="submit" id="simpan_bank" class="btn btn-primary">Simpan Data</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('css_load')
@endsection
@section('js_load')
<script src="{{asset('mofi/assets/js/system/master_data/daftarbank.js')}}"></script>
@endsection