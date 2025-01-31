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
                                    <td id="berkas_mcu_riwayat_medis_quill">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td>PEMERIKSAAN FISIK</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_pemeriksaan_fisik_quill">Tekanan Darah, Suhu, Nadi, Respirasi, dan Berat Badan</td>
                                </tr>
                                <tr>
                                    <td>HASIL LABORATORIUM</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_pemeriksaan_laboratorium_quill">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td>RO THORAX</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_pemeriksaan_rontgen_thorax_quill">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td>RO LUMBOSACRAL</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_pemeriksaan_rontgen_lumbosacral_quill">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td>USG UB DOMAIN</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_pemeriksaan_usg_ubdomain_quill">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td>EKG</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_pemeriksaan_ekg_quill">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td rowspan="2">AUDIOMETRI</td>
                                    <td rowspan="2">:</td>
                                    <td id="berkas_mcu_pemeriksaan_audiometri_kiri_quill">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td id="berkas_mcu_pemeriksaan_audiometri_kanan_quill">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td rowspan="2">SPIROMETRI</td>
                                    <td rowspan="2">:</td>
                                    <td id="berkas_mcu_pemeriksaan_spirometri_restriksi_quill">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td id="berkas_mcu_pemeriksaan_spirometri_obstruksi_quill">Tidak Ada</td>
                                </tr>
                                <tr>
                                    <td>FARINGOMETER</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_pemeriksaan_farmingham_score_quill">Tidak Ada</td>
                                </tr>
                                 <tr>
                                    <td>THREADMILL</td>
                                    <td>:</td>
                                    <td id="berkas_mcu_pemeriksaan_threadmill_quill">Tidak Ada</td>
                                </tr>
                            </table>
                            <h3>KESIMPULAN HASIL MEDICAL CHECKUP</h3>
                            <div id="berkas_mcu_kesimpulan_tindakan_quill"></div>
                            <h3>SARAN</h3>
                            <div id="berkas_mcu_tindakan_saran_quill"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">RIWAYAT PENYAKIT TERDAHULU</h5>
                        </div>
                        <div class="card-body">
                            <table id="datatables_penyakit_terdahulu_modal" class="table table-bordered table-padding-sm-no-datatable">
                            <thead>
                                <tr>
                                <th>PERTANYAAN</th>
                                <th>JAWABAN</th>
                                <th>KETERANGAN</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
                           <table id="datatables_riwayat_penyakit_keluarga_modal" class="table table-bordered table-padding-sm-no-datatable">
                            <thead>
                                <tr>
                                <th>PERTANYAAN</th>
                                <th>JAWABAN</th>
                                <th>KETERANGAN</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" id="riwayat_khusus_wanita_section" style="display: none;">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">RIWAYAT KHUSUS WANITA</h5>
                        </div>
                        <div class="card-body">
                            <table id="datatables_riwayat_kebiasaan_hidup_perempuan_modal" class="table table-bordered table-padding-sm-no-datatable">
                            <thead>
                                <tr>
                                    <th>PERTANYAAN</th>
                                    <th>JAWABAN</th>
                                    <th>WAKTU</th>
                                    <th>SATUAN</th>
                                    <th>KETERANGAN</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
                            <div id="editor_riwayat_kecelakaan_kerja_quill"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">RIWAYAT KEBIASAAN</h5>
                        </div>
                        <div class="card-body">
                            <table id="datatables_riwayat_kebiasaan_hidup_modal" class="table table-bordered table-padding-sm-no-datatable">
                            <thead>
                                <tr>
                                    <th>PERTANYAAN</th>
                                    <th>JAWABAN</th>
                                    <th>WAKTU</th>
                                    <th>SATUAN</th>
                                    <th>KETERANGAN</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
                            <table id="datatables_imunisasi_modal" class="table table-bordered table-padding-sm-no-datatable">
                                <thead>
                                    <tr>
                                        <th>PERTANYAAN</th>
                                        <th>JAWABAN</th>
                                        <th>KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
                           <table id="datatables_riwayat_lingkungan_kerja_modal" class="table table-bordered table-padding-sm-no-datatable">
                            <thead>
                                <tr>
                                <th>PERTANYAAN</th>
                                <th>STATUS</th>
                                <th>JAM / HARI</th>
                                <th>SELAMA X TAHUN</th>
                                <th>KETERANGAN</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
                            <table class="table table-bordered table-padding-sm-no-datatable">
                                <thead>
                                <tr>
                                    <th>PARAMTER</th>
                                    <th>KETERANGAN</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>KEADAAN UMUM</td>
                                        <td><span id="modal_keadaan_umum_temp"></span> - <span id="modal_keterangan_keadaan_umum_temp"></span></td>
                                    </tr>
                                    <tr>
                                        <td>STATUS KESADARAN <span id="modal_status_kesadaran_temp"></span></td>
                                        <td><span id="modal_keterangan_status_kesadaran_temp"></span></td>
                                    </tr>
                                    <tr>
                                        <td>KELUHAN</td>
                                        <td><span id="modal_keluhan_temp"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">TANDA VITAL</h5>
                        </div>
                        <div class="card-body">
            <div class="text-end">
                <strong>BMI : </strong><span id="modal_bmi_temp"></span> IMT<br>
                <strong>STATUS BMI : </strong><span id="modal_status_gizi_temp"></span>
            </div>
            <h3 class="text-center">VITAL</h3>
            <table class="table table-bordered table-padding-sm-no-datatable" id="datatables_tanda_vital_modal_tanda_vital">
                <thead>
                  <tr>
                    <th style="width: 30%;">PARAMETER</th>
                    <th style="width: 15%;">NILAI</th>
                    <th>KETERANGAN</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <h3 class="text-center">GIZI</h3>
            <table class="table table-bordered table-padding-sm-no-datatable" id="datatables_tanda_vital_modal_tanda_gizi">
                <thead>
                  <tr>
                    <th style="width: 30%;">PARAMETER</th>
                    <th style="width: 15%;">NILAI</th>
                    <th>KETERANGAN</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">PENGLIHATAN </h5>
                        </div>
                        <div class="card-body">
                    <table class="table table-bordered table-padding-sm-no-datatable">
                        <thead style="text-align: center">
                            <tr>
                                <th colspan="5">VISUS</th>
                                <th rowspan="3" style="vertical-align: middle;text-align: center;">Tes Buta Warna</th>
                            </tr>
                            <tr>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Status</th>
                                <th colspan="2">Tanpa Kacamata</th>
                                <th colspan="2">Dengan Kacamata</th>
                            </tr>
                            <tr>
                                <th>OS (Oculus Sinister)</th>
                                <th>OD (Oculus Dexter)</th>
                                <th>OS (Oculus Sinister)</th>
                                <th>OD (Oculus Dexter)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="text-align: center;">
                                <th style="text-align: center;">Jauh</th>
                                <td><div id="visus_os_tanpa_kacamata_jauh_modal"></div></td>
                                <td><div id="visus_od_tanpa_kacamata_jauh_modal"></div></td>
                                <td><div id="visus_os_kacamata_jauh_modal"></div></td>
                                <td><div id="visus_od_kacamata_jauh_modal"></div></td>
                                <td rowspan="2" style="vertical-align: middle;text-align: center;">
                                    <div id="buta_warna_modal"></div>
                                </td>
                            </tr>
                            <tr style="text-align: center;">
                                <th style="text-align: center;">Dekat</th>
                                <td><div id="visus_os_tanpa_kacamata_dekat_modal"></div></td>
                                <td><div id="visus_od_tanpa_kacamata_dekat_modal"></div></td>
                                <td><div id="visus_os_kacamata_dekat_modal_modal"></div></td>
                                <td><div id="visus_od_kacamata_dekat_modal_modal"></div></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered table-padding-sm-no-datatable">
                        <thead>
                            <tr>
                                <th rowspan="2" style="text-align: center;vertical-align: middle;">Posisi Mata</th>
                                <th colspan="5" class="sub-header" style="text-align: center;">LAPANG PANDANG</th>
                            </tr>
                            <tr style="text-align: center;">
                                <th style="max-width: 150px;width: 150px;">Superior</th>
                                <th style="max-width: 150px;width: 150px;">Inferior</th>
                                <th style="max-width: 150px;width: 150px;">Temporal</th>
                                <th style="max-width: 150px;width: 150px;">Nasal</th>
                                <th colspan="3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>OS (Oculus Sinister)</td>
                                <td><div id="lapang_pandang_superior_os_modal"></div></td>
                                <td><div id="lapang_pandang_inferior_os_modal"></div></td>
                                <td><div id="lapang_pandang_temporal_os_modal"></div></td>
                                <td><div id="lapang_pandang_nasal_os_modal"></div></td>
                                <td colspan="3" class="normal"><div id="lapang_pandang_keterangan_os_modal"></div></td>
                            </tr>
                            <tr>
                                <td>OD (Oculus Dexter)</td>
                                <td><div id="lapang_pandang_superior_od_modal"></div></td>
                                <td><div id="lapang_pandang_inferior_od_modal"></div></td>
                                <td><div id="lapang_pandang_temporal_od_modal"></div></td>
                                <td><div id="lapang_pandang_nasal_od_modal"></div></td>
                                <td colspan="3" class="normal"><div id="lapang_pandang_keterangan_od_modal"></div></td>
                            </tr>
                        </tbody>
                    </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-absolute">
                        <div class="card-header bg-warning">
                            <h5 class="txt-light">KONDISI FISIK</h5>
                        </div>
                        <div class="card-body">
                            <table id="datatables_kondisi_fisik_log_modal" class="table table-bordered table-padding-sm-no-datatable">
                                <tbody></tbody>
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
                            <table id="datatables_hasil_laboratorium_modal" class="table table-bordered table-padding-sm-no-datatable">
                            <thead>
                                <tr>
                                    <th>PARAMTER</th>
                                    <th>HASIL</th>
                                    <th>NILAI RUJUKAN</th>
                                    <th>SATUAN</th>
                                    <th>STATUS</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
.scaled-image_0_3 {
     width: 25%;
  }
</style>
@endsection
@section('js_load')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="{{ asset('vendor/erayadigital/laporan/berkas_mcu.js') }}"></script>
@endsection