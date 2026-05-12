@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Validasi Kesimpulan Tindakan MCU atau Pengobatan Pasien</h4><span>Pada tabel ini adalah pasien yang sudah mendapatkan jadwal MCU atau Pengobatan dan belum melakukan validasi tindakan, silahkan lakukan validasi pada masing masing pasien agar dapat melihat apakah pasien tersebut sudah melakukan tindakan atau belum atau bahkan tindakan yang diterima apakah sesuai paket atau tidak.</span>
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
              </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <table id="table_validasi_rekap_kesimpulan" class="table table-bordered table-striped table-padding-sm">
                        <tr style="display: none;">
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
                        <tr style="display: none;">
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
                            <th>Tanda Vital dan Gizi</th>
                            <th>
                                <div class="col-md-12 mb-1">
                                    <select class="form-control" id="pemeriksaan_tanda_vital_dan_gizi_select">
                                        <option value="underweight">Underweight</option>
                                        <option value="normal">Normal</option>
                                        <option value="overweight">Overweight</option>
                                        <option value="obesitas_1">Obesitas 1</option>
                                        <option value="obesitas_2">Obesitas 2</option>
                                    </select>
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
                            <th>Treadmill</th>
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
                            <th>USG Abdomain</th>
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
                                    <div class="col-md-10 mb-1">
                                        <select class="form-control" id="pemeriksaan_kesimpulan_non_status_kesehatan_select">
                                            <option value="fit_to_work">FIT TO WORK</option>
                                            <option value="fit_with_note">FIT WITH NOTE</option>
                                            <option value="temporary_unfit">TEMPORARY UNFIT</option>
                                            <option value="unfit">UNFIT</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-1">
                                        <div class="dropdown" style="width: 100%;" id="colorDropdown">
                                            <div class="dropdown-toggle">
                                                <div class="color-box" id="selectedColor" style="background-color: #90ee90;"></div>
                                            </div>
                                            <div class="dropdown-menu">
                                                <div class="option" data-color="#90ee90"><div class="color-box" style="background-color: #90ee90;"></div></div>
                                                <div class="option" data-color="#ffff00"><div class="color-box" style="background-color: #ffff00;"></div></div>
                                                <div class="option" data-color="#ffa500"><div class="color-box" style="background-color: #ffa500;"></div></div>
                                                <div class="option" data-color="#f8786e"><div class="color-box" style="background-color: #f8786e;"></div></div>
                                            </div>
                                            <input type="hidden" name="favcolor" id="favcolor" value="#90ee90">
                                        </div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>Status Kesehatan</th>
                            <th>
                                <div class="row">
                                    <div class="col-md-12 mb-1">
                                        <select class="form-control" id="pemeriksaan_kesimpulan_tindakan_select"></select>
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
                <div class="col-sm-4">
                    <table class="table table-bordered table-padding-sm-no-datatable">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Group</th>
                            <th>Nama Tindakan Pemeriksaan</th>
                            <th>Ada Data</th>
                            <th>Tampilkan</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td rowspan="7" style="background-color:rgb(224, 99, 3);color:white;">Riwayat Informasi</td>
                            <td>Foto Data Diri</td>
                            <td><span class="progress_fdd">FDD</span></td>
                            <td><button onclick="process_ajax('fdd','modalLihatFoto')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Lingkungan Kerja</td>
                            <td><span class="progress_lk">LK</span></td>
                            <td><button onclick="process_ajax('lk','modalLingkunganKerja')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Kecelakaan Kerja</td>
                            <td><span class="progress_kk">KK</span></td>
                            <td><button onclick="process_ajax('kk','modalKecelakaanKerja')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Kebiasaan Hidup</td>
                            <td><span class="progress_kh">KH</span></td>
                            <td><button onclick="process_ajax('kh','modalKebiasaanHidup')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Penyakit Terdahulu</td>
                            <td><span class="progress_pt">PT</span></td>
                            <td><button onclick="process_ajax('pt','modalPenyakitTerdahulu')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Penyakit Keluarga</td>
                            <td><span class="progress_pk">PK</span></td>
                            <td><button onclick="process_ajax('pk','modalPenyakitKeluarga')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Imunisasi</td>
                            <td><span class="progress_im">IM</span></td>
                            <td><button onclick="process_ajax('im','modalImunisasi')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td rowspan="15" style="background-color:rgb(68, 227, 54);color:black;">Pemeriksaan Fisik</td>
                            <td>Tingkat Kesadaran</td >
                            <td><span class="progress_tk">TK</span></td>
                            <td><button onclick="process_ajax('tk','modalTingkatKesadaran')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>Tanda Vital</td>
                            <td><span class="progress_tv">TV</span></td>
                            <td><button onclick="process_ajax('tv','modalTandaVital')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>Penglihatan</td>
                            <td><span class="progress_eye">EYE</span></td>
                            <td><button onclick="process_ajax('eye','modalPenglihatan')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>Kepala</td>
                            <td><span class="progress_kp">KP</span></td>
                            <td><button onclick="process_ajax('kp','modalFisik','Kepala')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>12</td>
                            <td>Telinga</td>
                            <td><span class="progress_tlg">TLG</span></td>
                            <td><button onclick="process_ajax('tlg','modalFisik','Telinga')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>13</td>
                            <td>Mata</td>
                            <td><span class="progress_mt">MT</span></td>
                            <td><button onclick="process_ajax('mt','modalFisik','Mata')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>14</td>
                            <td>Tenggorokan</td>
                            <td><span class="progress_tng">TNG</span></td>
                            <td><button onclick="process_ajax('tng','modalFisik','Tenggorokan')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>15</td>
                            <td>Mulut</td>
                            <td><span class="progress_mlt">MLT</span></td>
                            <td><button onclick="process_ajax('mlt','modalFisik','Mulut')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>16</td>
                            <td>Gigi</td>
                            <td><span class="progress_gg">GG</span></td>
                            <td><button onclick="process_ajax('gg','modalFisik','Gigi')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>17</td>
                            <td>Leher</td>
                            <td><span class="progress_lhr">LHR</span></td>
                            <td><button onclick="process_ajax('lhr','modalFisik','Leher')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>18</td>
                            <td>Thorax</td>
                            <td><span class="progress_thx">THX</span></td>
                            <td><button onclick="process_ajax('thx','modalFisik','Thorax')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>19</td>
                            <td>Abdomen Urogenital</td>
                            <td><span class="progress_anu">AnU</span></td>
                            <td><button onclick="process_ajax('anu','modalFisik','Abdomen Urogenital')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>20</td>
                            <td>Anorectal Genital</td>
                            <td><span class="progress_ang">AnG</span></td>
                            <td><button onclick="process_ajax('ang','modalFisik','Anorectal Genital')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>21</td>
                            <td>Ekstremitas</td>
                            <td><span class="progress_etm">ETM</span></td>
                            <td><button onclick="process_ajax('etm','modalFisik','Ekstremitas')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>22</td>
                            <td>Neurologis</td>
                            <td><span class="progress_nu">NU</span></td>
                            <td><button onclick="process_ajax('nu','modalFisik','Neurologis')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>23</td>
                            <td rowspan="8" style="background-color:rgb(1, 54, 171);color:white;">Poliklinik</td>
                            <td>Spirometri</td>
                            <td><span class="progress_sp">SP</span></td>
                            <td><button onclick="process_ajax('sp','modalPoliklinik','Spirometri')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>24</td>
                            <td>EKG</td>
                            <td><span class="progress_ekg">EKG</span></td>
                            <td><button onclick="process_ajax('ekg','modalPoliklinik','EKG')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>25</td>
                            <td>Treadmill</td>
                            <td><span class="progress_tm">TM</span></td>
                            <td><button onclick="process_ajax('tm','modalPoliklinik','Threadmill')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>26</td>
                            <td>Rontgen Thorax</td>
                            <td><span class="progress_rsn_thorax">RSN</span></td>
                            <td><button onclick="process_ajax('rsn_thorax','modalPoliklinik','Rontgen Thorax')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>27</td>
                            <td>Rontgen Lumbosacral</td>
                            <td><span class="progress_rsn_lumbosacral">LBS</span></td>
                            <td><button onclick="process_ajax('rsn_lumbosacral','modalPoliklinik','Rontgen Lumbosacral')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>28</td>
                            <td>USG Abdomain</td>
                            <td><span class="progress_usg_ubdomain">USG</span></td>
                            <td><button onclick="process_ajax('usg_ubdomain','modalPoliklinik','USG Abdomain')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>29</td>
                            <td>Farmingham Score</td>
                            <td><span class="progress_farmingham_score">FS</span></td>
                            <td><button onclick="process_ajax('farmingham_score','modalPoliklinik','Farmingham Score')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        <tr>
                            <td>30</td>
                            <td>Audiometri</td>
                            <td><span class="progress_au">AU</span></td>
                            <td><button onclick="process_ajax('au','modalPoliklinik','Audiometri')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
                        </tr>
                        </tbody>
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
@include('komponen.information_validasi_mcu_modal')
@endsection
@section('css_load')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<style>
.modal-body {
    overflow-y: auto;
}
.dropdown {
    position: relative;
    display: inline-block;
    width: 100%;
}

.dropdown-toggle {
    width: 200px;
    height: 40px;
    cursor: pointer;
    border: 1px solid #ccc;
    border-radius: 6px;
    background-color: #fff;
}

.dropdown-toggle .color-box {
    width: 100%;
    height: 100%;
    border-radius: 4px;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1;
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 6px;
    background-color: #fff;
    display: none;
}

.dropdown-menu .option {
    height: 40px;
    cursor: pointer;
}

.dropdown-menu .option:hover {
    outline: 2px solid black;
}

.option .color-box {
    width: 100%;
    height: 100%;
    border-radius: 4px;
}
.progress_fdd, .progress_lk, .progress_kk, .progress_kh, .progress_pt, .progress_pk, .progress_im, .progress_tk, .progress_tv, .progress_eye, .progress_kp, .progress_tlg, .progress_mt, .progress_tng, .progress_mlt, .progress_gg, .progress_lhr, .progress_thx, .progress_anu, .progress_ang, .progress_etm, .progress_nu, .progress_sp, .progress_ekg, .progress_tm, .progress_rsn, .progress_au, .progress_lab {
  cursor: pointer;
}
</style>
@endsection
@section('js_load')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="{{ asset('vendor/erayadigital/laporan/validasi_rekap_kesimpulan.js') }}?v={{ filemtime(public_path('vendor/erayadigital/laporan/validasi_rekap_kesimpulan.js')) }}"></script>
<script>
    const dropdown = document.getElementById("colorDropdown");
    const toggle = dropdown.querySelector(".dropdown-toggle");
    const menu = dropdown.querySelector(".dropdown-menu");
    const selectedColorBox = document.getElementById("selectedColor");
    const hiddenInput = document.getElementById("favcolor");

    toggle.addEventListener("click", () => {
        menu.style.display = menu.style.display === "block" ? "none" : "block";
    });

    dropdown.querySelectorAll(".option").forEach(option => {
        option.addEventListener("click", () => {
            const color = option.getAttribute("data-color");
            selectedColorBox.style.backgroundColor = color;
            hiddenInput.value = color;
            menu.style.display = "none";
        });
    });

    document.addEventListener("click", (e) => {
        if (!dropdown.contains(e.target)) {
            menu.style.display = "none";
        }
    });
</script>
@endsection