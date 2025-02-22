@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row" id="on_top_formulir_tambah_tarif">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4 style="margin-top: 5px;">Formulir Buat Templat Laboratorium</h4>
                <button class="btn btn-primary text-white float-end" id="collapse_formulir">Tampilkan Formulir</button>
            </div>
        </div>
        <div class="card-body collapse hide" id="formulir_buat_templat">
            <div class="row g-2">
              <div class="col-sm-12">
                <label for="paket_mcu_tersedia">Hubungkan Dengan Paket MCU Tersedia</label>
                <select name="paket_mcu_tersedia" id="paket_mcu_tersedia" class="form-select"></select>
              </div>
              <div class="col-sm-12">
                  <input type="text" class="form-control" id="nama_template" placeholder="Nama Template">
              </div>
              <div class="col-md-2">
                <select id="data_ditampilkan" class="form-select">
                  <option value="0" selected>Semua</option>
                  <option value="10">10 Data</option>
                  <option value="25">25 Data</option>
                  <option value="50">50 Data</option>
                  <option value="100">100 Data</option>
                  <option value="500">500 Data</option>
              </select>
              </div>
              <div class="col-md-2">
                <select id="jenis_item_tampilkan" class="form-select">
                  <option value="">Semua</option>
                  <option selected value="laboratorium">Laboratorium</option>
                  <option value="non_laboratorium">Non Laboratorium</option>
                </select>
              </div>
              <div class="col-md-8">
                <input type="text" class="form-control" id="kotak_pencarian" placeholder="Cari berdasarkan kode, nama tindakan, atau kelompok tindakan">
              </div>
              <div class="col-sm-12">
                <label for="paket_tindakan_lab">Tentukan Paket Tindakan Laboratorium dan Pengobatan</label>
                <table id="table_paket_tindakan_lab" class="table table-striped table-bordered table-hover table-padding-sm"></table>
              </div>
              <div class="col-sm-6">
                  <button class="btn btn-danger w-100" id="btn_bersihkan_formulir"><i class="fa fa-refresh"></i> Bersihkan Formulir</button>
              </div>
              <div class="col-sm-6">
                  <button class="btn btn-primary w-100" id="btn_simpan_templat_laboratorium"><i class="fa fa-database"></i> Simpan Templat Lab</button>
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
          <h4>Daftar Tindakan Laboratorium dan Pengobatan</h4><span>Berikut adalah daftar tindakan yang tersedia untuk pemeriksaan laboratorium dan pengobatan. Silahkan buatkan template untuk mempermudah proses transaksi lab pada fitur transaksi lab. Jikalau tindakan ini sudah tertransaksi pada pasien usahakan untuk tidak menghapusnya silahkan lakukan penonaktifkan saja.</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                  <select id="data_ditampilkan_tindakan" class="form-select">
                    <option value="0">Semua</option>
                    <option value="10" selected>10 Data</option>
                    <option value="25">25 Data</option>
                    <option value="50">50 Data</option>
                    <option value="100">100 Data</option>
                    <option value="500">500 Data</option>
                </select>
                </div>
                <div class="col-md-10">
                  <input type="text" class="form-control" id="kotak_pencarian_tindakan" placeholder="Cari berdasarkan nama template">
                </div>
              </div>
            <table id="table_tindakan_lab" class="table table-striped table-bordered table-hover table-padding-sm"></table>
        </div>
      </div>
    </div>
</div>
@endsection
@section('css_load')
<link href="https://cdn.datatables.net/keytable/2.12.1/css/keyTable.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<style>
    table.dataTable tbody td.focus {
      outline: 1px dotted rgb(255, 153, 0);
      outline-offset: -2px;
    }
    .clicked-row {
      background-color: pink !important;
    }
</style>
@endsection
@section('js_load')
<script src="https://cdn.datatables.net/keytable/2.12.1/js/dataTables.keyTable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="{{ asset('vendor/erayadigital/laboratorium/templating.js') }}"></script>
@endsection
