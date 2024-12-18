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
                            <p>Formulir untuk kelengkapan MCU berupa informasi tanda vital dan gizi terbaru dari pasien MCU saat test untuk kelengkapan yang akan digunakan untuk laporan MCU dari peserta.</p>
                        </div>
                        <button class="mt-3 btn btn-secondary" id="btnCekDataIni">Cek Data Ini</button>
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
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="table-responsive theme-scrollbar">
                        <table class="table table-bordered border">
                            <thead style="text-align: center;">
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
                                <tr>
                                    <th style="text-align: center;">Jauh</th>
                                    <td><input type="text" class="form-control" id="visus_os_tanpa_kacamata_jauh" name="visus_os_tanpa_kacamata_jauh" placeholder="Nilai Ketajaman Penglihatan 20/20"></td>
                                    <td><input type="text" class="form-control" id="visus_od_tanpa_kacamata_jauh" name="visus_od_tanpa_kacamata_jauh" placeholder="Nilai Ketajaman Penglihatan 20/20"></td>
                                    <td><select class="form-select" id="visus_os_kacamata_jauh" name="visus_os_kacamata_jauh"><option value="Tidak">Tidak</option><option value="Ya">Ya</option></select></td>
                                    <td><select class="form-select" id="visus_od_kacamata_jauh" name="visus_od_kacamata_jauh"><option value="Tidak">Tidak</option><option value="Ya">Ya</option></select></td>
                                    <td rowspan="2" style="vertical-align: middle;text-align: center;">
                                        <div class="card-wrapper border rounded-3 h-100 checkbox-checked" style="text-align: left;">
                                            <div class="form-check radio radio-secondary">
                                              <input class="form-check-input" id="buta_warna_partial" type="radio" name="buta_warna" value="buta_warna_partial">
                                              <label class="form-check-label" for="buta_warna_partial">Buta Warna Partial </label>
                                            </div>
                                            <div class="form-check radio radio-success">
                                              <input checked class="form-check-input" id="buta_warna_tidak" type="radio" name="buta_warna" value="tidak_buta_warna">
                                              <label class="form-check-label" for="buta_warna_tidak">Tidak Buta Warna </label>
                                            </div>
                                            <div class="form-check radio radio-warning">
                                              <input class="form-check-input" id="buta_warna_total" type="radio" name="buta_warna" value="buta_warna_total">
                                              <label class="form-check-label" for="buta_warna_total">Buta Warna Total </label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="text-align: center;">Dekat</th>
                                    <td><input type="text" class="form-control" id="visus_os_tanpa_kacamata_dekat" name="visus_os_tanpa_kacamata_dekat" placeholder="Nilai Ketajaman Penglihatan 20/20"></td>
                                    <td><input type="text" class="form-control" id="visus_od_tanpa_kacamata_dekat" name="visus_od_tanpa_kacamata_dekat" placeholder="Nilai Ketajaman Penglihatan 20/20"></td>
                                    <td><select class="form-select" id="visus_os_kacamata_dekat" name="visus_os_kacamata_dekat"><option value="Tidak">Tidak</option><option value="Ya">Ya</option></select></td>
                                    <td><select class="form-select" id="visus_od_kacamata_dekat" name="visus_od_kacamata_dekat"><option value="Tidak">Tidak</option><option value="Ya">Ya</option></select></td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered border">
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
                                    <td><select class="form-select" id="lapang_pandang_superior_os" name="lapang_pandang_superior_os"><option value="+">+</option><option value="-">-</option></select></td>
                                    <td><select class="form-select" id="lapang_pandang_inferior_os" name="lapang_pandang_inferior_os"><option value="+">+</option><option value="-">-</option></select></td>
                                    <td><select class="form-select" id="lapang_pandang_temporal_os" name="lapang_pandang_temporal_os"><option value="+">+</option><option value="-">-</option></select></td>
                                    <td><select class="form-select" id="lapang_pandang_nasal_os" name="lapang_pandang_nasal_os"><option value="+">+</option><option value="-">-</option></select></td>
                                    <td colspan="3" class="normal"><input type="text" class="form-control" id="lapang_pandang_keterangan_os" name="lapang_pandang_keterangan_os" placeholder="Isikan Keterangan Lapang Pandang OS"></td>
                                </tr>
                                <tr>
                                    <td>OD (Oculus Dexter)</td>
                                    <td><select class="form-select" id="lapang_pandang_superior_od" name="lapang_pandang_superior_od"><option value="+">+</option><option value="-">-</option></select></td>
                                    <td><select class="form-select" id="lapang_pandang_inferior_od" name="lapang_pandang_inferior_od"><option value="+">+</option><option value="-">-</option></select></td>
                                    <td><select class="form-select" id="lapang_pandang_temporal_od" name="lapang_pandang_temporal_od"><option value="+">+</option><option value="-">-</option></select></td>
                                    <td><select class="form-select" id="lapang_pandang_nasal_od" name="lapang_pandang_nasal_od"><option value="+">+</option><option value="-">-</option></select></td>
                                    <td colspan="3" class="normal"><input type="text" class="form-control" id="lapang_pandang_keterangan_od" name="lapang_pandang_keterangan_od" placeholder="Isikan Keterangan Lapang Pandang OD"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                        <button class="btn btn-danger w-100 mt-3" id="bersihkan_penglihatan"><i class="fa fa-refresh"></i> Bersihkan Data</button>                   
                        <button class="btn btn-success w-100 mt-3" id="simpan_penglihatan"><i class="fa fa-save"></i> Simpan Data</button>                   
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <h1 class="mb-2 text-center">Daftar Pemeriksaan Tanda Vital</h1>
            <input type="text" class="form-control" id="kotak_pencarian_penglihatan" placeholder="Cari Informasi Tanda Vital">
            <div class="table-responsive theme-scrollbar">
              <table id="datatables_penglihatan"></table>
            </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="modalLihatParameter" tabindex="-1" role="dialog" aria-labelledby="modalLihatParameterLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nama Pasien : <span id="modal_nama_peserta_parameter"></span></h5>
                <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
            </div>
            <div class="modal-body">
                <div class="table-responsive theme-scrollbar">
                    <table class="table table-bordered border">
                        <thead style="text-align: center;">
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
                    <table class="table table-bordered border">
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
            <div class="modal-footer">
            </div>
      </div>
  </div>
</div>
@endsection
@section('css_load')
<link href="https://cdn.datatables.net/keytable/2.12.1/css/keyTable.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('mofi/assets/css/vendors/tagify.css') }}">
<style>
table.dataTable tbody td.focus {
  outline: 1px dotted rgb(255, 153, 0);
  outline-offset: -2px;
}
</style>
@endsection
@section('js_load')
<script src="https://cdn.datatables.net/keytable/2.12.1/js/dataTables.keyTable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('mofi/assets/js/select2/tagify.js') }}"></script>
<script src="{{ asset('vendor/erayadigital/tingkatkesadaran/penglihatan.js') }}"></script>
@endsection