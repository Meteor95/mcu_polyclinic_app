@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
    <div class="card">
        <div class="card-header">
          <h4>Paket MCU Artha Medica Clinic</h4><span>Silahkan cari data paket MCU berdasarkan nama perusahaan yang terdaftar di MCU Artha Medica Clinic guna melakukan pengaturan data paket MCU yang tersedia.</span>
          <button class="mt-2 btn btn-outline-success w-100" id="tambah_paket_mcu_baru" type="button"><i class="fa fa-plus"></i> Formulir Tambah Paket MCU Baru</button>
        </div>
        <div class="card-body">
          <div class="col-md-12">
            <input type="text" class="form-control" id="kotak_pencarian_paket_mcu" placeholder="Cari data berdasarkan nama perusahaan yang terdaftar di MCU Artha Medica Clinic">
            <div class="table">
              <table class="display" id="datatable_paketmcu"></table>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="formulir_tambah_paket_mcu" tabindex="-1" aria-labelledby="formulir_tambah_paket_mcuLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formulir_tambah_paket_mcuLabel">Formulir Tambah Paket MCU Baru</h5>
                <button type="button btn-danger" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formulir_tambah_paket_mcu_baru">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kodepaketmcu" class="form-label">Kode Paket MCU</label>
                        <input placeholder="Ex: EDS" type="text" class="form-control" id="kodepaketmcu" name="kodepaketmcu" required>
                        <div class="invalid-feedback">Masukan kode paket MCU yang valid</div>
                        <div class="valid-feedback">Terlihat bagus! Kode paket MCU sudah terisi</div>
                    </div>
                    <div class="mb-3">
                        <label for="namapaketmcu" class="form-label">Nama Paket MCU [Dalam Rupiah]</label>
                        <input placeholder="Ex: Paket MCU Basic" type="text" class="form-control" id="namapaketmcu" name="namapaketmcu" required>
                        <div class="invalid-feedback">Masukan nama paket MCU yang valid</div>
                        <div class="valid-feedback">Terlihat bagus! Nama paket MCU sudah terisi</div>
                    </div>
                    <div class="mb-3">
                        <label for="hargapaketmcu" class="form-label">Harga Paket MCU</label>
                        <input placeholder="Ex: 1.500.000" type="text" class="form-control" id="hargapaketmcu" name="hargapaketmcu" required>
                        <div class="invalid-feedback">Masukan harga paket MCU yang valid</div>
                        <div class="valid-feedback">Terlihat bagus! Harga paket MCU sudah terisi</div>
                    </div>
                    <div class="mb-3">
                        <label for="aksespolipaketmcu" class="form-label">Akses Poli</label>
                        <input class="form-control" id="aksespolipaketmcu" name="aksespolipakketmcu" placeholder="Ex: Poli Umum, Poli Gigi" required>
                        <div class="invalid-feedback">Masukan akses poli yang valid</div>
                        <div class="valid-feedback">Terlihat bagus! Akses poli sudah terisi</div>
                    </div>
                    <div class="mb-3">
                        <label for="keteranganpaketmcu" class="form-label">Keterangan</label>
                        <input placeholder="Ex: Paket MCU Basic untuk pemeriksaan dasar" type="text" class="form-control" id="keteranganpaketmcu" name="keteranganpaketmcu" required>
                        <div class="invalid-feedback">Masukan keterangan yang valid</div>
                        <div class="valid-feedback">Terlihat bagus! Keterangan sudah terisi</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="simpan_paket_mcu" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('css_load')
@component('komponen.css.datatables')
@endcomponent
<link rel="stylesheet" type="text/css" href="{{asset('mofi/assets/css/vendors/tagify.css')}}">
@endsection
@section('js_load')
@component('komponen.js.datatables')
@endcomponent
<script src="{{asset('mofi/assets/js/select2/tagify.js')}}"></script>
<script src="{{asset('mofi/assets/js/select2/tagify.polyfills.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.8.1/autoNumeric.min.js"></script>
<script src="{{asset('mofi/assets/js/system/master_data/paketmcu.js')}}"></script>
@endsection
