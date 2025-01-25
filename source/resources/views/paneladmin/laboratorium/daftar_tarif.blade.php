@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row" id="on_top_formulir_tambah_tarif">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4 style="margin-top: 5px;">Formulir Tambah Tarif Laboratorium dan Pengobatan</h4>
                <button class="btn btn-primary text-white float-end" id="collapse_formulir">Tampilkan Formulir</button>
            </div>
        </div>
        <div class="card-body collapse hide" id="formulir_tambah_tarif">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="kode_item">Kode Item</label>
                        <div class="input-group">
                            <input type="text" name="kode_item" id="kode_item" placeholder="Buat kode item untuk Laboratorium" class="form-control">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="btn_generate_kode_item">Generate</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label for="nama_item">Nama Item</label>
                    <input type="text" name="nama_item" id="nama_item" class="form-control" placeholder="Buat nama item untuk Laboratorium">
                </div>
                <div class="col-sm-6">
                    <label for="grup_item">Grup Item</label>
                    <select name="grup_item" data-choices id="grup_item" class="form-control">
                        <option value="laboratorium">Laboratorium</option>
                        <option value="non_laboratorium">Non Laboratorium</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control"></select>                                                     
                </div>
                <div class="col-sm-6">
                    <label for="satuan">Satuan</label>
                    <select name="satuan" id="satuan" class="form-control"></select>
                </div>
                <div class="col-sm-6">
                    <label for="visible_item">Status Penampilkan</label>
                    <select name="visible_item" data-choices id="visible_item" class="form-control">
                        <option value="tampilkan">Tampilkan</option>
                        <option value="rahasia">Rahasia</option>
                        <option value="sembunyikan">Sembunyikan</option>
                    </select>
                </div>
            </div>
            <div class="row" id="section_nilai_kenormalan">
                <div class="col-md-12 mt-2">
                    <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active txt-danger" id="tab_jenis_item_kuantitatif" data-bs-toggle="tab" href="#formulir_jenis_item_kuantitatif" role="tab" aria-controls="icon-home" aria-selected="true">
                            <i class="icofont icofont-prescription"></i> Jenis Kuantitatif
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link txt-danger" id="tab_jenis_item_kualitatif" data-bs-toggle="tab" href="#formulir_jenis_item_kualitatif" role="tab" aria-controls="profile-icon" aria-selected="false">
                            <i class="icofont icofont-prescription"></i> Jenis Kualitatif
                          </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="formulir_tab_content">
                        <div class="tab-pane fade show active" id="formulir_jenis_item_kuantitatif" role="tabpanel" aria-labelledby="tab_jenis_item_kuantitatif">
                            <div class="row mt-2 g-1">
                                <div class="col-md-12">
                                    <select class="form-control" id="rentang_kenormalan_kuantitatif" name="rentang_kenormalan_kuantitatif" required>
                                        @foreach ($data['kenormalan'] as $item)
                                            @php
                                                $jenisKelamin = $item->jenis_kelamin;
                                                $jenisKelaminLabel = $jenisKelamin == 'L' 
                                                    ? 'Laki-laki' 
                                                    : ($jenisKelamin == 'P' 
                                                        ? 'Perempuan' 
                                                        : 'Semua Jenis Kelamin');
                                            @endphp
                                            <option value="{{ $item->id }} | {{ $item->umur }}">
                                                {{ $jenisKelaminLabel }} - {{ $item->nama_rentang_kenormalan }} {{ $item->umur > 0 ? '('.$item->umur.' Tahun)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>                                        
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="batas_atas" name="batas_atas" placeholder="Masukan Batas Atas">
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" id="antara" name="antara">
                                        <option value="-">Antara ( - )</option>
                                        <option value="=">Sama Dengan ( = )</option>
                                        <option value=">">Lebih Besar Dari ( > )</option>
                                        <option value="<">Kurang Dari ( < )</option>
                                        <option value=">=">Lebih Besar Sama Dengan ( >= )</option>
                                        <option value="<=">Kurang Dari Sama Dengan ( <= )</option>
                                        <option value="!=">Tidak Sama Dengan ( != )</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="batas_bawah" name="batas_bawah" placeholder="Masukan Batas Bawah">
                                </div>
                                <button class="btn btn-primary" id="btn_tambah_tarif_kuantitatif"><i class="fa fa-plus"></i> Tambah Nilai Kenormalan Kuantitatif</button>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label for="tabel_rentang_nilai_kenormalan_kuantitatif">Tabel Rentang Nilai Kenormalan</label>
                                    <table id="tabel_rentang_nilai_kenormalan_kuantitatif" class="table table-striped table-bordered table-hover  table-padding-sm">
                                        <thead>
                                            <tr style="text-align: center; vertical-align: middle;">
                                                <th rowspan="2">No</th>
                                                <th rowspan="2">Id</th>
                                                <th rowspan="2">Umur</th>
                                                <th rowspan="2">Rentang Kenormalan</th>
                                                <th colspan="3">Nilai Rujukan</th>
                                                <th rowspan="2">Aksi</th>
                                            </tr>
                                            <tr style="text-align: center;">
                                                <th scope="col">Batas Atas</th>
                                                <th scope="col">Antara</th>
                                                <th scope="col">Batas Bawah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>                                        
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="formulir_jenis_item_kualitatif" role="tabpanel" aria-labelledby="tab_jenis_item_kualitatif">
                            <div class="row mt-2 g-1">
                                <div class="col-md-12">
                                    <select class="form-control" id="rentang_kenormalan_kualitatif" name="rentang_kenormalan_kualitatif" required>
                                        @foreach ($data['kenormalan'] as $item)
                                            @php
                                                $jenisKelamin = $item->jenis_kelamin;
                                                $jenisKelaminLabel = $jenisKelamin == 'L' 
                                                    ? 'Laki-laki' 
                                                    : ($jenisKelamin == 'P' 
                                                        ? 'Perempuan' 
                                                        : 'Semua Jenis Kelamin');
                                            @endphp
                                            <option value="{{ $item->id }} | {{ $item->umur }}">
                                                {{ $jenisKelaminLabel }} - {{ $item->nama_rentang_kenormalan }} {{ $item->umur > 0 ? '('.$item->umur.' Tahun)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>                                        
                                </div>
                                <div class="col-md-6">
                                    <label for="keterangan_kualitatif_positif">Keterangan Jika Positif</label>
                                    <input type="text" class="form-control" id="keterangan_kualitatif_positif" name="keterangan_kualitatif_positif" placeholder="Masukan keterangan untuk nilai rujukan kualitatif">
                                </div>
                                <div class="col-md-6">
                                    <label for="keterangan_kualitatif_negatif">Keterangan Jika Negatif</label>
                                    <input type="text" class="form-control" id="keterangan_kualitatif_negatif" name="keterangan_kualitatif_negatif" placeholder="Masukan keterangan untuk nilai rujukan kualitatif">
                                </div>
                                <button class="btn btn-primary" id="btn_tambah_tarif_kualitatif"><i class="fa fa-plus"></i> Tambah Nilai Kenormalan Kualitatif</button>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <label for="tabel_rentang_nilai_kenormalan_kualitatif">Tabel Rentang Nilai Kenormalan</label>
                                        <table id="tabel_rentang_nilai_kenormalan_kualitatif" class="table table-striped table-bordered table-hover  table-padding-sm">
                                            <thead>
                                                <tr style="text-align: center; vertical-align: middle;">
                                                    <th rowspan="2">No</th>
                                                    <th rowspan="2">Id</th>
                                                    <th rowspan="2">Umur</th>
                                                    <th rowspan="2">Rentang Kenormalan</th>
                                                    <th colspan="4">Nilai Rujukan</th>
                                                    <th rowspan="2">Aksi</th>
                                                </tr>
                                                <tr style="text-align: center;">
                                                    <th style="text-align: center;">Jika Positif</th>
                                                    <th>Keterangan</th>
                                                    <th style="text-align: center;">Jika Negatif</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label for="harga_dasar_tarif_laboratorium">Harga Dasar</label>
                    <input type="text" name="harga_dasar_tarif_laboratorium" id="harga_dasar_tarif_laboratorium" class="form-control" placeholder="Tentukan harga dasar sebelum dikenakan jasa laboratorium">
                </div>  
                <div class="col-sm-12">
                    <label for="table_tarif_laboratorium">Jasa Laboratorium Per Transaksi</label>
                    <table id="table_tarif_laboratorium" class="table table-striped table-bordered table-hover table-padding-sm"></table>
                </div>
                <div class="col-sm-12">
                    <label for="harga_jual">Harga Jual</label>
                    <input type="text" name="harga_jual" id="harga_jual_tarif_laboratorium" class="form-control" readonly>
                </div> 
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <button class="btn btn-danger w-100" id="btn_bersihkan_formulir"><i class="fa fa-refresh"></i> Bersihkan Formulir</button>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-primary w-100" id="btn_simpan_tarif_laboratorium"><i class="fa fa-database"></i> Simpan Tarif Lab & Obat</button>
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
                  <select id="data_ditampilkan" class="form-select">
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
                    <option value="laboratorium">Laboratorium</option>
                    <option value="non_laboratorium">Non Laboratorium</option>
                  </select>
                </div>
                <div class="col-md-8">
                  <input type="text" class="form-control" id="kotak_pencarian" placeholder="Cari data berdasarkan kode, nama item, group item, kategori, atau satuan">
                </div>
              </div>
            <table id="table_tindakan_lab" class="table table-striped table-bordered table-hover table-padding-sm"></table>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="modalLihatTarif" tabindex="-1" role="dialog" aria-labelledby="modalLihatTarifLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLihatTarifLabel">Lihat Tarif</h5>
                <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
            </div>
            <div class="modal-body">
                <div class="row">
                    <label class="col-sm-3">Kode Item</label>
                    <div class="col-sm-9"><div class="form-control-static" id="kode_item_tarif"></div></div>
                </div>
                <div class="row">
                    <label class="col-sm-3">Nama Item</label>
                    <div class="col-sm-9"><div class="form-control-static" id="nama_item_tarif"></div></div>
                </div>
                <div class="row">
                    <label class="col-sm-3">Grup Item</label>
                    <div class="col-sm-9"><div class="form-control-static" id="grup_item_tarif"></div></div>
                </div>
                <div class="row">
                    <label class="col-sm-3">Kategori Item</label>
                    <div class="col-sm-9"><div class="form-control-static" id="jenis_item_tarif"></div></div>
                </div>
                <div class="row">
                    <label class="col-sm-3">Satuan</label>
                    <div class="col-sm-9"><div class="form-control-static" id="satuan_tarif"></div></div>
                </div>
                <div class="row" id="section_nilai_kenormalan_tarif_modal">
                    <label class="col-sm-3">Nilai Kenormalan</label>
                    <table style="display: none;" id="table_jasa_laboratorium_tarif_kuantitatif" class="table table-striped table-bordered table-hover table-padding-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Batas Bawah</th>
                                <th>Antara</th>
                                <th>Batas Atas</th>
                                <th>Umur</th>
                            </tr>
                        </thead>
                    </table>
                    <table style="display: none;" id="table_jasa_laboratorium_tarif_kualitatif" class="table table-striped table-bordered table-hover table-padding-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Nilai Kenormalan</th>
                                <th>Jika Positif</th>
                                <th>Keterangan</th>
                                <th>Jika Negatif</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="row">
                    <label class="col-sm-3">Harga Dasar</label>
                    <div class="col-sm-9"><div class="form-control-static" id="harga_dasar_tarif"></div></div>
                </div>
                <div class="row">
                    <label class="col-sm-3">Harga Jual</label>
                    <div class="col-sm-9"><div class="form-control-static" id="harga_jual_tarif"></div></div>
                </div>
                <div class="row">
                    <label class="col-sm-3">Jasa Laboratorium</label>
                    <table id="table_jasa_laboratorium_tarif_modal" class="table table-striped table-bordered table-hover table-padding-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jasa Laboratorium</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                    </table>
                </div>
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
    </style>
@endsection
@section('js_load')
<script src="https://cdn.jsdelivr.net/npm/numbro@2.5.0/dist/numbro.min.js"></script>
<script src="https://cdn.datatables.net/keytable/2.12.1/js/dataTables.keyTable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.8.1/autoNumeric.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="{{ asset('vendor/erayadigital/laboratorium/daftar_tarif.js') }}"></script>
@endsection
