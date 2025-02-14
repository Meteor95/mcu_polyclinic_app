@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
  <div class="col-md-12">
    <div class="card common-hover">
      <div class="card-header border-l-primary border-3">
        <h4>Status dan Posisi Peserta</h4>
        <p>Informasi pada tabel ini akan menghilang jikalau peserta sudah selesai dan tervalidasi oleh dokter pada menu VALIDASI MCU baik Paket atau Non Paket</p>
      </div>
      <div class="card-body">
        <div class="input-group mb-3 gap-2">
          <input type="text" class="form-control" id="kotak_pencarian_daftarpasien" placeholder="Cari data berdasarkan nama peserta">
          <button class="btn btn-primary" id="segarkan_antrian"><i class="fa fa-refresh"></i> Segarkan</button>
          <button class="btn btn-success" id="cek_kosong_semua"><i class="fa fa-refresh"></i> Cek Yang Kosong Semua</button>
        </div>
        <table id="daftar_status_peserta_beranda" class="table table-striped table-bordered table-hover table-padding-sm"></table>
        LEGEND : <br>
        <span class="badge bg-danger">-</span> : Belum terinput<br>
        <span class="badge bg-success">Selesai</span> : Peserta sudah selesai dan meninggalkan ruangan<br>
        <span class="badge bg-warning">Proses</span> : Peserta sedang diproses oleh dokter<br>
        <span class="badge bg-primary">Mengantri</span> : Peserta sedang mengantri pada ruangan
      </div>
    </div>
  </div>
</div>
@endsection
@section('css_load')
@endsection
@section('js_load')
<script src="{{ asset('vendor/erayadigital/beranda/beranda.js') }}"></script>
@endsection