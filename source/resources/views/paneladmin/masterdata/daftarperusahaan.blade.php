@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
    <div class="card">
        <div class="card-header">
          <h4>Perusahaan Terdaftar di MCU Artha Medica Clinic</h4><span>Silahkan cari data perusahaan berdasarkan nama perusahaan yang terdaftar di MCU Artha Medica Clinic guna melakukan pengaturan data perusahaan untuk menentukan jenis paket MCU yang tersedia. Anda dapat menentukan paket paket MCU berbeda pada masing-masing perusahaan.</span>
          <button class="mt-2 btn btn-outline-success w-100" id="tambah_perusahaan_baru" type="button"><i class="fa fa-plus"></i> Formulir Tambah Perusahaan Baru</button>
        </div>
        <div class="card-body">
          <div class="col-md-12">
            <input type="text" class="form-control" id="kotak_pencarian_perusahaan" placeholder="Cari data berdasarkan nama perusahaan yang terdaftar di MCU Artha Medica Clinic">
            <div class="table">
              <table class="display" id="datatables_perusahaan"></table>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="formulir_tambah_perusahaan" tabindex="-1" aria-labelledby="formulir_tambah_perusahaanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formulir_tambah_perusahaanLabel">Formulir Tambah Perusahaan Baru</h5>
                <button type="button btn-danger" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="formulir_tambah_perusahaan_baru">
              <div class="mb-3">
                <label for="kodeperusahaan" class="form-label">Kode Perusahaan</label>
                <input placeholder="Ex: EDS" type="text" class="form-control" id="kodeperusahaan" name="kodeperusahaan" required>
                <div class="invalid-feedback">Masukan kode perusahaan yang valid</div>
                <div class="valid-feedback">Terlihat bagus! Kode perusahaan sudah terisi</div>
              </div>
              <div class="mb-3">
                <label for="namaperusahaan" class="form-label">Nama Perusahaan</label>
                <input placeholder="Ex: PT. Eraya Digital Solusindo" type="text" class="form-control" id="namaperusahaan" name="namaperusahaan" required>
                <div class="invalid-feedback">Masukan nama perusahaan yang valid</div>
                <div class="valid-feedback">Terlihat bagus! Nama perusahaan sudah terisi</div>
              </div>
              <div class="mb-3">
                <label for="alamatperusahaan" class="form-label">Alamat Perusahaan</label>
                <input placeholder="Ex: Jl. Raya Bogor KM. 12, Kel. Pasir Jaya, Kec. Bogor Barat, Kota Bogor, Jawa Barat" type="text" class="form-control" id="alamatperusahaan" name="alamatperusahaan" required>
                <div class="invalid-feedback">Masukan alamat perusahaan yang valid</div>
                <div class="valid-feedback">Terlihat bagus! Alamat perusahaan sudah terisi</div>
              </div>
              <div class="mb-3">
                <div class="form-label">Keterangan Informasi Perusahaan</div>
                <div id="keteranganperusahaan"></div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <button type="submit" id="simpan_perusahaan" class="btn btn-primary">Simpan Data</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('css_load')
@component('komponen.css.datatables')
@endcomponent
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
@endsection
@section('js_load')
@component('komponen.js.datatables')
@endcomponent
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script src="{{asset('mofi/assets/js/system/master_data/perusahaan.js')}}"></script>
@endsection