@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Daftar Tindakan Laboratorium dan Pengobatan Artha Medica Centre</h4><span>
            Silahkan lakukan pentagihan pada pasien yang sudah melakukan pendaftaran dan terdaftar pada tabel berikut. Halaman ini sebagai penentu apakah transaksi yang dilakukan oleh peserta sudah selesai atau belum dalam hal administrasi pembayaran.
          </span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                  <label for="data_ditampilkan">Data yang ditampilkan</label>
                    <select id="data_ditampilkan" class="form-select">
                      <option value="10">10 Data</option>
                      <option selected value="25">25 Data</option>
                      <option value="50">50 Data</option>
                      <option value="100">100 Data</option>
                      <option value="500">500 Data</option>
                    </select>
                </div>
                <div class="col-md-2">
                  <label for="status_pembayaran">Status Pembayaran</label>
                    <select id="status_pembayaran" class="form-select">
                      <option value="">Semua</option>
                      <option value="paid">Lunas</option>
                      <option value="process">Proses</option>
                      <option value="pending">Ditahan</option>
                      <option value="done">Selesai</option>
                    </select>
                </div>
                <div class="col-md-2">
                  <label for="jenis_layanan">Jenis Layanan</label>
                    <select id="jenis_layanan" class="form-select">
                      <option value="">Semua</option>
                      <option value="MCU">MCU</option>
                      <option value="Follow_Up">Follow Up</option>
                      <option value="Berobat">Berobat</option>
                      <option value="MCU_Tambahan">MCU Tambahan</option>
                      <option value="MCU_Threadmill">MCU Threadmill</option>
                      <option value="Threadmill">Threadmill</option>
                    </select>
                </div>
                <div class="col-md-6">
                  <label for="kotak_pencarian">Cari Data</label>
                    <input type="text" class="form-control" id="kotak_pencarian" placeholder="Cari data berdasarkan No MCU, Nama Penanggung Jawab, Nama Pasien atau Nama Dokter">
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
            </div>
          </div>
          <div class="modal-footer">
            <div id="button_edit_transaksi"></div>
          </div>
      </div>
  </div>
</div>

<div class="modal modal-xl fade" id="modalKonfimasiPendaftaran" tabindex="-1" role="dialog" aria-labelledby="modalKonfimasiPendaftaranLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahPenggunaLabel">Konfirmasi Pembayaran Pasien</h5>
                <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-4">
                        <span class="text-start" style="margin-top: 5px; padding-right: 10px; font-family: 'DS-Digital', sans-serif; font-size: 30px; color: red;">
                            <strong>Grand Total</strong>
                        </span>
                    </div>
                    <div class="col-md-8">
                        <input type="text" value="0" class="form-control text-end" id="nominal_bayar_konfirmasi" name="nominal_bayar_konfirmasi" placeholder="0.00" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <h4 class="text-start mt-1">Tipe Pembayaran</h4>
                    </div>
                    <div class="col-md-8">
                        <div class="mega-inline">
                            <div class="row">
                                <div class="col-sm-6">
                                <div id="card-hutang" class="card">
                                    <div class="d-flex p-20">
                                    <div class="form-check radio radio-primary m-0">
                                        <input checked class="form-check-input" id="hutang" type="radio" name="tipe_pembayaran" value="0">
                                        <label class="form-check-label" for="hutang"><span class="flex-grow-1 megaoption-space"><span class="mt-0 mega-title-badge">Invoices<span class="badge badge-primary pull-right digits">Hutang</span></span><span>Transaksi MCU ini akan dicatat sebagai hutang berdasarkan No Identitas Member</span></span></label>
                                    </div>
                                    </div>
                                </div>
                                </div>
                                <div class="col-sm-6">
                                <div id="card-tunai" class="card">
                                    <div class="d-flex p-20">
                                    <div class="form-check radio radio-secondary m-0">
                                        <input class="form-check-input" id="tunai" type="radio" name="tipe_pembayaran" value="1">
                                        <label class="form-check-label" for="tunai"><span class="flex-grow-1 megaoption-space"><span class="mt-0 mega-title-badge">Tunai Langsung<span class="badge badge-secondary pull-right digits">Tunai</span></span><span>Transaksi ini akan dicatat sebagai Kas Kecil berdasarkan No Transaksi</span></span></label>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="pembayaran_tunai">
                    <div class="row">
                        <div class="col-md-4">
                            <h4 class="text-start mt-2">Metode Pembayaran</h4>
                        </div>
                        <div class="col-md-8">
                            <select class="form-select mt-2" id="select2_metode_pembayaran" name="select2_metode_pembayaran" required>
                                <option value="0">Tunai</option>
                                <option value="1">Transfer</option>
                            </select>  
                        </div>
                    </div>
                    <div class="row transaksi_transfer">
                        <div class="col-md-4">
                            <h4 class="text-start mt-2">Penerima Bank</h4>
                        </div>
                        <div class="col-md-8">
                            <select class="form-select mt-2" id="beneficiary_bank" name="beneficiary_bank" required>
                                @foreach ($data['bank'] as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_bank }}</option>
                                @endforeach
                            </select>  
                        </div>
                    </div>
                    <div class="row transaksi_transfer">
                        <div class="col-md-4">
                            <h4 class="text-start mt-2">Nomor Transaksi Transfer</h4>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control mt-2" id="nomor_transaksi_transfer" name="nomor_transaksi_transfer" placeholder="Ex: 12002158840023654">
                        </div>
                    </div>
                    <div class="row transaksi_tunai">
                        <div class="col-md-4">
                            <h4 class="text-start mt-2">Nominal Pembayaran</h4>
                        </div>
                        <div class="col-md-8">
                        <input type="text" class="form-control mt-2" id="nominal_bayar" name="nominal_bayar" placeholder="0.00">
                        </div>
                    </div>
                    <div class="row transaksi_tunai">
                        <div class="col-md-4">
                            <h4 class="text-start mt-2">Kembalian Pembayaran</h4>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control mt-2" id="nominal_kembalian" name="nominal_kembalian" placeholder="0.00" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary" id="btnSimpanPendaftaran">
                    <i class="fa fa-save"></i> Simpan Data
                </button>
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
<script src="{{ asset('vendor/erayadigital/laboratorium/kasir.js') }}"></script>
@endsection