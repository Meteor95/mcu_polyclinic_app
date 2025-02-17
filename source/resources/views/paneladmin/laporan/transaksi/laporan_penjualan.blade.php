@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Daftar Laporan Pendapatan {{ config('app.name') }}</h4><span>
            Semua transkasi dari berbagai jenis layanan baik MCU atau NON MCU akan terekam pada tabel berikut. Silahkan lakukan pencarian sesuai dengan kriteria yang diinginkan, jika terdapat kesalahan silahkan hubungi admin atau hak akses yang diizinkan untuk melakukan penyesuaian data.
          </span>
        </div>
        <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <label for="tanggal_awal">Tanggal Awal</label>
                <input type="text" class="form-control" id="tanggal_awal" placeholder="Pilih Tanggal Awal">
              </div>
              <div class="col-md-6">
                <label for="tanggal_akhir">Tanggal Akhir</label>
                <input type="text" class="form-control" id="tanggal_akhir" placeholder="Pilih Tanggal Akhir">
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-xl-4">
                  <div class="card">
                      <div class="card-body p-0">
                          <div class="alert alert-warning border-0 rounded-top rounded-0 m-0 d-flex align-items-center" role="alert">
                              <i class="fa-solid fa-triangle-exclamation"></i>
                              <div class="flex-grow-1 text-truncate">
                                  Kejar Target <b>Tindakan</b> Anda
                              </div>
                          </div>
                          <div class="row align-items-end">
                              <div class="col-sm-8">
                                  <div class="p-3">
                                      <p class="fs-16 lh-base">Silahkan analisa usaha anda berdasarkan <span class="fw-semibold">Laporan Tindakan</span>, "Tindakan MCU atau NON MCU" <i class="mdi mdi-arrow-right"></i></p>
                                      <div class="mt-3">
                                          <a href="javascript:void(0)" id="btn_transaksi_tindakan" class="btn btn-success" onclick="report_show_modal('transaksi_tindakan',this.id)">Lihat Laporan!</a>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-sm-4">
                                  <dotlottie-player style="p-3" src="https://lottie.host/35de7bd1-ae92-488b-8662-11f1fbb55de6/WS5bYjWD3t.lottie" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player>
                              </div>
                          </div>
                      </div> <!-- end card-body-->
                  </div>
              </div> <!-- end col-->
              <div class="col-xl-4">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="alert alert-warning border-0 rounded-top rounded-0 m-0 d-flex align-items-center" role="alert">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <div class="flex-grow-1 text-truncate">
                                Ingin Lihat <b>Tindakan Detail</b> Anda
                            </div>
                        </div>
                        <div class="row align-items-end">
                            <div class="col-sm-8">
                                <div class="p-3">
                                    <p class="fs-16 lh-base">Silahkan analisa usaha anda berdasarkan <span class="fw-semibold">Laporan Tindakan</span>, "Transaksi Tindakan Detail" <i class="mdi mdi-arrow-right"></i></p>
                                    <div class="mt-3">
                                        <a href="javascript:void(0)" id="btn_transaksi_tindakan_detail" class="btn btn-success" onclick="report_show_modal('transaksi_tindakan_detail',this.id)">Lihat Laporan!</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <dotlottie-player style="p-3" src="https://lottie.host/acd7f620-b8cf-41f2-885a-1bd71c64b8b3/2R328f3prn.lottie" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player>
                            </div>
                        </div>
                    </div> <!-- end card-body-->
                </div>
              </div> <!-- end col-->
              <div class="col-xl-4">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="alert alert-warning border-0 rounded-top rounded-0 m-0 d-flex align-items-center" role="alert">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <div class="flex-grow-1 text-truncate">
                                Ayo Lakukan Tingkatkan <b>Kegiatan</b> Pasien Terbanyak
                            </div>
                        </div>
                        <div class="row align-items-end">
                            <div class="col-sm-8">
                                <div class="p-3">
                                    <p class="fs-16 lh-base">Silahkan analisa usaha anda berdasarkan <span class="fw-semibold">Laporan Tindakan</span>, "Tindakan Pasien Terbanyak" <i class="mdi mdi-arrow-right"></i></p>
                                    <div class="mt-3">
                                        <a href="javascript:void(0)" id="btn_transaksi_tindakan_terbanyak" class="btn btn-success" onclick="report_show_modal('transaksi_tindakan_terbanyak',this.id)">Lihat Laporan!</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <dotlottie-player style="p-3" src="https://lottie.host/d7b9c53b-ddde-4be3-9c8d-50b4ce768380/4XjsNXVBif.lottie" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player>
                            </div>
                        </div>
                    </div> <!-- end card-body-->
                </div>
            </div> <!-- end col-->
            </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="report_show_modal" tabindex="-1" role="dialog" aria-labelledby="report_show_modalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-fullscreen" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="report_show_modalLabel">Detail Transaksi Tindakan <span id="no_trx"></span></h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 mb-2">
                  <h5 class="modal_title" id="modal_title">Laporan Tindakan</h5>
              </div>
              <div class="col-md-6 mb-2">
                  <select id="select_page_length_transaksi_tindakan" name="select_page_length_transaksi_tindakan" class="form-select">
                      <option value="0">Semua Data</option>
                      <option value="1">1 Data</option>
                      <option value="10">10 Data</option>
                      <option value="25">25 Data</option>
                      <option value="50">50 Data</option>
                      <option selected value="100">100 Data</option>
                      <option value="500">500 Data</option>
                  </select>
              </div>
              <div class="col-md-6 mb-2">
                <input type="text" id="searchInput_transaksi_tindakan" name="searchInput_transaksi_penjualan" class="form-control" placeholder="Ketikan: Nomor Invoice, Nama Kasir, Nama Pelanggan">
              </div>
              <div class="col-md-2">
                <select id="status_pembayaran" class="form-select">
                  <option value="" selected>Pilih Status Pembayaran</option>
                  <option value="">Semua</option>
                  <option value="process">Proses</option>
                  <option value="pending">Ditahan</option>
                  <option value="done">Selesai</option>
                </select>
              </div>
              <div class="col-md-2">
                <select id="jenis_layanan" class="form-select">
                  <option value="">Pilih Jenis Layanan</option>
                  <option value="">Semua</option>
                  <option value="MCU">MCU</option>
                  <option value="Follow_Up">Follow Up</option>
                  <option value="Berobat">Berobat</option>
                  <option value="MCU_Tambahan">MCU Tambahan</option>
                  <option value="MCU_Threadmill">MCU Threadmill</option>
                  <option value="Threadmill">Threadmill</option>
                </select>
              </div>
              <div class="col-md-2">
                <select id="jenis_transaksi" class="form-select">
                  <option value="">Pilih Jenis Transaksi</option>
                  <option value="">Semua</option>
                  <option value="0">Invoices</option>
                  <option value="1">Tunai</option>
                  <option value="2">Non Tunai</option>
                </select>
              </div>
              <div class="col-sm-12 col-md-12 col-lg-2">
                  <button id="modal_proses_data_transaksi_tindakan" class="btn btn-success w-100">
                      <i class="fas fa-search"></i> Proses Saring
                  </button>
              </div>
              <div class="col-sm-12 col-md-12 col-lg-2">
                  <button disabled id="modal_cetak_pdf_transaksi_tindakan" class="btn btn-danger w-100">
                      <i class="fas fa-file-pdf-o"></i> Cetak PDF
                  </button>
              </div>
              <div class="col-sm-12 col-md-12 col-lg-2">
                  <button class="btn btn-secondary w-100" data-bs-dismiss="modal" aria-label="Close">
                      <i class="fas fa-times"></i> Tutup
                  </button>
              </div>
          </div>
            <div class="row">
                <div class="col-12">
                    <table id="datatables_laporan_tindakan" class="table table-bordered table-striped table-hover table-padding-sm"></table>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="row">
              <div class="col-12">
                  <table class="table table-padding-sm-no-datatable">
                        <tr id="footer_total_apotek">
                            <td class="fw-bold">SUB TOTAL APOTEK</td>
                            <td class="fw-bold text-end"><span id="total_apotek">0</span></td>
                        </tr>
                        <tr id="footer_tindakan">
                            <td class="fw-bold">SUB TOTAL TINDAKAN</td>
                            <td class="fw-bold text-end"><span id="total_tindakan">0</span></td>
                        </tr>
                        <tr id="footer_total_all">
                            <td class="fw-bold">GRAND TOTAL PENJUALAN</td>
                            <td class="fw-bold text-end"><span id="total_all">0</span></td>
                        </tr>
                  </table>
              </div>
          </div>
          </div>
      </div>
  </div>
</div>
@endsection
@section('css_load')
<link rel="stylesheet" type="text/css" href="{{ asset('mofi/assets/css/vendors/flatpickr/flatpickr.min.css') }}">
<link href="https://fonts.cdnfonts.com/css/ds-digital" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.5.1/css/rowGroup.dataTables.min.css">
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
<script src="{{ asset('mofi/assets/js/flat-pickr/flatpickr.js') }}"></script>
<script src="https://cdn.datatables.net/rowgroup/1.5.1/js/dataTables.rowGroup.min.js"></script>
<script src="{{ asset('vendor/erayadigital/laporan/laporan_penjualan.js') }}"></script>
@endsection