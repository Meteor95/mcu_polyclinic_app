@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row default-dashboard">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <div class="row mt-2">
            <div class="col-sm-12 col-md-12">
              <select class="form-select" id="pencarian_member_mcu" name="pencarian_member_mcu"></select>
            </div>
          </div>
          <div class="row" id="kartu_informasi_peserta">
            <div class="col-xl-7 col-md-6 proorder-xl-1 proorder-md-1">  
                <div class="card profile-greeting p-0">
                    <div class="card-body">
                        <div class="img-overlay">
                            <h1>LKP MCU, <span id="nama_peserta_temp"></span></h1>
                            <p>Formulir untuk kelengkapan MCU berupa informasi foto terbaru dari pasien MCU yang akan digunakan untuk laporan MCU. Foto peserta wajib diisi setiap dia melakukan pemeriksaan MCU baru.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-md-6 proorder-md-5"> 
                <div class="card">
                    <div class="card-header card-no-border pb-0">
                        <div class="header-top">
                            <h4>Informasi Peserta</h4>
                            <div class="location-menu dropdown">
                                <button class="btn btn-danger" type="button">Dibuat pada : <span id="created_at_temp">{{date('d-m-Y')}}</span></button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body live-meet">
                        <div class="row">
                            <div class="col-4">
                                Nomor Identitas<br>
                                Nama Peserta<br>
                                Jenis Kelamin<br>
                                Nomor Transaksi MCU<br>
                                No. HP Peserta<br>
                                Email Peserta<br>
                                Perusahaan<br>
                                Departemen<br>
                            </div>
                            <div class="col-8">
                                : <span id="nomor_identitas_temp"></span> (<span id="user_id_temp"></span>)<br>
                                : <span id="nama_peserta_temp_1"></span><br>
                                : <span id="jenis_kelamin_temp"></span><br>
                                : <span id="nomor_transaksi_temp"></span> (<span id="id_transaksi_mcu"></span>)<br>
                                : <span id="no_telepon_temp"></span><br>
                                : <span id="email_temp"></span><br>
                                : <span id="tempat_lahir_temp"></span><br>
                                : <span id="status_kawin_temp"></span><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
        <div class="card-body">
            <h1 class="mb-2 text-center">Formulir Riwayat Kecelakaan Kerja</h1>
            <div id="editor_kecelakaan_kerja"></div>
            <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                <button class="btn btn-danger w-100 mt-2" id="bersihkan_data_riwayat_kecelakaan_kerja"><i class="fa fa-refresh"></i> Bersihkan Data</button>                   
                <button class="mt-2 btn btn-success w-100" id="btnSimpanRiwayatKecelakaanKerja">Simpan Riwayat Kecelakaan Kerja</button>
            </div>
        </div>
        <div class="card-footer">
            <h1 class="mb-2 text-center">Daftar Kecelakaan Kerja</h1>
            <input type="text" class="form-control" id="kotak_pencarian_daftarpasien" placeholder="Cari data...">
            <div class="table-responsive theme-scrollbar">
              <table class="display" id="datatables_daftar_kecelakaan_kerja"></table>
            </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="modalLihatRiwayatKecelakaanKerja" tabindex="-1" role="dialog" aria-labelledby="modalLihatRiwayatKecelakaanKerjaLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nama Pasien : <span id="modal_nama_peserta_parameter"></span></h5>
                <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
            </div>
            <div class="modal-body">
              <div id="editor_riwayat_kecelakaan_kerja"></div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
  </div>
@endsection
@section('css_load')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container--default .select2-selection--single .select2-selection__arrow {
    margin-top: 10px;
    margin-right: 10px;
}
.select2-container--open .select2-dropdown--below {
  margin-top: -20px;
  border-top-left-radius:2;
  border-top-right-radius:2;
}
table.dataTable tbody td.focus {
  outline: 1px dotted rgb(255, 153, 0);
  outline-offset: -2px;
}
</style>
@endsection
@section('js_load')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('mofi/assets/js/system/riwayat/kecelakaan_kerja.js') }}"></script>
@endsection
