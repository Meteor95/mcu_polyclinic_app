@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div id="container_transaksi_tindakan" class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
            <div class="row header_informasi">
                <div class="col-sm-10 col-md-10">
                    <select class="form-select override" id="pencarian_member_mcu" name="pencarian_member_mcu"></select>
                </div>
                <div class="col-sm-2 col-md-2">
                    <button class="btn btn-primary w-100" style="height: 47px;" id="btn_informasi_pasien_mcu"><i class="fa fa-info-circle"></i> Tampilkan Informasi</button>
                </div>
            </div>
            <div class="header_informasi" id="informasi_pasien_mcu" style="display: none;">
                <div class="row">
                    <div class="col-sm-3">
                        No Transaksi
                    </div>
                    <div class="col-sm-3">
                        <span id="nomor_transaksi_mcu"></span>(<span id="id_transaksi_mcu"></span>)<br></span>
                    </div>
                    <div class="col-sm-3">
                        Nomor Identitas
                    </div>
                    <div class="col-sm-3">
                        <span id="nomor_identitas_mcu"></span> (<span id="id_user_mcu"></span>)
                    </div>
                    <div class="col-sm-3">
                        Nama Peserta
                    </div>
                    <div class="col-sm-3">
                        <span id="nama_peserta_mcu"></span> (<span id="status_peserta_mcu"></span>)
                    </div>
                    <div class="col-sm-3">
                        No Telepon
                    </div>
                    <div class="col-sm-3">
                        <span id="no_telepon_mcu"></span>
                    </div>
                    <div class="col-sm-3">
                        Jenis Kelamin
                    </div>
                    <div class="col-sm-3">
                        <span id="jenis_kelamin_mcu"></span>
                    </div>
                    <div class="col-sm-3">
                        Email
                    </div>
                    <div class="col-sm-3">
                        <span id="email_mcu"></span>
                    </div>
                    <div class="col-sm-3">
                        Company Name
                    </div>
                    <div class="col-sm-3">
                        <span id="company_name_mcu"></span>
                    </div>
                    <div class="col-sm-3">
                        Departemen
                    </div>
                    <div class="col-sm-3">
                        <span id="nama_departemen_mcu"></span>
                    </div> 
                </div>
            </div>
            <div class="row header_informasi">
                <div class="col-sm-6">
                    <label for="nomor_transaksi_mcu">Nomor Transaksi</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="nomor_transaksi_mcu_generate" name="nomor_transaksi_mcu_generate" placeholder="Silahkan Tentukan Nomor Transaksi">
                        <div class="input-group-append">
                            <button class="btn btn-primary" id="generate_nomor_transaksi_mcu"><i class="fa fa-refresh"></i> Generate</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label for="waktu_transaksi_mcu">Waktu Transaksi</label>
                    <input type="text" class="form-control" id="waktu_transaksi_mcu" name="waktu_transaksi_mcu">
                </div>
                <div class="col-sm-3">
                    <label for="waktu_transaksi_sample_mcu">Waktu Transaksi Sample</label>
                    <input type="text" class="form-control" id="waktu_transaksi_sample_mcu" name="waktu_transaksi_sample_mcu">
                </div>
            </div>
            <div class="row header_informasi">
                <div class="col-sm-6">
                    <label for="dokter_mcu">Dokter Bertugas</label>
                    <select class="form-select override" id="dokter_bertugas_mcu" name="dokter_bertugas_mcu"></select >
                </div>
                <div class="col-sm-6">
                    <label for="penanggung_jawab_lab_mcu">Penanggung Jawab Lab</label>
                    <select class="form-select override" id="penanggung_jawab_mcu" name="penanggung_jawab_mcu"></select >
                </div>
            </div>
            <div class="row">
                <div class="col-sm-7 mt-2">
                    <select class="form-select" id="tindakan_tersedia_mcu" name="tindakan_tersedia_mcu"></select>
                </div>
                <div class="col-sm-5 mt-2">
                    <div class="button-wrapper button-outlined">
                        <div class="btn-group btn-group-square w-100" style="height: 47px;" role="group" aria-label="Basic outlined example">
                            <button id="btn_tambah_ke_keranjang_tindakan_mcu" class="btn btn-outline-success" type="button"><i class="fa fa-cart-plus"></i> Tambah Ke Keranjang</button>
                            <button id="btn_baca_template_tindakan_mcu" class="btn btn-outline-primary" type="button"><i class="fa fa-book"></i> Baca Template</button>
                            <button id="refresh_keranjang_tindakan_mcu" class="btn btn-outline-primary" type="button"><i data-feather="refresh-cw"></i></button>
                            <button id="sembunyikan_informasi" class="btn btn-outline-danger" type="button"><i id="arrowIcon" class="fa fa-arrow-up"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row label_total_harga">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="input-group">
                            <span id="label_total_harga" class="text-start" style="margin-top: 5px; padding-right: 10px; font-family: 'DS-Digital', sans-serif; font-size: 30px; color: red;">
                                <strong>Total Harga</strong>
                            </span>
                            <input type="text" class="form-control text-end" id="generate_total_harga_tindakan_mcu" name="generate_total_harga_tindakan_mcu" placeholder="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 mb-2">
                    <input type="text" id="table_tindakan_mcu_filter" name="table_tindakan_mcu_filter" class="form-control" placeholder="Pencarian Data Keranjang Tindakan. Ketikan Kode atau Nama Tindakan">
                </div>
            </div>
            <table id="table_tindakan_mcu" class="table table-striped table-bordered table-hover table-padding-sm">
                <thead>
                    <tr style="text-align: center;vertical-align: middle;">
                        <th>No</th>
                        <th>Kode Tindakan</th>
                        <th>Nama Tindakan</th>
                        <th>Nilai</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Harga Setelah Diskon</th>
                        <th>Jumlah</th>
                        <th>Sub Total Harga</th>
                        <th style="width: 200px;">Aksi</th>
                        <th>meta_data_kuantitatif</th>
                        <th>meta_data_kualitatif</th>
                        <th>meta_data_jasa</th>
                        <th>meta_data_jasa_fee</th>
                        <th>id_item</th>
                    </tr>
                </thead>
            </table>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="modal_baca_template_tindakan_mcu" tabindex="-1" aria-labelledby="modal_baca_template_tindakan_mcu" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Template Tindakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2">
                        <select id="data_ditampilkan" class="form-select">
                          <option value="0">Semua</option>
                          <option value="10">10 Data</option>
                          <option value="25" selected>25 Data</option>
                          <option value="50">50 Data</option>
                          <option value="100">100 Data</option>
                          <option value="500">500 Data</option>
                        </select>
                    </div>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="kotak_pencarian" placeholder="Cari berdasarkan kode, nama tindakan, atau kelompok tindakan">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table id="table_template_tindakan_mcu_modal" class="table table-striped table-bordered table-hover table-padding-sm"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_bagi_fee_tindakan_mcu" tabindex="-1" aria-labelledby="modal_baca_template_tindakan_mcu" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pembagian Fee Tindakan Ke Pekerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <span id="kode_tarif_jasa_mcu">Nilai total pembagian fee untuk pekerja akan dibagi secara proporsional berdasarkan jumlah pekerja yang terpilih pada masing masing kode jasa</span>
                        <span style="display: none;" id="kode_item_tabel"></span>
                        <span style="display: none;" id="nomor_item_tabel"></span>
                        <div id="table_jasa_container_alert"></div>
                        <div id="table_jasa_container"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="konfirmasi_pembagian_fee_tindakan_mcu">Konfirmasi Pembagian Fee</button>
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
                <div class="row mb-2 label_total_harga">
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
                        <h4 class="text-start mt-1" for="surat_pengantar">Unggah surat pengantar jika ada</h4>
                    </div>
                    <div class="col-md-8 mb-2">
                        <input type="file" class="form-control" id="surat_pengantar" name="surat_pengantar">
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
<button class="floating-button" id="floating_button_tambah_tindakan_mcu"><i class="fa-solid fa-cash-register"></i></button>
@endsection
@section('css_load')
<link href="https://fonts.cdnfonts.com/css/ds-digital" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<link rel="stylesheet" type="text/css" href="{{ asset('mofi/assets/css/vendors/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('mofi/assets/css/vendors/tagify.css') }}">
<style>
.card {
  display: flex;
  flex-direction: column;
  height: 100vh;
}

.card-body {
  flex: 1;
  overflow-y: auto;
}

.card-footer {
  background: #f1f1f1;
  padding: 10px;
  text-align: center;
  position: relative;
}
.mega-inline .card {
  background-color: #fff;
  border: 1px solid #ccc;
  height: auto;
}
#generate_total_harga_tindakan_mcu, #nominal_bayar_konfirmasi, #nominal_bayar, #nominal_kembalian{
    text-align: right;
    font-family: 'DS-Digital', sans-serif;
    font-size: 50px;
    color: red;
    padding: 0; 
    line-height: 1;
    font-weight: bold;
}
#generate_total_harga_tindakan_mcu::placeholder {
    font-family: 'DS-Digital', sans-serif;
    font-size: 50px;
    color: red;
}
.dataTables_filter {
    display: none;
}
.d-none {
    display: none;
}
.floating-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    font-size: 24px;
    text-align: center;
    cursor: pointer;
    z-index: 1000;
    transition: background-color 0.3s ease;
}

.floating-button:hover {
    background-color: #0056b3;
}
.tooltip-inner {
  background-color: #ff5722 !important; 
  color: #ffffff !important; 
}
.tooltip-arrow::before {
  border-top-color: #ff5722 !important; 
}
</style>
@endsection
@section('js_load')
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.8.1/autoNumeric.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/keytable/2.12.1/js/dataTables.keyTable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="{{ asset('mofi/assets/js/select2/tagify.js') }}"></script>
<script src="{{ asset('mofi/assets/js/select2/tagify.polyfills.min.js') }}"></script>
<script src="{{ asset('mofi/assets/js/flat-pickr/flatpickr.js') }}"></script>
<script src="{{ asset('vendor/erayadigital/laboratorium/transaksi_tindakan.js') }}"></script>
<script> var hasAtribut = {{ $hasVisiblePrice ? 'true' : 'false' }}; </script>
@endsection