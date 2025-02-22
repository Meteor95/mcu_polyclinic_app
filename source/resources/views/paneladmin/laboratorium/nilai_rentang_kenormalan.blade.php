@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Form Tambah Nilai Rentang Kenormalan</h4><span>Silahkan isi form berikut untuk menambahkan nilai rentang kenormalan baru.</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <label for="umur_awal">Masukan Rentang Umur (Awal dan Akhir 0 Untuk Semua Umur)</label>
                    <div class="input-group">
                        <input class="form-control" id="umur_awal" type="text" placeholder="Awal" aria-label="Awal"><span class="input-group-text">Sampai</span>
                        <input class="form-control" id="umur_akhir" type="text" placeholder="Akhir" aria-label="Akhir">
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="nama_rentang_kenormalan">Nama Rentang Kenormalan</label>
                    <input class="form-control" id="nama_rentang_kenormalan" type="text" placeholder="Nama Rentang Kenormalan" aria-label="Nama Rentang Kenormalan">
                </div>
                <div class="col-sm-2">
                    <label for="nama_satuan">Jenis Kelamin</label>
                    <select class="form-select" id="jenis_kelamin" data-choices>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                        <option value="LP">Semua Jenis Kelamin</option>
                    </select>
                </div>
                <div class="col-sm-6 mt-2">
                  <button id="btn_refresh_rentang_kenormalan" class="btn btn-danger w-100"><i class="fa fa-refresh"></i> Bersihkan Form</button>
                </div>
                <div class="col-sm-6 mt-2">
                    <button id="btn_simpan_rentang_kenormalan" class="btn btn-primary w-100"><i class="fa fa-save"></i> Simpan Nilai Rentang</button>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Daftar Nilai Rentang Kenormalan</h4><span>Pada daftar ini, anda dapat mengelola nilai rentang kenormalan yang tersedia. Silahkan saring berdasarkan paramter yang tersedia.</span>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-2">
                <select id="data_ditampilkan" class="form-select">
                  <option value="10">10 Data</option>
                  <option value="25">25 Data</option>
                  <option value="50">50 Data</option>
                  <option value="100">100 Data</option>
                  <option value="500">500 Data</option>
              </select>
            </div>
            <div class="col-md-10">
                <input type="text" class="form-control" id="kotak_pencarian" placeholder="Cari data berdasarkan nama rentang kenormalan">
            </div>
            <div class="col-md-12">
                <table class="table display table-striped table-bordered table-hover table-padding-sm" id="datatables_rentang_kenormalan"></table>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
@section('css_load')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
@endsection
@section('js_load')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="{{ asset('vendor/erayadigital/laboratorium/rentang_nilai_kenormalan.js') }}"></script>
@endsection
