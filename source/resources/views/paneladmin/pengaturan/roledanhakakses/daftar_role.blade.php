@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
    <div class="card">
        <div class="card-header">
          <h4>Fitur Aplikasi Pengaturan Role Pengguna</h4><span>Pada tampilan ini, Anda dapat mengatur dan mengelola role pengguna di dalam sistem MCU Artha Medica. Dengan fitur ini, administrator dapat menentukan hak akses bagi setiap role, termasuk akses ke menu, izin simpan, cetak, dan fitur lainnya. Setiap perubahan role akan berlaku setelah pengguna melakukan login ulang ke sistem, memastikan hak akses yang baru diterapkan sesuai dengan kebutuhan operasional.</span>
        </div>
        <div class="card-body">
          <div class="col-md-12">
            <input type="text" class="form-control" id="kotak_pencarian_role" placeholder="Cari data berdasarkan nama role yang tersedia">
            <div class="table">
              <table class="display" id="datatables_role"></table>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
            <button class="btn btn-outline-success w-100" id="tambah_role_baru" type="button"><i class="fa fa-plus"></i> Formulir Tambah Role Baru</button>
        </div>
        <div class="card-body">
          <div class="col-md-12 position-relative">
            <input type="hidden" id="id_role">
            <label class="form-label" for="nama_role">Nama Role Pengguna</label>
            <input class="form-control" type="text" placeholder="Ex: Administrator" id="nama_role">
            <label class="form-label" for="keterangan_role">Keterangan Role Pengguna</label>
            <input class="form-control" type="text" placeholder="Ex: Status role pengguna paling tinggi pada aplikasi MCU Artha Medica" id="keterangan_role">
          </div>
          <div class="title text-center mt-2">
            <h2 class="sub-title">Role Yang Tersedia Pada Sistem {{ config('app.name') }}</h2>
          </div>
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-2">
                <select id="data_ditampilkan" class="form-select">
                  <option value="10">10 Data</option>
                  <option value="25">25 Data</option>
                  <option value="50">50 Data</option>
                  <option value="100">100 Data</option>
                  <option selected value="500">500 Data</option>
              </select>
              </div>
              <div class="col-md-10">
                <div class="input-group">
                  <input type="text" class="form-control" id="kotak_pencarian" placeholder="Cari data berdasarkan nama akses permission yang tersedia">
                  <button class="btn btn-success" id="simpan_role" type="button"><i class="fa fa-save"></i> Simpan Data</button>
                </div>
              </div>
            </div>
            <div class="table">
              <table class="display" id="datatables_permission_tersedia"></table>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
@section('css_load')
<style>
#datatables_permission_tersedia tbody td,
#datatables_role tbody td {
    padding-top: 2px;
    padding-bottom: 2px;
}
</style>
@endsection
@section('js_load')
<script src="{{asset('vendor/erayadigital/auth/role.js')}}"></script>
@endsection