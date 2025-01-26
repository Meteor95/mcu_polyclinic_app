@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Validasi Laporan Tindakan MCU atau Pengobatan Pasien</h4><span>Pada tabel ini adalah pasien yang sudah mendapatkan jadwal MCU atau Pengobatan dan belum melakukan validasi tindakan, silahkan lakukan validasi pada masing masing pasien agar dapat melihat apakah pasien tersebut sudah melakukan tindakan atau belum atau bahkan tindakan yang diterima apakah sesuai paket atau tidak.</span>
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
<div class="modal fade" id="modal_validasi_rekap_kesimpulan" tabindex="-1" role="dialog" aria-labelledby="modal_validasi_rekap_kesimpulanLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal_validasi_rekap_kesimpulan_text">Validasi Kesimpulan Pada Setiap Tindakan</h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="alert alert-dark d-flex align-items-center" role="alert">
                    <div class="mr-2">
                        <i class="fa fa-info-circle fa-2x" style="color: white;"></i>
                    </div>
                    <span class="txt-light">Silahkan lakukan validasi pada setiap tindakan yang dilakukan pasien <span id="nama_pasien"></span> <span id="umur_pasien"></span> dengan nomor MCU <span id="no_mcu"></span> yang akan digunakam pada menu <a href="{{url('laporan/validasi_mcu')}}" target="_blank" style="color: yellow;">Validasi MCU</a></span>
                </div>
                <table id="table_validasi_rekap_kesimpulan" class="table table-bordered table-striped table-padding-sm">
                    <tr>
                        <th>Riwayat Medis</th>
                        <th>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="editor_container">
                                        <div id="riwayat_medis_quill"></div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th>Pemeriksaan Fisik</th>
                        <th>
                            <div class="row">
                                <div class="col-md-9 mb-1">
                                    <select class="form-control" id="pemeriksaan_fisik_select"></select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_fisik', $('#pemeriksaan_fisik_select option:selected').text(), 'gantikan')" class="btn btn-primary w-100" style="height: 45px;">Gantikan</button>
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_fisik', $('#pemeriksaan_fisik_select option:selected').text(), 'tambah')" class="btn btn-primary w-100" style="height: 45px;">Tambah</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="editor_container">
                                        <div id="pemeriksaan_fisik_quill"></div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th>Laboratorium</th>
                        <th>
                            <div class="row">
                                <div class="col-md-3 mb-1">
                                    <select class="form-control" id="pemeriksaan_laboratorium_kondisi_select">
                                        <option value="normal">Normal</option>
                                        <option value="abnormal">Abnormal</option>
                                        <option value="dalam_batas_normal">Dalam Batas Normal</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <select class="form-control" id="pemeriksaan_laboratorium_select"></select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_laboratorium', $('#pemeriksaan_laboratorium_select option:selected').text(), 'gantikan')" class="btn btn-primary w-100" style="height: 45px;">Gantikan</button>
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_laboratorium', $('#pemeriksaan_laboratorium_select option:selected').text(), 'tambah')" class="btn btn-primary w-100" style="height: 45px;">Tambah</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="editor_container">
                                        <div id="pemeriksaan_laboratorium_quill"></div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr class="pemeriksaan_threadmill" style="display: none;">
                        <th>ThreadMill</th>
                        <th>
                            <div class="row">
                                <div class="col-md-9 mb-1">
                                    <select class="form-control" id="pemeriksaan_threadmill_select"></select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_threadmill', $('#pemeriksaan_threadmill_select option:selected').text(), 'gantikan')" class="btn btn-primary w-100" style="height: 45px;">Gantikan</button>
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_threadmill', $('#pemeriksaan_threadmill_select option:selected').text(), 'tambah')" class="btn btn-primary w-100" style="height: 45px;">Tambah</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="editor_container">
                                        <div id="pemeriksaan_threadmill_quill"></div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr class="pemeriksaan_rontgen_thorax" style="display: none;">
                        <th>Rontgen Thorax</th>
                        <th>
                            <div class="row">
                                <div class="col-md-9 mb-1">
                                    <select class="form-control" id="pemeriksaan_rontgen_thorax_select"></select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_rontgen_thorax', $('#pemeriksaan_rontgen_thorax_select option:selected').text(), 'gantikan')" class="btn btn-primary w-100" style="height: 45px;">Gantikan</button>
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_rontgen_thorax', $('#pemeriksaan_rontgen_thorax_select option:selected').text(), 'tambah')" class="btn btn-primary w-100" style="height: 45px;">Tambah</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="editor_container">
                                        <div id="pemeriksaan_rontgen_thorax_quill"></div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr class="pemeriksaan_rontgen_lumbosacral" style="display: none;">
                        <th>Rontgen Lumbosacral</th>
                        <th>
                            <div class="row">
                                <div class="col-md-9 mb-1">
                                    <select class="form-control" id="pemeriksaan_rontgen_lumbosacral_select"></select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_rontgen_lumbosacral', $('#pemeriksaan_rontgen_lumbosacral_select option:selected').text(), 'gantikan')" class="btn btn-primary w-100" style="height: 45px;">Gantikan</button>
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_rontgen_lumbosacral', $('#pemeriksaan_rontgen_lumbosacral_select option:selected').text(), 'tambah')" class="btn btn-primary w-100" style="height: 45px;">Tambah</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="editor_container">
                                        <div id="pemeriksaan_rontgen_lumbosacral_quill"></div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr class="pemeriksaan_usg_ubdomain" style="display: none;">
                        <th>USG Ubdomain</th>
                        <th>
                            <div class="row">
                                <div class="col-md-9 mb-1">
                                    <select class="form-control" id="pemeriksaan_usg_ubdomain_select"></select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_usg_ubdomain', $('#pemeriksaan_usg_ubdomain_select option:selected').text(), 'gantikan')" class="btn btn-primary w-100" style="height: 45px;">Gantikan</button>
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_usg_ubdomain', $('#pemeriksaan_usg_ubdomain_select option:selected').text(), 'tambah')" class="btn btn-primary w-100" style="height: 45px;">Tambah</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="editor_container">
                                        <div id="pemeriksaan_usg_ubdomain_quill"></div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr class="pemeriksaan_farmingham_score" style="display: none;">
                        <th>Farmingham Score</th>
                        <th>
                            <div class="row">
                                <div class="col-md-9 mb-1">
                                    <select class="form-control" id="pemeriksaan_farmingham_score_select"></select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_farmingham_score', $('#pemeriksaan_farmingham_score_select option:selected').text(), 'gantikan')" class="btn btn-primary w-100" style="height: 45px;">Gantikan</button>
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_farmingham_score', $('#pemeriksaan_farmingham_score_select option:selected').text(), 'tambah')" class="btn btn-primary w-100" style="height: 45px;">Tambah</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="editor_container">
                                        <div id="pemeriksaan_farmingham_score_quill"></div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr class="pemeriksaan_ekg" style="display: none;">
                        <th>EKG</th>
                        <th>
                            <div class="row">
                                <div class="col-md-9 mb-1">
                                    <select class="form-control" id="pemeriksaan_ekg_select"></select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_ekg', $('#pemeriksaan_ekg_select option:selected').text(), 'gantikan')" class="btn btn-primary w-100" style="height: 45px;">Gantikan</button>
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_ekg', $('#pemeriksaan_ekg_select option:selected').text(), 'tambah')" class="btn btn-primary w-100" style="height: 45px;">Tambah</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="editor_container">
                                        <div id="pemeriksaan_ekg_quill"></div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr class="pemeriksaan_audiometri" style="display: none;">
                        <th colspan="2" style="text-align: center;"><h3>Audiometri</h3></th>
                    </tr>
                    <tr class="pemeriksaan_audiometri" style="display: none;">
                        <th>Kiri</th>
                        <th>
                            <div class="row">
                                <div class="col-md-9 mb-1">
                                    <select class="form-control" id="pemeriksaan_audiometri_kiri_select"></select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_audiometri_kiri', $('#pemeriksaan_audiometri_kiri_select option:selected').text(), 'gantikan')" class="btn btn-primary w-100" style="height: 45px;">Gantikan</button>
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_audiometri_kiri', $('#pemeriksaan_audiometri_kiri_select option:selected').text(), 'tambah')" class="btn btn-primary w-100" style="height: 45px;">Tambah</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="editor_container">
                                        <div id="pemeriksaan_audiometri_kiri_quill"></div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr class="pemeriksaan_audiometri" style="display: none;">
                        <th>Kanan</th>
                        <th>
                            <div class="row">
                                <div class="col-md-9 mb-1">
                                    <select class="form-control" id="pemeriksaan_audiometri_kanan_select"></select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_audiometri_kanan', $('#pemeriksaan_audiometri_kanan_select option:selected').text(), 'gantikan')" class="btn btn-primary w-100" style="height: 45px;">Gantikan</button>
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_audiometri_kanan', $('#pemeriksaan_audiometri_kanan_select option:selected').text(), 'tambah')" class="btn btn-primary w-100" style="height: 45px;">Tambah</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="editor_container">
                                        <div id="pemeriksaan_audiometri_kanan_quill"></div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr class="pemeriksaan_spirometri" style="display: none;">
                        <th colspan="2" style="text-align: center;"><h3>Spirometri</h3></th>
                    </tr>
                    <tr class="pemeriksaan_spirometri" style="display: none;"   >
                        <th>Restriksi</th>
                        <th>
                            <div class="row">
                                <div class="col-md-9 mb-1">
                                    <select class="form-control" id="pemeriksaan_spirometri_restriksi_select"></select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_spirometri_restriksi', $('#pemeriksaan_spirometri_restriksi_select option:selected').text(), 'gantikan')" class="btn btn-primary w-100" style="height: 45px;">Gantikan</button>
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_spirometri_restriksi', $('#pemeriksaan_spirometri_restriksi_select option:selected').text(), 'tambah')" class="btn btn-primary w-100" style="height: 45px;">Tambah</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="editor_container">
                                        <div id="pemeriksaan_spirometri_restriksi_quill"></div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr class="pemeriksaan_spirometri" style="display: none;">
                        <th>Obstruksi</th>
                        <th>
                            <div class="row">
                                <div class="col-md-9 mb-1">
                                    <select class="form-control" id="pemeriksaan_spirometri_obstruksi_select"></select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_spirometri_obstruksi', $('#pemeriksaan_spirometri_obstruksi_select option:selected').text(), 'gantikan')" class="btn btn-primary w-100" style="height: 45px;">Gantikan</button>
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_spirometri_obstruksi', $('#pemeriksaan_spirometri_obstruksi_select option:selected').text(), 'tambah')" class="btn btn-primary w-100" style="height: 45px;">Tambah</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="editor_container">
                                        <div id="pemeriksaan_spirometri_obstruksi_quill"></div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>

                    <tr>
                        <th>Kesimpulan</th>
                        <th>
                            <div class="row">
                                <div class="col-md-9 mb-1">
                                    <select class="form-control" id="pemeriksaan_kesimpulan_tindakan_select"></select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_kesimpulan_tindakan', $('#pemeriksaan_kesimpulan_tindakan_select option:selected').text(), 'gantikan')" class="btn btn-primary w-100" style="height: 45px;">Gantikan</button>
                                        <button onclick="aksi_onchange_tindakan_kesimpulan('pemeriksaan_kesimpulan_tindakan', $('#pemeriksaan_kesimpulan_tindakan_select option:selected').text(), 'tambah')" class="btn btn-primary w-100" style="height: 45px;">Tambah</button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="editor_container">
                                        <div id="pemeriksaan_kesimpulan_tindakan_quill"></div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th>Saran</th>
                        <th>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="editor_container">
                                        <div id="pemeriksaan_tindakan_saran_quill"></div>
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="konfirmasi_validasi_rekap_kesimpulan"><i class="fa fa-check"></i> Konfirmasi Kesimpulan</button>
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
<script src="{{ asset('vendor/erayadigital/laporan/validasi_rekap_kesimpulan.js') }}"></script>
@endsection