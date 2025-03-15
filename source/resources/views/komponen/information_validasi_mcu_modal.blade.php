<div class="modal fade" id="modalLihatFoto" tabindex="-1" role="dialog" aria-labelledby="modalLihatFotoLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalTambahPenggunaLabel">Foto Pasien MCU {{ config('app.name') }}</h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
              <img id="foto_lihat" class="rounded img-thumbnail mx-auto d-block">
          </div>
          <div class="modal-footer">
              <h5>Nama File : <br><span id="nama_peserta_foto"></span></h5>
          </div>
      </div>
  </div>
</div>
<div class="modal fade" id="modalLingkunganKerja" tabindex="-1" role="dialog" aria-labelledby="modalLingkunganKerjaLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalTambahPenggunaLabel">Lingkungan Kerja</h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <table id="datatables_riwayat_lingkungan_kerja_modal" class="table table-bordered">
              <thead>
                <tr>
                  <th>Paparan Kerja</th>
                  <th>Status</th>
                  <th>Jam / Hari</th>
                  <th>Selama X Tahun</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          <div class="modal-footer">
              
          </div>
      </div>
  </div>
</div>
<div class="modal fade" id="modalKecelakaanKerja" tabindex="-1" role="dialog" aria-labelledby="modalKecelakaanKerjaLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalTambahPenggunaLabel">Berita Informasi Kecelakaan Kerja</h5>
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
<div class="modal fade" id="modalKebiasaanHidup" tabindex="-1" role="dialog" aria-labelledby="modalKebiasaanHidupLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalKebiasaanHidupLabel">Kebiasaan Hidup</h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
            </div>
            <div class="modal-body">
            <table id="datatables_riwayat_kebiasaan_hidup_modal" class="table table-bordered">
              <thead>
                <tr>
                  <th>Kebiasaan Hidup</th>
                  <th>Status</th>
                  <th>Nilai</th>
                  <th>Satuan</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <table id="datatables_riwayat_kebiasaan_hidup_perempuan_modal" class="table table-bordered">
              <thead>
                <tr>
                  <th>Kebiasaan Hidup</th>
                  <th>Status</th>
                  <th>Waktu</th>
                  <th>Satuan</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          <div class="modal-footer">
          </div>
      </div>
  </div>
</div>
<div class="modal fade" id="modalPenyakitTerdahulu" tabindex="-1" role="dialog" aria-labelledby="modalPenyakitTerdahuluLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalPenyakitTerdahuluLabel">Penyakit Terdahulu</h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <table id="datatables_penyakit_terdahulu_modal" class="table table-bordered">
              <thead>
                <tr>
                  <th>Nama Penyakit Terdahulu</th>
                  <th>Status</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          <div class="modal-footer">
          </div>
      </div>
  </div>
</div>
<div class="modal fade" id="modalPenyakitKeluarga" tabindex="-1" role="dialog" aria-labelledby="modalPenyakitKeluargaLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalPenyakitKeluargaLabel">Penyakit Keluarga</h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <table id="datatables_riwayat_penyakit_keluarga_modal" class="table table-bordered">
              <thead>
                <tr>
                  <th>Riwayat Penyakit Keluarga</th>
                  <th>Status</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          <div class="modal-footer">
          </div>
      </div>
  </div>
</div>
<div class="modal fade" id="modalImunisasi" tabindex="-1" role="dialog" aria-labelledby="modalImunisasiLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalImunisasiLabel">Imunisasi</h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <table id="datatables_imunisasi_modal" class="table table-bordered">
              <thead>
                <tr>
                  <th>Nama Imunisasi</th>
                  <th>Status</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          <div class="modal-footer">
          </div>
      </div>
  </div>
</div>
<div class="modal fade" id="modalTingkatKesadaran" tabindex="-1" role="dialog" aria-labelledby="modalTingkatKesadaranLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalTingkatKesadaranLabel">Tingkat Kesadaran</h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Paramter</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Keadaan Umum <span id="modal_keadaan_umum_temp"></span></td>
                        <td><span id="modal_keterangan_keadaan_umum_temp"></span></td>
                    </tr>
                    <tr>
                        <td>Status Kesadaran <span id="modal_status_kesadaran_temp"></span></td>
                        <td><span id="modal_keterangan_status_kesadaran_temp"></span></td>
                    </tr>
                    <tr>
                        <td>Keluhan</td>
                        <td><span id="modal_keluhan_temp"></span></td>
                    </tr>
                </tbody>
            </table>
          </div>
          <div class="modal-footer">
          </div>
      </div>
  </div>
</div>
<div class="modal fade" id="modalTandaVital" tabindex="-1" role="dialog" aria-labelledby="modalTandaVitalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalTandaVitalLabel">Tanda Vital</h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <h1>Tanda Vital</h1>
            <table class="table table-bordered" id="datatables_tanda_vital_modal_tanda_vital">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Tanda Vital</th>
                    <th>Nilai</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <h1>Tanda Gizi</h1>
            <table class="table table-bordered" id="datatables_tanda_vital_modal_tanda_gizi">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Tanda Vital</th>
                    <th>Nilai</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
          </div>
          <div class="modal-footer">
          </div>
      </div>
  </div>
</div>
<div class="modal fade" id="modalPenglihatan" tabindex="-1" role="dialog" aria-labelledby="modalPenglihatanLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalPenglihatanLabel">Penglihatan</h5>
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
<div class="modal fade" id="modalFisik" tabindex="-1" role="dialog" aria-labelledby="modalFisikLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalFisikLabel">Fisik <span id="modal_fisik_lokasi"></span></h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <div class="row" id="modal_fisik_gigi">
                <div class="table table-responsive theme-scrollbar">
                    <table class="table table-bordered" id="datatables_gigi_modal">
                      <thead>
                      <tr>
                          <td rowspan="2" style="text-align: center;">Posisi</td>
                          <td colspan="8" style="text-align: center;">Kanan</td>
                          <td colspan="8" style="text-align: center;">Kiri</td>
                      </tr>
                      <tr style="text-align: center;">
                          <td>8</td>
                          <td>7</td>
                          <td>6</td>
                          <td>5</td>
                          <td>4</td>
                          <td>3</td>
                          <td>2</td>
                          <td>1</td>
                          <td>1</td>
                          <td>2</td>
                          <td>3</td>
                          <td>4</td>
                          <td>5</td>
                          <td>6</td>
                          <td>7</td>
                          <td>8</td>
                      </tr>
                      </thead>
                      <tbody>
                      <tr style="text-align: center;">
                          <td>Atas</td>
                          <td><span id="atas_kanan_8_modal"></span></td>
                          <td><span id="atas_kanan_7_modal"></span></td>
                          <td><span id="atas_kanan_6_modal"></span></td>
                          <td><span id="atas_kanan_5_modal"></span></td>
                          <td><span id="atas_kanan_4_modal"></span></td>
                          <td><span id="atas_kanan_3_modal"></span></td>
                          <td><span id="atas_kanan_2_modal"></span></td>
                          <td><span id="atas_kanan_1_modal"></span></td>
                          <td><span id="atas_kiri_1_modal"></span></td>
                          <td><span id="atas_kiri_2_modal"></span></td>
                          <td><span id="atas_kiri_3_modal"></span></td>
                          <td><span id="atas_kiri_4_modal"></span></td>
                          <td><span id="atas_kiri_5_modal"></span></td>
                          <td><span id="atas_kiri_6_modal"></span></td>
                          <td><span id="atas_kiri_7_modal"></span></td>
                          <td><span id="atas_kiri_8_modal"></span></td>
                      </tr>
                      <tr style="text-align: center;">
                          <td>Bawah</td>
                          <td><span id="bawah_kanan_8_modal"></span></td>
                          <td><span id="bawah_kanan_7_modal"></span></td>
                          <td><span id="bawah_kanan_6_modal"></span></td>
                          <td><span id="bawah_kanan_5_modal"></span></td>
                          <td><span id="bawah_kanan_4_modal"></span></td>
                          <td><span id="bawah_kanan_3_modal"></span></td>
                          <td><span id="bawah_kanan_2_modal"></span></td>
                          <td><span id="bawah_kanan_1_modal"></span></td>
                          <td><span id="bawah_kiri_1_modal"></span></td>
                          <td><span id="bawah_kiri_2_modal"></span></td>
                          <td><span id="bawah_kiri_3_modal"></span></td>
                          <td><span id="bawah_kiri_4_modal"></span></td>
                          <td><span id="bawah_kiri_5_modal"></span></td>
                          <td><span id="bawah_kiri_6_modal"></span></td>
                          <td><span id="bawah_kiri_7_modal"></span></td>
                          <td><span id="bawah_kiri_8_modal"></span></td>
                      </tr>
                      </tbody>
                  </table> 
                    <table class="table table-bordered">
                        <thead>
                        <tr style="background-color: #2A3650;">
                            <td style="color: white;">Singkatan</td>
                            <td style="color: white;">Keterangan</td>
                            <td style="color: white;">Singkatan</td>
                            <td style="color: white;">Keterangan</td>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>C</td>
                                <td>Carries</td>
                                <td>I</td>
                                <td>Impaksi Gigi</td>
                            </tr>
                            <tr>
                                <td>T</td>
                                <td>Tambal Gigi</td>
                                <td>P</td>
                                <td>Gigi Palsu</td>
                            </tr>
                            <tr>
                                <td>M</td>
                                <td>Missing</td>
                                <td>AB</td>
                                <td>Abrasi</td>
                            </tr>
                            <tr>
                                <td>GR</td>
                                <td>Gangren Radix</td>
                                <td>KG</td>
                                <td>karang Gigi</td>
                            </tr>
                            <tr>
                                <td>GP</td>
                                <td>Gangren Pulpae</td>
                                <td>Plaque</td>
                                <td>Plaque</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="table-responsive theme-scrollbar">
              <table class="table table-bordered" id="datatables_kondisi_fisik_log_modal">
                <thead>
                    <tr style="vertical-align: middle;">
                        <th rowspan="2">ID</th>
                        <th rowspan="2" style="display: none;">Kategori Lokasi Fisik</th>
                        <th rowspan="2">Jenis Atribut</th>
                        <th colspan="2" style="text-align: center;">Status</th>
                        <th rowspan="2">Keterangan</th>
                    </tr>
                    <tr style="vertical-align: middle;text-align: center;">
                        <th>Ab-Normal</th>
                        <th>Normal</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </div>
          </div>
          <div class="modal-footer">
          </div>
      </div>
  </div>
</div>
<div class="modal fade" id="modalPoliklinik" tabindex="-1" role="dialog" aria-labelledby="modalPoliklinikLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalPoliklinikLabel">Poliklinik <span id="modal_poliklinik_nama"></span></h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <table class="table table-bordered" id="table_informasi">
              <thead>
                <tr>
                  <th>Paramter</th>
                  <th>Nilai / Hasil</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Judul Unggahan</td>
                  <td><div id="judul_laporan_informasi"></div></td>
                </tr>
                <tr>
                  <td>Kesimpulan</td>
                  <td><div id="kesimpulan_informasi"></div></td>
                </tr>
                <tr>
                  <td colspan="2">Detail Kesimpulan<br><div id="detail_kesimpulan_informasi"></div></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
          </div>
      </div>
  </div>
</div>
