@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Rekap Pemeriksaan Tanda Vital Pada Perusahaan</h4><span>Pada tabel ini adalah rekap pemeriksaan tanda vital pada perusahaan, silahkan pilih perusahaan dan periode untuk melihat rekap pemeriksaan tanda vital. Jadi nantinya tim AMC dapat melihat seberapa banyak perusahaan mengirimkan pegawainya untuk dicek kesehatannya di AMC ini</span>
        </div>
        <div class="card-body">
            <div class="row mb-2">
              <div class="col-md-6">
                <label for="tanggal_awal">Tanggal Awal</label>
                <input type="text" class="form-control" id="tanggal_awal" placeholder="Pilih Tanggal Awal">
              </div>
              <div class="col-md-6">
                <label for="tanggal_akhir">Tanggal Akhir</label>
                <input type="text" class="form-control" id="tanggal_akhir" placeholder="Pilih Tanggal Akhir">
              </div>
            </div>
            <div class="row mb-2">
              <!-- Kolom 1 -->
              <div class="col-md-6">
                <input type="text" class="form-control" id="kotak_pencarian_daftarpasien" placeholder="Cari data berdasarkan nama peserta">
              </div>
            
              <!-- Kolom 2 -->
              <div class="col-md-6">
                <select class="form-select" onchange="filter_perusahaan(this.value)" id="filter_perusahaan" name="filter_perusahaan" style="cursor: pointer">
                  <option value="">Semua Perusahaan</option>
                </select>
              </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-12">
                    <button id="btn_baca_data_rekap" class="btn btn-block w-100 btn-success"><i class="fa fa-search"></i> Baca Data Rekap </button>
                </div>
            </div>
            <hr>
            <div class="table-responsive theme-scrollbar">
                <table class="display" id="datatables_vital_perusahaan"></table>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="modal_vital_detail" tabindex="-1" role="dialog" aria-labelledby="modal_vital_detailLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_vital_detail_text">Detail Peserta</h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
               <table  class="table table-bordered table-striped table-hover table-padding-sm" id="datatables_vital_perusahaan_detail" ></table>
          </div>
          <div class="modal-footer">
            
          </div>
        </div>
    </div>
</div>
@endsection
@section('css_load')
<link rel="stylesheet" type="text/css" href="{{ asset('mofi/assets/css/vendors/flatpickr/flatpickr.min.css') }}">
@endsection
@section('js_load')
<script src="{{ asset('mofi/assets/js/flat-pickr/flatpickr.js') }}"></script>
<script src="https://cdn.datatables.net/rowgroup/1.5.1/js/dataTables.rowGroup.min.js"></script>
<script src="{{ asset('vendor/erayadigital/laporan/rekap_vital.js') }}?v={{ filemtime(public_path('vendor/erayadigital/laporan/rekap_vital.js')) }}"></script>
@endsection