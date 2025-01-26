@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
  <div class="col-12 col-md-12">
    <div class="card">
      <div class="table-responsive theme-scrollbar">
        <table class="table table-bordered table-padding-sm">
          <thead>
            <tr style="text-align: center;">
              <th colspan="7">Riwayat Informasi</th>
              <th colspan="15">Pemeriksaan Fisik</th>
              <th colspan="8">Poliklinik</th>
              <th>Lab</th>
            </tr>
          </thead>
          <tbody>
            <tr style="text-align: center;">
              <!-- Riwayat Informasi -->
              <td><span class="progress_fdd" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Foto Data Diri">FDD</span></td>
              <td><span class="progress_lk" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Lingkungan Kerja">LK</span></td>
              <td><span class="progress_kk" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Kecelakaan Kerja">KK</span></td>
              <td><span class="progress_kh" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Kebiasaan Hidup">KH</span></td>
              <td><span class="progress_pt" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Penyakit Terdahulu">PT</span></td>
              <td><span class="progress_pk" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Penyakit Keluarga">PK</span></td>
              <td><span class="progress_im" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Imunisasi">IM</span></td>
              <!-- Pemeriksaan Fisik -->
              <td><span class="progress_tk" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Tingkat Kesadaran">TK</span></td>
              <td><span class="progress_tv" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Tanda Vital">TV</span></td>
              <td><span class="progress_eye" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Penglihatan">EYE</span></td>
              <td><span class="progress_kp" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Kepala">KP</span></td>
              <td><span class="progress_tlg" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Telinga">TLG</span></td>
              <td><span class="progress_mt" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Mata">MT</span></td>
              <td><span class="progress_tng" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Tenggorokan">TNG</span></td>
              <td><span class="progress_mlt" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Mulut">MLT</span></td>
              <td><span class="progress_gg" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Gigi">GG</span></td>
              <td><span class="progress_lhr" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Leher">LHR</span></td>
              <td><span class="progress_thx" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Thorax">THX</span></td>
              <td><span class="progress_anu" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Abdomen Urogenital">AnU</span></td>
              <td><span class="progress_ang" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Anorectal Genital">AnG</span></td>
              <td><span class="progress_etm" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Ekstremitas">ETM</span></td>
              <td><span class="progress_nu" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Neurologis">NU</span></td>
              <td><span class="progress_sp" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Spirometri">SP</span></td>
              <td><span class="progress_au" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Audiometri">AU</span></td>
              <td><span class="progress_ekg" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="EKG">EKG</span></td>
              <td><span class="progress_tm" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Threadmill">TM</span></td>
              <td><span class="progress_rsn_thorax" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Rontgen Thorax">RSN</span></td>
              <td><span class="progress_rsn_lumbosacral" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Rontgen Lumbosacral">RSN</span></td>
              <td><span class="progress_usg_ubdomain" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="USG Ubdomain">USG</span></td>
              <td><span class="progress_farmingham_score" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Farmingham Score">FS</span></td>
              <td><span class="progress_lab" data-container="body" data-bs-toggle="tooltip" data-bs-placement="top" title="Lab dan Pengobatan">LAB</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="row" id="error_transaksi" style="display: none;">
  <div class="col-12 col-md-12">
    <div class="card">
      <div class="card-header">
        <h4>Oops..... Ada Yang Salah Nih</h4>
      </div>
      <div class="card-body">
        <div class="alert-box">
          <div class="alert alert-dismissible justify-content-center p-0 fade show" role="alert">
            <div class="alert-body">
              <svg> 
                <use href="{{ asset('mofi/assets/svg/icon-sprite.svg#alert-popup') }}"></use>
              </svg>
              <h6 class="mb-1">Kesalahan Transaksi Dokumen</h6>
              <p>Pengguna ini tidak memiliki data Tindakan. Silahkan lakukan tindakan pada menu TINDAKAN baik MCU atau NON MCU</p>
              <div class="button-box">
                <a href="{{url('laporan/validasi_mcu')}}" class="btn light-background"><i class="fa fa-arrow-left"></i> Kembali</a>
                <a href="{{url('laboratorium/tindakan')}}" class="btn btn-warning"><i class="fa fa-flask"></i> Lakukan Tindakan</a>
              </div>
            </div>
          </div>
         </div>                                            
      </div>
    </div>
  </div>
</div>
<div class="row" id="data_transaksi" style="display: none;">
  <div class="col-12 col-md-12">
    <div class="card">
      <div class="card-header border-t-danger">
        <h4>Berkas MCU / Pengobatan Atas Nama : <span id="nama_peserta"></span> (<span id="umur_peserta"></span> Tahun)</h4>
        <p class="mt-1 f-m-light">Nominal : <span id="harga_paket" style="font-weight: bold;"></span></p>
        <p class="mt-1 f-m-light">Alamat : <span id="alamat_peserta"></span></p>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-md-4">
            <div id="card-sticky-validasi-mcu-nota" class="card social-profile">
              <div class="card-body">
                <div class="social-img-wrap"> 
                  <div class="social-img"><img id="foto_peserta_mcu" src="{{asset('mofi/assets/images/logo/logo_amc.png')}}" alt="profile"></div>
                  <div class="edit-icon">
                    <svg>
                      <use href="{{ asset('mofi/assets/svg/icon-sprite.svg#profile-check') }}"></use>
                    </svg>
                  </div>
                </div>
                <div class="social-details" style="margin-top: 0px;">
                  <h5 class="mb-1"><a href="javascript:void(0)" id="nama_peserta_card"></a></h5>
                  <ul class="card-social" style="margin-top: 0px;">
                    <li><a href="https://www.facebook.com/" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="https://accounts.google.com/" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                    <li><a href="https://twitter.com/" target="_blank"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="https://www.instagram.com/" target="_blank"><i class="fa fa-instagram"></i></a></li>
                    <li><a href="https://rss.app/" target="_blank"><i class="fa fa-rss"></i></a></li>
                  </ul>
                  <ul class="social-follow" style="margin-top: 10px;"> 
                    <li>
                      <h5 class="mb-0"><span id="kedatangan">0</span>x</h5><span class="f-light">Kedatangan</span>
                    </li>
                    <li>
                      <h5 class="mb-0"><span id="terakhir_datang">99-99-9999</span></h5><span class="f-light">Terkahir Datang</span>
                    </li>
                    <li>
                      <h5 class="mb-0"><span id="valuasi">0.00</span></h5><span class="f-light">Valuasi</span>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="card-footer">
                <div class="table-responsive theme-scrollbar">
                  <table style="text-align: left;" class="table table-bordered table-padding-sm">
                    <thead>
                      <tr>
                        <th>Paramter</th>
                        <th>Nilai</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Tanggal MCU</td>
                        <td><span id="tanggal_mcu_tabel"></span></td>
                      </tr>
                      <tr>
                        <td>Nomor Identitas</td>
                        <td><span id="nomor_identitas_tabel"></span></td>
                      </tr>
                      <tr>
                        <td>Tempat Tanggal Lahir</td>
                        <td><span id="ttl_tabel"></span></td>
                      </tr>
                      <tr>
                        <td>Jenis Identitas</td>
                        <td><span id="jenis_identitas_tabel"></span></td>
                      </tr>
                      <tr>
                        <td>Status Perkawinan</td>
                        <td><span id="status_perkawinan_tabel"></span></td>
                      </tr>
                      <tr>
                        <td>Asal Perusahaan</td>
                        <td><span id="asal_perusahaan_tabel"></span></td>
                      </tr>
                      <tr>
                        <td>Departemen</td>  
                        <td><span id="departemen_tabel"></span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-8">
            <div class="card card-absolute">
              <div class="card-header bg-primary">
                <h5 class="txt-light">Kelengkapan Berkas MCU / Pengobatan</h5>
              </div>
              <div class="card-body">
                <div class="d-flex list-behavior-1 align-items-center">
                  <div class="flex-shrink-0"><img class="tab-img img-fluid" src="{{asset('mofi/assets/images/logo/logo_amc.png')}}" alt="home"></div>
                  <div class="flex-grow-1">
                    <p class="mb-xl-0 mb-sm-4">Validasi ini bisa dilakukan oleh dokter yang diakui untuk memastikan bahwa hasil dari pemeriksaan kesehatan tersebut sah dan sesuai dengan standar yang ditetapkan, serta sangat penting terutama untuk keperluan administratif, seperti persyaratan pekerjaan, asuransi, atau keperluan lainnya yang memerlukan bukti bahwa seseorang telah menjalani dan lulus pemeriksaan kesehatan.</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header">
                <h5>Tentukan Status Dokumen Ini</h5>
                <div class="input-group">
                  <select class="form-control" id="select_lab_mcu_verifikasi">
                    <option value="proses">Status Proses</option>
                    <option value="dibatalkan">Status Dibatalkan</option>
                    <option value="selesai">Status Selesai dan Valid</option>
                  </select>
                  <div class="input-group-append">
                    <button class="btn btn-amc-orange" id="btn_lab_mcu_verifikasi"><i class="fa fa-check"></i> Validasi Akhir</button>
                    <button class="btn btn-primary" id="btn_lab_mcu_batal"><i class="fa fa-question"></i> Kesimpulan</button>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive theme-scrollbar">
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
                        <td>Threadmill</td>
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
                        <td>USG Ubdomain</td>
                        <td><span class="progress_usg_ubdomain">USG</span></td>
                        <td><button onclick="process_ajax('usg_ubdomain','modalPoliklinik','USG Ubdomain')" class="btn btn-primary"><i class="fa fa-folder-open"></i> Lihat Data</button></td>
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
                      <tr>
                        <td>31</td>
                        <td>Lab dan Tindakan</td>
                        <td colspan="2">Laboratorium dan Pengobatan</td>
                        <td><span class="progress_lab_bawah">LAB</span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
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
                    <span class="txt-light">Silahkan ubah jikalau terdapat kesalahan pada kesimpulan tindakan pasien ini. Silahkan masukan nomor MCU pada menu <a href="{{url('laporan/validasi_rekap_kesimpulan')}}" target="_blank" style="color: yellow;">Hasil Kesimpulan</a></span>
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
                                <div class="col-md-12 mb-1">
                                    <select class="form-control" id="pemeriksaan_laboratorium_kondisi_select">
                                        <option value="normal">Normal</option>
                                        <option value="abnormal">Abnormal</option>
                                        <option value="dalam_batas_normal">Dalam Batas Normal</option>
                                    </select>
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
        </div>
    </div>
</div>
@include('komponen.information_validasi_mcu_modal')
@endsection
@section('css_load')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<style>
.tooltip-inner {
  background-color: #ff5722 !important; 
  color: #ffffff !important; 
}
.tooltip-arrow::before {
  border-top-color: #ff5722 !important; 
}
.progress_fdd, .progress_lk, .progress_kk, .progress_kh, .progress_pt, .progress_pk, .progress_im, .progress_tk, .progress_tv, .progress_eye, .progress_kp, .progress_tlg, .progress_mt, .progress_tng, .progress_mlt, .progress_gg, .progress_lhr, .progress_thx, .progress_anu, .progress_ang, .progress_etm, .progress_nu, .progress_sp, .progress_ekg, .progress_tm, .progress_rsn, .progress_au, .progress_lab {
  cursor: pointer;
}
</style>
@endsection
@section('js_load')
<script>
    let no_mcu_js = '{{ $data['no_nota'] }}';
</script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="{{ asset('vendor/erayadigital/laporan/validasi_mcu_nota.js') }}"></script>
@endsection