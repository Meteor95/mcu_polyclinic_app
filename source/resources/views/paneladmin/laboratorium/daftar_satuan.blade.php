@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Form Tambah Satuan Laboratorium</h4><span>Silahkan isi form berikut untuk menambahkan satuan laboratorium baru.</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <label for="grup_item">Silahkan Tentukan Grup Satuan</label>
                    <select name="grup_item" data-choices id="grup_item" class="form-control">
                        <option value="laboratorium">Laboratorium</option>
                        <option value="non_laboratorium">Non Laboratorium</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label for="nama_satuan">Tentukan Nama Satuan</label>
                    <input type="text" name="nama_satuan" id="nama_satuan" class="form-control" placeholder="Tentukan Nama Satuan">
                </div>
                <div class="col-sm-6 mt-2">
                  <button id="btn_refresh_satuan" class="btn btn-danger w-100"><i class="fa fa-refresh"></i> Bersihkan Form</button>
                </div>
                <div class="col-sm-6 mt-2">
                    <button id="btn_simpan_satuan" class="btn btn-primary w-100"><i class="fa fa-save"></i> Simpan Satuan</button>
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
          <h4>Daftar Satuan Laboratorium</h4><span>Pada daftar ini, anda dapat mengelola satuan laboratorium yang tersedia. Silahkan saring berdasarkan paramter yang tersedia.</span>
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
                <input type="text" class="form-control" id="kotak_pencarian" placeholder="Cari data berdasarkan nama satuan">
            </div>
            <div class="col-md-12">
                <table class="table display table-striped table-bordered table-hover table-padding-sm" id="datatables_satuan"></table>
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
<script src="{{ asset('vendor/erayadigital/laboratorium/satuan_labot.js') }}"></script>
@endsection
