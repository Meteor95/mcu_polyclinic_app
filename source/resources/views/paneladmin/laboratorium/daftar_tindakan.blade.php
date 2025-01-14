@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Daftar Tindakan Laboratorium dan Pengobatan Artha Medica Centre</h4><span>
            Semua transkasi dari berbagai jenis layanan baik MCU atau NON MCU akan terekam pada tabel berikut. Silahkan lakukan pencarian sesuai dengan kriteria yang diinginkan, jika terdapat kesalahan silahkan hubungi admin atau hak akses yang diizinkan untuk melakukan penyesuaian data.
          </span>
          <div class="row mt-2">
            <div class="col-sm-12">
              <a href="{{ route('admin.laboratorium.tindakan') }}" target="_blank" class="btn btn-amc-orange w-100"><i class="fa fa-flask"></i> Tambah Informasi Tindakan Baru</a>
            </div>
          </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <select id="data_ditampilkan" class="form-select">
                      <option value="10">10 Data</option>
                      <option selected value="25">25 Data</option>
                      <option value="50">50 Data</option>
                      <option value="100">100 Data</option>
                      <option value="500">500 Data</option>
                  </select>
                  </div>
                  <div class="col-md-2">
                    <select id="jenis_item_tampilkan" class="form-select">
                      <option value="">Semua</option>
                      <option value="laboratorium">Laboratorium</option>
                      <option value="non_laboratorium">Non Laboratorium</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                    <select id="jenis_layanan" class="form-select">
                      <option value="">Semua</option>
                      <option value="MCU">MCU</option>
                      <option value="Follow_Up">Follow Up</option>
                      <option value="Berobat">Berobat</option>
                      <option value="MCU-Tambahan">MCU-Tambahan</option>
                      <option value="MCU-Threadmill">MCU-Threadmill</option>
                      <option value="Threadmill">Threadmill</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <input type="text" class="form-control" id="kotak_pencarian" placeholder="Cari data berdasarkan kode, nama item, group item, kategori, atau satuan">
                  </div>
                </div>
                <hr>
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table id="daftar_table_tindakan" class="table table-bordered table-striped table-hover table-padding-sm"></table>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
@endsection
@section('css_load')
@endsection
@section('js_load')
<script src="{{ asset('vendor/erayadigital/laboratorium/daftar_tindakan.js') }}"></script>
@endsection