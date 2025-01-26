@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Berkas Tindakan MCU</h4><span>Pada halaman ini anda dapat melihat semua berkas peserta yang melakukan tindakan MCU mulai dari data awal hingga data akhir. Informasi berkas MCU akan tampil pada halaman ini jikalau berkas tersebut telah validasi oleh dokter atau admin aplikasi.</span>
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
<div class="modal fade" id="modal_lihat_berkas_mcu" tabindex="-1" role="dialog" aria-labelledby="modal_lihat_berkas_mcuLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_lihat_berkas_mcu_text">Pratinjau Berkas MCU Non Header</h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <h1>PEMERIKSAAN KESEHATAN<br>(MEDICAL CHECKUP)</h1>
                    <div id="berkas_mcu_foro_peserta"></div>
                </div>
                <div class="col-sm-12">
                    <table class="table table-bordered table-padding-sm-no-datatable" style="width: 80%; margin: 0 auto;">
                        <tr>
                            <td class="text-end">NOMOR MEDICAL CHECKUP</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_nomor_mcu">0005/MCU/EDS-DO/AMC/I/2099</td>
                        </tr>
                        <tr>
                            <td class="text-end">NAMA PESERTA</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_nama_peserta">PT. Eraya Digital Solusindo</td>
                        </tr>
                        <tr>
                            <td class="text-end">NIK / NRR</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_nik">3275010101010101</td>
                        </tr>
                        <tr>
                            <td class="text-end">TEMPAT TANGGAL LAHIR / UMUR</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_tempat_tanggal_lahir">MALANG, 01-01-1999 / 26 TAHUN</td>
                        </tr>
                        <tr>
                            <td class="text-end">JENIS KELAMIN</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_jenis_kelamin">LAKI-LAKI</td>
                        </tr>
                        <tr>
                            <td class="text-end">PERUSAHAAN</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_perusahaan">PT. Eraya Digital Solusindo</td>
                        </tr>
                        <tr>
                            <td class="text-end">DEPARTEMEN JABATAN</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_jabatan">CEO / IT</td>
                        </tr>
                        <tr>
                            <td class="text-end">ALAMAT</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_alamat">JL. RAYA MALANG KM. 10, KEL. KARANGPLOSO, KEC. KARANGPLOSO, KOTA MALANG, JAWA TIMUR 65151</td>
                        </tr>
                        <tr>
                            <td class="text-end">TANGGAL MCU / TIPE MCU</td>
                            <td class="text-center">:</td>
                            <td id="berkas_mcu_tanggal_mcu">01-01-2025 / PAKET PERUSAHAN ERAYA DIGITAL SOLUSINDO</td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">LAPORAN HASIL MEDICAL CHECKUP</h5>
                        </div>
                        <div class="card-body">
                            <h3>HASIL PEMERIKSAAN</h3>
                            <table class="table table-bordered table-padding-sm-no-datatable" style="width: 80%; margin: 0 auto;">
                                <tr>
                                    <td>RIWAYAT MEDIS</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_riwayat_medis">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td>PEMERIKSAAN FISIK</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_pemeriksaan_fisik">Tekanan Darah, Suhu, Nadi, Respirasi, dan Berat Badan</td>
                                </tr>
                                <tr>
                                    <td>HASIL LABORATORIUM</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_hasil_laboratorium">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td>RO THORAX</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_rontgen_thorax">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td>RO LUMBOSACRAL</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_rontgen_lumbosacral">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td>USG UB DOMAIN</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_usg_ubdomain">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td>EKG</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_ekg">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td>AUDIOMETRI</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_audiometri">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td>SPIROMETRI</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_spirometri">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td>FARINGOMETER</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_farmingham_score">Tidak Ada</td>
                                </tr>
                                 <tr>
                                    <td>THREADMILL</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_threadmill">Tidak Ada</td>
                                </tr>
                            </table>
                            <h3>KESIMPULAN HASIL MEDICAL CHECKUP</h3>
                            <h3>SARAN</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">STATUS KESEHATAN</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" style="width: 100%; margin: 0 auto;">
                                <tr>
                                    <td>STATUS</td>
                                    <td>KATEGORI</td>
                                    <td>CATATAN</td>
                                </tr>
                            </table>
                            <h3>KETERANGAN</h3>
                            <h3>CATATAN</h3>
                            <ol>
                                <li><strong>Kesimpulan  yang dikeluarkan berdasarkan hasil temuan yang didapatkan pada pemeriksaan medical checkup.</strong></li>
                                <li><strong>Kesimpulan hasil medical checkup tidak dapat diganggu gugat.</strong></li>
                            </ol>
                            <h5>Bila masih ada hal yang perlu dijelaskan, mohon segerah menghubungi dokter. Terima kasih atas kerjasamanya.</h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">RIWAYAT PENYAKIT TERDAHULU</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" style="width: 100%; margin: 0 auto;">
                                <tr>
                                    <td>PERTANYAAN</td>
                                    <td>JAWABAN</td>
                                    <td>KETERANGAN</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">RIWAYAT PENYAKIT KELUARGA</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" style="width: 100%; margin: 0 auto;">
                                <tr>
                                    <td>PERTANYAAN</td>
                                    <td>JAWABAN</td>
                                    <td>KETERANGAN</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">RIWAYAT KHUSUS WANITA</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" style="width: 100%; margin: 0 auto;">
                                <tr>
                                    <td>PERTANYAAN</td>
                                    <td>JAWABAN</td>
                                    <td>KETERANGAN</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">RIWAYAT KECELAKAAN KERJA</h5>
                        </div>
                        <div class="card-body">

                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">RIWAYAT KEBIASAAN</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" style="width: 100%; margin: 0 auto;">
                                <tr>
                                    <td>PERTANYAAN</td>
                                    <td>JAWABAN</td>
                                    <td>KETERANGAN</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                                <h5 class="txt-light">RIWAYAT IMUNISASI</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" style="width: 100%; margin: 0 auto;">
                                <tr>
                                    <td>PERTANYAAN</td>
                                    <td>JAWABAN</td>
                                    <td>KETERANGAN</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">RIWAYAT PAPARAN KERJA</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" style="width: 100%; margin: 0 auto;">
                                <tr>
                                    <td>PERTANYAAN</td>
                                    <td>JAWABAN</td>
                                    <td>KETERANGAN</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="text-center">
                        <h3>PEMERIKSAAN FISIK</h3>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">TINGKAT KESADARAN</h5>
                        </div>
                        <div class="card-body">
                            
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">TANDA VITAL</h5>
                        </div>
                        <div class="card-body">

                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">PENGLIHAN </h5>
                        </div>
                        <div class="card-body">

                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">KONDISI FISIK</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" style="width: 100%; margin: 0 auto;">
                                <tr>
                                    <td>PEMERIKSAAN</td>
                                    <td>JENIS PEMERIKSAAN</td>
                                    <td>AB</td>
                                    <td>N</td>
                                    <td>KETERANGAN</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="text-center">
                        <h3>HASIL LABORATORIUM</h3>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">HASIL PEMERIKSAAN</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered" style="width: 100%; margin: 0 auto;">
                                <tr>
                                    <td>PARAMTER</td>
                                    <td>HASIL</td>
                                    <td>NILAI RUJUKAN</td>
                                    <td>SATUAN</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="konfirmasi_validasi_rekap_kesimpulan"><i class="fa fa-file-pdf-o"></i> Unduh Berkas PDF</button>
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
</style>
@endsection
@section('js_load')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="{{ asset('vendor/erayadigital/laporan/berkas_mcu.js') }}"></script>
@endsection