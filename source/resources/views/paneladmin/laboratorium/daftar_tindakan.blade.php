@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Daftar Tindakan Laboratorium dan Pengobatan {{ config('app.name') }}</h4><span>
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
                <div id="total_pendapatan_apotek_keterangan">0</div>
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
            <div id="button_edit_apotek"></div>
            <div id="button_edit_transaksi"></div>
          </div>
      </div>
  </div>
</div>
<div class="modal fade" id="modalUbahDataApotek" tabindex="-1" role="dialog" aria-labelledby="modalUbahDataApotekLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-fullscreen" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalUbahDataApotekLabel">Ubah Data Apotek dengan ID Transaksi <span id="id_transaksi_apotek"></span> dan Nomor <span id="nomor_trx_apotek"></span></h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <h3>Masukan Nominal</h3>
                <input type="text" class="form-control text-end" id="nominal_apotek" placeholder="0" autofocus>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <h3>Berkas Teks</h3>
                <table id="daftar_table_berkas_apotek" class="table table-striped table-bordered table-hover table-padding-sm">
                  <thead>
                    <tr style="text-align: center;">
                      <th>No</th>
                      <th>Berkas</th>
                      <th>Ekstensi</th>
                      <th>Keterangan</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
            <div class="row mt-2">
              <div class="card-body">
                <div class="dropzone" id="singleFileUploadApotek">
                  <div class="dz-message needsclick">
                      <i class="icon-cloud-up" style="font-size: 40px;"></i>
                      <h5 class="f-w-600">Tarik file ke sini atau klik untuk mengunggah berkas.</h5>
                      <span class="note needsclick">(Berkas yang diizinkan diunggah dalam satu waktu adalah <strong>3 File</strong>)</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-amc-orange" id="simpan_data_apotek"><i class="fa fa-save"></i> Simpan Tambahan Apotek</button>
          </div>
      </div>
  </div>
</div>
@endsection
@section('css_load')
<link rel="stylesheet" type="text/css" href="{{ asset('mofi/assets/css/vendors/dropzone.min.css') }}">
<link rel="stylesheet" href="https://fonts.cdnfonts.com/css/ds-digital">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" integrity="sha512-UtLOu9C7NuThQhuXXrGwx9Jb/z9zPQJctuAgNUBK3Z6kkSYT9wJ+2+dh6klS+TDBCV9kNPBbAxbVD+vCcfGPaA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
#total_pendapatan_text, #total_pendapatan, #nominal_apotek{
  font-family: 'DS-Digital', sans-serif;
  font-size: 60px;
  color: red;
  padding: 0; 
  line-height: 1;
  font-weight: bold;
}
#nominal_apotek::placeholder{
  font-size: 60px;
  color: red;
}
#singleFileUploadApotek{
  border: 1px dashed blue;
  margin: 0 10px 0 10px;
}
.dropzone .dz-preview {
  position: relative;
  display: inline-block;
  vertical-align: top;
  margin: 16px;
  min-height: 100px;
}
.dz-preview .dz-image img {
    display: block;
    margin: 0 auto;
}
.dz-preview {
    display: flex;
    flex-direction: column;
    align-items: center; 
    justify-content: center;
    width: 100% !important; 
    max-width: 300px; /* Batasi maksimal lebar agar tidak terlalu besar */
    height: 150px;
    padding: 10px;
}

.dz-preview .dz-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.dz-details .dz-filename {
    display: none !important;
}
/* Pastikan Dropzone berada di tengah */
.dropzone {
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: center;
    justify-content: flex-start;
    justify-content: center;
}

/* Pastikan pesan dalam Dropzone tetap center */
.dropzone .dz-message {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    width: 100%;
    height: 100%;
}

/* Ikon cloud di dalam Dropzone */
.dropzone .dz-message i {
    font-size: 50px;
    margin-bottom: 10px;
}

/* Teks dalam Dropzone */
.dropzone .dz-message h5 {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 5px;
}

/* Note di bawah teks */
.dropzone .dz-message .note {
    font-size: 14px;
    color: #777;
}


</style>
@endsection
@section('js_load')
<script src="{{ asset('mofi/assets/js/dropzone/dropzone.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js" integrity="sha512-JyCZjCOZoyeQZSd5+YEAcFgz2fowJ1F1hyJOXgtKu4llIa0KneLcidn5bwfutiehUTiOuK87A986BZJMko0eWQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.8.1/autoNumeric.min.js"></script>
<script src="{{ asset('vendor/erayadigital/laboratorium/daftar_tindakan.js') }}"></script>
@endsection
