@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
    <div class="card">
        <div class="card-header">
          <h4>Daftar Departemen Peserta MCU</h4><span>Silahkan tentukan departemen peserta MCU yang akan digunakan oleh perusahaan MCU Artha Medica Clinic.</span>
          <button class="mt-2 btn btn-outline-success w-100" id="tambah_departemen_peserta_baru" type="button"><i class="fa fa-plus"></i> Formulir Tambah Departemen Peserta MCU Baru</button>
        </div>
        <div class="card-body">
          <div class="col-md-12">
            <input type="text" class="form-control" id="kotak_pencarian_departemen_peserta" placeholder="Cari data berdasarkan nama departemen peserta yang terdaftar di MCU Artha Medica Clinic">
            <div class="table">
              <table class="display" id="datatable_departemenpeserta"></table>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="formulir_tambah_departemen_peserta" tabindex="-1" aria-labelledby="formulir_tambah_departemen_pesertaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formulir_tambah_departemen_pesertaLabel">Formulir Tambah Departemen Peserta MCU Baru</h5>
                <button type="button btn-danger" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formulir_tambah_departemen_peserta_baru">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kodedepartemen" class="form-label">Kode Departemen Peserta</label>
                        <input placeholder="Ex: DO" type="text" class="form-control" id="kodedepartemen" name="kodedepartemen" required>
                        <div class="invalid-feedback">Masukan kode departemen peserta yang valid</div>
                        <div class="valid-feedback">Terlihat bagus! Kode departemen peserta sudah terisi</div>
                    </div>
                    <div class="mb-3">
                        <label for="namadepartemen" class="form-label">Nama Departemen Peserta</label>
                        <input placeholder="Ex: Developer Operational" type="text" class="form-control" id="namadepartemen" name="namadepartemen" required>
                        <div class="invalid-feedback">Masukan nama departemen peserta yang valid</div>
                        <div class="valid-feedback">Terlihat bagus! Nama departemen peserta sudah terisi</div>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input placeholder="Ex: Departemen Peserta untuk karyawan yang bekerja di bagian pemasaran" type="text" class="form-control" id="keterangan" name="keterangan" required>
                        <div class="invalid-feedback">Masukan keterangan departemen peserta yang valid</div>
                        <div class="valid-feedback">Terlihat bagus! Keterangan departemen peserta sudah terisi</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="simpan_departemen_peserta" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('css_load')
@endsection
@section('js_load')
<script src="{{asset('mofi/assets/js/system/master_data/departemenpeserta.js')}}"></script>
@endsection
