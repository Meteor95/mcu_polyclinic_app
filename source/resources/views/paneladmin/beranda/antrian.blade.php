@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header" style="text-align: center">
          <h4>Tantukan Antrian Untuk Pasien</h4><span>Silahkan pilih kategori antrian kepada pasien yang akan datang, pasien yang sudah masuk ke antrian kategori tertentu tidak bisa dimasukan lagi ke kategori yang lain sampai dia dianggap selesai oleh sistem. Usahakan jangan sampai salah kategori. </span>
          <div class="row">
            <div class="col-sm-12 col-md-10">
                <select class="form-select" id="kategori_antrian" name="kategori_antrian" style="cursor: pointer">
                    <option value="">Silahkan Pilih Lokasi Kategori Antrian</option>
                  <optgroup label="Poliklinik">
                    <option value="tanda_vital">Tanda Vital</option>
                    <option value="spirometri">Poli Spirometri</option>
                    <option value="audiometri">Poli Audiometri</option>
                    <option value="ekg">Poli EKG</option>
                    <option value="threadmill">Poli Threadmill</option>
                    <option value="rontgen_thorax">Poli Ronsen Thorax</option>
                    <option value="rontgen_lumbosacral">Poli Ronsen Lumbosacral</option>
                    <option value="usg_ubdomain">Poli USG</option>
                    <option value="farmingham_score">Farmingham Score</option>
                  </optgroup>
                  <optgroup label="Non Poliklinik">
                    <option value="poli_dokter">Poli Dokter</option>
                    <option value="kesimpulan">Proses Pemberi Kesimpulan</option>
                  </optgroup>
                </select>
              </div>
              <div class="col-sm-12 col-md-2">
                <div style="display: flex; justify-content: center;" class="gap-2">
                  <button class="btn btn-primary" id="tombol_tantukan_antrian"><i class="fa fa-add"></i></button>
                  <button class="btn btn-success" id="segarkan_tantukan_antrian"><i class="fa fa-refresh"></i></button>
                </div>
              </div>
          </div>
          </div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-12 col-md-3 mb-2">
                <select class="form-select" id="status_antrian_sekarang">
                  <option value="">Semua Status Antrian</option>
                  <option value="0">Masih Antri</option>
                  <option value="2">Proses</option>
                  <option value="1">Selesai</option>
                </select>
            </div>
            <div class="col-sm-12 col-md-6 mb-2">
              <input type="text" class="form-control" id="pencarian_tabel_antrian" placeholder="Masukan kata kunci pencarian data antrian tersedia">
            </div>
            <div class="col-sm-12 col-md-3 mb-2">
              <div style="display: flex; justify-content: center;" class="gap-2">
                  <button class="btn btn-primary w-100" id="cari_antrian_tersedia"><i class="fa fa-search"></i> Cari Data</button>
              </div>
            </div>
          </div>
          <table class="table table-striped table-bordered table-hover table-padding-sm" id="tabel_antrian_data"></table>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="modal_pilih_pasien_antrian" tabindex="-1" role="dialog" aria-labelledby="modal_pilih_pasien_antrianLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Pilih Pasien Yang Diantrikan : <span id="modal_pilih_pasien_antrian_text"></span></h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-center">
                        <h3>Pilih Pasien Yang Diantrikan</h3>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card-body">
                      <input type="text" class="form-control" style="" placeholder="Ketikan ID PENDAFTARAN yang ingin diantrikan">
                      <table id="table_peserta_antrian" class="table table-striped table-bordered table-hover table-padding-sm"></table>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>>
@endsection
@section('css_load')
@endsection
@section('js_load')
<script src="{{ asset('vendor/erayadigital/beranda/antrian.js') }}"></script>
@endsection