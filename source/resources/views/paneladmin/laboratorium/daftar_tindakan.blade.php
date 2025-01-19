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
            <div class="row">
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
<div class="modal fade" id="modalDetailTindakan" tabindex="-1" role="dialog" aria-labelledby="modalDetailTindakanLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-fullscreen" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalDetailTindakanLabel">Detail Transaksi Tindakan <span id="no_trx"></span></h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-3">
                <h6 class="f-w-600">No MCU</h6>
                <p id="no_mcu_label"></p>
              </div>
              <div class="col-sm-3">
                <h6 class="f-w-600">Waktu Transaksi</h6>
                <p id="waktu_transaksi_label"></p>
              </div>
              <div class="col-sm-3">
                <h6 class="f-w-600">Waktu Sample</h6>
                <p id="waktu_sample_label"></p>
              </div>
              <div class="col-sm-3">
                <h6 class="f-w-600">Dibuat Tanggal</h6>
                <p id="dibuat_tanggal_label"></p>
              </div>
              <div class="col-sm-3">
                <h6 class="f-w-600">Nama Dokter</h6>
                <p id="nama_dokter_label"></p>
              </div>
              <div class="col-sm-3">
                <h6 class="f-w-600">Nama Penanggung Jawab</h6>
                <p id="nama_penanggung_jawab_label"></p>
              </div>
              <div class="col-sm-3">
                <h6 class="f-w-600">Nama Paket</h6>
                <p id="nama_paket_label"></p>
              </div>
              <div class="col-sm-3">
                <h6 class="f-w-600">Surat Pengantaran</h6>
                <p id="surat_pengantaran_label"></p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div id="total_pendapatan_text">Total TRX</div>
              </div>
              <div class="col-md-6 text-end">
                <div id="total_pendapatan">0</div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <h1 style="text-align: center;">Daftar Tindakan</h1>
                <table id="table_tindakan_lab_modal" class="table table-striped table-bordered table-hover table-padding-sm">
                    <thead>
                      <tr style="text-align: center;">
                        <th>No</th>
                        <th>Kode Tindakan</th>
                        <th>Nama Tindakan</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Setelah Diskon</th>
                        <th>Jumlah</th>
                        <th>Sub Total</th>
                      </tr>
                    </thead>
                </table>
              </div>
              <div class="col-md-12">
                <h1 style="text-align: center;">Daftar Fee</h1>
                <table id="table_fee_lab_modal" class="table table-striped table-bordered table-hover table-padding-sm">
                    <thead>
                      <tr style="text-align: center;">
                        <th>No</th>
                        <th>Nama Tindakan</th>
                        <th>Jenis Fee</th>
                        <th>Penerima Fee</th>
                        <th>Diterima</th>
                        <th>Besaran Fee</th>
                      </tr>
                    </thead>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div id="button_edit_transaksi"></div>
          </div>
      </div>
  </div>
</div>
@endsection
@section('css_load')
<link href="https://fonts.cdnfonts.com/css/ds-digital" rel="stylesheet">
<style>
  #total_pendapatan_text, #total_pendapatan{
    font-family: 'DS-Digital', sans-serif;
    font-size: 60px;
    color: red;
    padding: 0; 
    line-height: 1;
    font-weight: bold;
  }
</style>
@endsection
@section('js_load')
<script src="{{ asset('vendor/erayadigital/laboratorium/daftar_tindakan.js') }}"></script>
@endsection