@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
    <div class="card">
        <div class="card-header">
          <h4>Bank Data Kesimpulan Tindakan</h4><span>Silahkan cari data peserta berdasarkan kriteria yang tersedia guna melakukan pengaturan data kesimpulan tindakan medis. Anda dapat menentukan hasil evaluasi dan rekomendasi tindakan lanjut yang berbeda pada masing-masing peserta sesuai dengan hasil pemeriksaan MCU yang telah dilakukan.</span>
          <button class="mt-2 btn btn-outline-success w-100" id="tambah_kesimpulan_baru"><i class="fa fa-plus"></i> Formulir Tambah Kesimpulan</button>
        </div>
        <div class="card-body">
          <div class="row mb-2">
            <div class="col-md-6">
              <input type="text" class="form-control" id="kotak_pencarian_kesimpulan" placeholder="Cari data berdasarkan nama kesimpulan yang terdaftar di Aplikasi MCU Artha Medica Clinic">
            </div>
            <div class="col-md-6">
              <select class="form-control" id="jenis_pemeriksaan_pencarian">
                  <option value="">PILIH JENIS TINDAKAN</option>
                  <option value="pemeriksaan_audiometri">KESIMPULAN AUDIOMETRI</option>
                  <option value="pemeriksaan_ekg">KESIMPULAN EKG</option>
                  <option value="pemeriksaan_farmingham_score">KESIMPULAN FARMINGHAM SCORE</option>
                  <option value="pemeriksaan_fisik">KESIMPULAN FISIK</option>
                  <option value="pemeriksaan_laboratorium">KESIMPULAN LABORATORIUM</option>
                  <option value="pemeriksaan_rontgen_lumbosacral">KESIMPULAN RONTGEN LUMBOSACRAL</option>
                  <option value="pemeriksaan_rontgen_thorax">KESIMPULAN RONTGEN THORAX</option>
                  <option value="pemeriksaan_spirometri">KESIMPULAN SPIROMETRI</option>
                  <option value="pemeriksaan_threadmill">KESIMPULAN TREADMILL</option>
                  <option value="pemeriksaan_usg_ubdomain">KESIMPULAN USG ABDOMAIN</option>
                  <option value="saran">KESIMPULAN SARAN</option>
                  <option value="poli_spirometri">PEMERIKSAAN POLI SPIROMETRI</option>
                  <option value="poli_audiometri">PEMERIKSAAN POLI AUDIOMETRI</option>
                  <option value="poli_ekg">PEMERIKSAAN POLI EKG</option>
                  <option value="poli_threadmill">PEMERIKSAAN POLI TREADMILL</option>
                  <option value="poli_rontgen_thorax">PEMERIKSAAN POLI RONTGEN</option>
                  <option value="poli_rontgen_lumbosacral">PEMERIKSAAN POLI RONTGEN LUMBOSACRAL</option>
                  <option value="poli_usg_ubdomain">PEMERIKSAAN POLI USG ABDOMEN</option>
                  <option value="poli_farmingham_score">PEMERIKSAAN POLI FARMINGHAM SCORE</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="table"><table class="display table-padding-sm" id="datatables_kesimpulan"></table></div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="formulir_tambah_kesimpulan" tabindex="-1" aria-labelledby="formulir_tambah_kesimpulanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formulir_tambah_kesimpulanLabel">Formulir Kesimpulan Tindakan</h5>
                <button type="button btn-danger" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="pilihjeniskesimpulan" class="form-label">Pilih Jenis Kesimpulan</label>
                <select class="form-control" id="jenis_pemeriksaan">
                  <option value="">PILIH JENIS TINDAKAN</option>
                  <option value="pemeriksaan_audiometri">KESIMPULAN AUDIOMETRI</option>
                  <option value="pemeriksaan_ekg">KESIMPULAN EKG</option>
                  <option value="pemeriksaan_farmingham_score">KESIMPULAN FARMINGHAM SCORE</option>
                  <option value="pemeriksaan_fisik">KESIMPULAN FISIK</option>
                  <option value="pemeriksaan_laboratorium">KESIMPULAN LABORATORIUM</option>
                  <option value="pemeriksaan_rontgen_lumbosacral">KESIMPULAN RONTGEN LUMBOSACRAL</option>
                  <option value="pemeriksaan_rontgen_thorax">KESIMPULAN RONTGEN THORAX</option>
                  <option value="pemeriksaan_spirometri">KESIMPULAN SPIROMETRI</option>
                  <option value="pemeriksaan_threadmill">KESIMPULAN TREADMILL</option>
                  <option value="pemeriksaan_usg_ubdomain">KESIMPULAN USG ABDOMAIN</option>
                  <option value="saran">KESIMPULAN SARAN</option>
                  <option value="poli_spirometri">PEMERIKSAAN POLI SPIROMETRI</option>
                  <option value="poli_audiometri">PEMERIKSAAN POLI AUDIOMETRI</option>
                  <option value="poli_ekg">PEMERIKSAAN POLI EKG</option>
                  <option value="poli_threadmill">PEMERIKSAAN POLI TREADMILL</option>
                  <option value="poli_rontgen_thorax">PEMERIKSAAN POLI RONTGEN</option>
                  <option value="poli_rontgen_lumbosacral">PEMERIKSAAN POLI RONTGEN LUMBOSACRAL</option>
                  <option value="poli_usg_ubdomain">PEMERIKSAAN POLI USG ABDOMEN</option>
                  <option value="poli_farmingham_score">PEMERIKSAAN POLI FARMINGHAM SCORE</option>
              </select>
              </div>
              <div class="mb-3">
                <label for="kesimpulantindakan" class="form-label">Kesimpulan Tindakan</label>
                <textarea placeholder="Ex: Ambang dengar dalam batas normal" type="text" class="form-control" id="kesimpulantindakan" name="kesimpulantindakan" rows=4></textarea>
              </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <button id="simpan_kesimpulan" class="btn btn-primary">Simpan Data</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('css_load')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
@endsection
@section('js_load')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script src="https://cdn.datatables.net/rowgroup/1.5.1/js/dataTables.rowGroup.min.js"></script>
<script src="{{ asset('vendor/erayadigital/master_data/kesimpulan.js') }}?v={{ filemtime(public_path('vendor/erayadigital/master_data/kesimpulan.js')) }}"></script>
@endsection