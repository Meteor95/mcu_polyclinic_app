@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Berkas Laboratorium</h4><span>Pada halaman ini anda dapat melihat semua berkas peserta yang melakukan tindakan laboratorium mulai dari data awal hingga data akhir. Informasi berkas laboratorium akan tampil pada halaman ini jikalau berkas tersebut telah validasi oleh dokter atau admin aplikasi.</span>
        </div>
        <div class="card-body">
          <input type="text" class="form-control" id="kotak_pencarian_daftarpasien" placeholder="Cari data berdasarkan nama peserta">
          <div class="table-responsive theme-scrollbar">
            <table class="display" id="datatables_daftarpasien"></table>
          </div>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="modal_lihat_berkas_laboratorium" tabindex="-1" role="dialog" aria-labelledby="modal_lihat_berkas_laboratoriumLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_lihat_berkas_laboratorium_text">Pratinjau Berkas Laboratorium</h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <h1>BERKAS LABORATORIUM</h1>
                    <div id="berkas_mcu_foro_peserta"></div>
                </div>
                <div class="col-sm-12">
                    <div style="display: none;" id="id_mcu_berkas_mcu"></div>
                    <table class="table table-bordered table-padding-sm-no-datatable" style="width: 80%; margin: 0 auto;">
                        <tr>
                            <td class="text-end" style="white-space: nowrap;">NOMOR MEDICAL CHECKUP</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_nomor_mcu">Nomor MCU Tidak Terdeteksi Oleh Sistem</td>
                        </tr>
                        <tr>
                            <td class="text-end">NAMA PESERTA</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_nama_peserta">Nama Peserta Tidak Terdeteksi Oleh Sistem</td>
                        </tr>
                        <tr>
                            <td class="text-end">NIK / NRR</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_nik">NIK / NRR Tidak Terdeteksi Oleh Sistem</td>
                        </tr>
                        <tr>
                            <td class="text-end" style="white-space: nowrap;">TEMPAT TANGGAL LAHIR / UMUR</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_tempat_tanggal_lahir">Tempat Tanggal Lahir Tidak Terdeteksi Oleh Sistem / Umur Tidak Terdeteksi Oleh Sistem</td>
                        </tr>
                        <tr>
                            <td class="text-end">JENIS KELAMIN</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_jenis_kelamin">Jenis Kelamin Tidak Terdeteksi Oleh Sistem</td>
                        </tr>
                        <tr>
                            <td class="text-end">PERUSAHAAN</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_perusahaan">Perusahaan Tidak Terdeteksi Oleh Sistem</td>
                        </tr>
                        <tr>
                            <td class="text-end">DEPARTEMEN JABATAN</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_jabatan">Jabatan Tidak Terdeteksi Oleh Sistem</td>
                        </tr>
                        <tr>
                            <td class="text-end">ALAMAT</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_alamat">Alamat Tidak Terdeteksi Oleh Sistem</td>
                        </tr>
                        <tr>
                            <td class="text-end" style="white-space: nowrap;">TANGGAL MCU / TIPE MCU</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_tanggal_mcu">Tanggal MCU Tidak Terdeteksi Oleh Sistem / Tipe MCU Tidak Terdeteksi Oleh Sistem</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-center">
                        <h3>HASIL LABORATORIUM</h3>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card-body">
                        <table id="datatables_hasil_laboratorium_modal" class="table table-bordered table-padding-sm-no-datatable">
                        <thead>
                            <tr style="text-align: center;">
                                <th>PARAMTER</th>
                                <th>HASIL</th>
                                <th>NILAI RUJUKAN</th>
                                <th>SATUAN</th>
                                <th>METODE</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="konfirmasi_cetak_laboratorium"><i class="fa fa-file-pdf-o"></i> Unduh Berkas PDF</button>
          </div>
        </div>
    </div>
</div>
@endsection
@section('css_load')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<style>
.modal-body {
  overflow-y: auto;
}
.scaled-image_0_3 {
     width: 25%;
}
</style>
@endsection
@section('js_load')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="{{ asset('vendor/erayadigital/laporan/berkas_laboratorium.js') }}"></script>
@endsection