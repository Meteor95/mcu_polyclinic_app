@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Daftar Peserta</h4><span>Peserta yang terdaftar pada sistem dan belum mendapatkan jadwal. Informasi yang ditampikan berasal dari pendataran via website atau pendaftaran langsung yang datang ke lokasi MCU Arta Medica Clinic. Daftar peserta akan dihapus secara otomatis setelah 7 hari dari tanggal pendaftaran agar tidak menyebabkan duplikasi data.</span>
        </div>
        <div class="card-body">
          <input type="text" class="form-control" id="kotak_pencarian_daftarpeserta" placeholder="Cari data berdasarkan nama peserta">
          <div class="table">
            <table class="table display" id="datatables_daftarpeserta"></table>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
@section('css_load')
@component('komponen.css.datatables')
@endcomponent
@endsection
@section('js_load')
@component('komponen.js.datatables')
@endcomponent
<script src="{{ asset('mofi/assets/js/system/pendaftaran/peserta.js') }}"></script>
@endsection
