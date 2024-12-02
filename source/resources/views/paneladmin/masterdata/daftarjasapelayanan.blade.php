@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
    <div class="card">
        <div class="card-header">
          <h4>Daftar Jasa Layanan Pegawai MCU</h4><span>Silahkan tentukan jasa pelayanan yang akan digunakan oleh pegawai MCU Artha Medica Clinic pada setiap tindakan. Jasa layanan akan direkam pada setiap tindakan yang dilakukan oleh pegawai MCU.</span>
          <button class="mt-2 btn btn-outline-success w-100" id="tambah_jasa_pelayanan_baru" type="button"><i class="fa fa-plus"></i> Formulir Tambah Jasa Layanan MCU Baru</button>
        </div>
        <div class="card-body">
          <div class="col-md-12">
            <input type="text" class="form-control" id="kotak_pencarian_jasa_pelayanan" placeholder="Cari data berdasarkan nama jasa pelayanan yang terdaftar di MCU Artha Medica Clinic">
            <div class="table">
              <table class="display" id="datatable_jasapelayanan"></table>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="formulir_tambah_jasa_pelayanan" tabindex="-1" aria-labelledby="formulir_tambah_jasa_pelayananLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formulir_tambah_jasa_pelayananLabel">Formulir Tambah Jasa Pelayanan MCU Baru</h5>
                <button type="button btn-danger" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formulir_tambah_jasa_pelayanan_baru">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kodejasa" class="form-label">Kode Jasa Pelayanan</label>
                        <input placeholder="Ex: JDPD" type="text" class="form-control" id="kodejasa" name="kodejasa" required>
                        <div class="invalid-feedback">Masukan kode jasa pelayanan yang valid</div>
                        <div class="valid-feedback">Terlihat bagus! Kode jasa pelayanan sudah terisi</div>
                    </div>
                    <div class="mb-3">
                        <label for="namajasa" class="form-label">Nama Jasa Pelayanan</label>
                        <input placeholder="Ex: Jasa Dokter Penyakit Dalam" type="text" class="form-control" id="namajasa" name="namajasa" required>
                        <div class="invalid-feedback">Masukan nama jasa pelayanan yang valid</div>
                        <div class="valid-feedback">Terlihat bagus! Nama jasa pelayanan sudah terisi</div>
                    </div>
                    <div class="mb-3">
                        <label for="nominaljasa" class="form-label">Nominal Jasa Pelayanan</label>
                        <input placeholder="Ex: 1.500.000" type="text" class="form-control" id="nominaljasa" name="nominaljasa" required>
                        <div class="invalid-feedback">Masukan nominal jasa pelayanan yang valid</div>
                        <div class="valid-feedback">Terlihat bagus! Nominal jasa pelayanan sudah terisi</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="simpan_jasa_pelayanan" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('css_load')
@endsection
@section('js_load')
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.8.1/autoNumeric.min.js"></script>
<script src="{{asset('mofi/assets/js/system/master_data/jasapelayanan.js')}}"></script>
@endsection
