@extends('templatebody')
@section('konten_utama')
<div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="landing-page">
      <!-- Page Body Start            -->
      <div class="landing-home"><span class="cursor"><span class="cursor-move-inner"><span class="cursor-inner"></span></span><span class="cursor-move-outer"><span class="cursor-outer"></span></span></span>
        <div class="paginacontainer">
          <div class="progress-wrap">
            <svg class="progress-circle svg-content" width="100%" height="100%" viewbox="-1 -1 102 102">
              <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"></path>
            </svg>
          </div>
        </div>
        <div class="container-fluid">
          @include('includes.EndUser.header_formulir')
        </div>
      </div>
      <section class="section-space app-section overflow-hidden" id="header_formulir">
      <div class="row default-dashboard demo-section">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header">
              <img src="{{asset('mofi/assets/images/landing/header_formulir.gif')}}" style="width:100%" alt="Header Image">
              <h2>INFORM CONSENT<br>SAYA YANG BERTANDA TANGAN DIBAWAH INI MENYATAKAN :</h2>
                <ol class="text-start">
                  <li>Mengetahui jenis pemeriksaan yang akan dilakukan</li>
                  <li>Bersedia mengikuti pemeriksaan kesehatan (Medical Check Up) tanpa paksaan</li>
                  <li>Mengizinkan Klinik Artha Medical Center menyerahkan hasil MCU kepada perusahaan yang mengirim saya</li>
                </ol> 
            </div>
            <div class="card-body">
              <div class="d-flex justify-content-between mt-3 step-indicators">
                <button class="btn btn-outline-primary step-indicator" data-step="0">Data Pribadi</button>
                <button class="btn btn-outline-primary step-indicator" data-step="1">Lingkungan Kerja</button>
                <button class="btn btn-outline-primary step-indicator" data-step="2">Kecelakaan Kerja</button>
                <button class="btn btn-outline-primary step-indicator" data-step="3">Kebiasaan Hidup</button>
                <button class="btn btn-outline-primary step-indicator" data-step="4">Penyakit Terdahulu</button>
                <button class="btn btn-outline-primary step-indicator" data-step="5">Penyakit Keluarga</button>
                <button class="btn btn-outline-primary step-indicator" data-step="6">Imunisasi</button>
              </div>
              <div class="steps">
                <!-- Step 1 -->
                <div class="step active" data-step="1">
                  <h5>Formulir Data Pribadi</h5>
                  <div class="formulir_group">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nomor_identitas_temp" style="font-weight:bold" class="form-label">Nomor Identitas (KTP /SIM / Paspor)</label>
                                <input placeholder="Ex: 3602041211870001" type="text" class="form-control" id="nomor_identitas_temp" name="nomor_identitas_temp">
                                <div class="invalid-feedback">Masukan nomor identitas yang valid</div>
                                <div class="valid-feedback">Terlihat bagus! Nomor identitas sudah terisi</div>
                            </div>
                            <div class="mb-3">
                                <label for="nama_peserta_temp" style="font-weight:bold"  class="form-label">Nama Peserta</label>
                                <input placeholder="Ex: John Doe" type="text" class="form-control" id="nama_peserta_temp" name="nama_peserta_temp">
                                <div class="invalid-feedback">Masukan nama peserta yang valid</div>
                                <div class="valid-feedback">Terlihat bagus! Nama peserta sudah terisi</div>
                            </div>
                            <div class="mb-3">
                                <label for="tempat_lahir_temp" style="font-weight:bold"  class="form-label">Tempat Lahir</label>
                                <input placeholder="Ex: Jakarta" type="text" class="form-control" id="tempat_lahir_temp" name="tempat_lahir_temp">
                                <div class="invalid-feedback">Masukan tempat lahir yang valid</div>
                                <div class="valid-feedback">Terlihat bagus! Tempat lahir sudah terisi</div>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_lahir_peserta_temp" style="font-weight:bold"  class="form-label">Tanggal Lahir</label>
                                <input type="text" class="form-control" id="tanggal_lahir_peserta_temp" name="tanggal_lahir_peserta_temp" placeholder="dd-mm-yyyy">
                                <div class="invalid-feedback">Masukan tanggal lahir yang valid</div>
                                <div class="valid-feedback">Terlihat bagus! Tanggal lahir sudah terisi</div>
                            </div>
                            <div class="mb-3">
                                <label for="tipe_identitas_temp" style="font-weight:bold"  class="form-label">Tipe Identitas</label>
                                <select class="form-select" id="tipe_identitas_temp" name="tipe_identitas_temp" required>
                                    <option value="KTP">KTP</option>
                                    <option value="SIM">SIM</option>
                                    <option value="Paspor">Paspor</option>
                                    <option value="Visa">Visa</option>
                                </select>
                                <div class="invalid-feedback">Pilih tipe identitas</div>
                                <div class="valid-feedback">Terlihat bagus! Tipe identitas sudah dipilih</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenis_kelamin_temp" style="font-weight:bold"  class="form-label">Jenis Kelamin</label>
                                <select class="form-select" id="jenis_kelamin_temp" name="jenis_kelamin_temp" required>
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                    <option value="Alien">Alien</option>
                                </select>
                                <div class="invalid-feedback">Pilih jenis kelamin</div>
                                <div class="valid-feedback">Terlihat bagus! Jenis kelamin sudah dipilih</div>
                            </div>
                            <div class="mb-3">
                                <label for="status_perkawinan_temp" style="font-weight:bold"  class="form-label">Status Perkawinan</label>
                                <select class="form-select" id="status_perkawinan_temp" name="status_perkawinan_temp" required>
                                    <option value="Belum Menikah">Belum Menikah</option>
                                    <option value="Menikah">Menikah</option>
                                    <option value="Cerai Hidup">Cerai Hidup</option>
                                    <option value="Cerai Mati">Cerai Mati</option>
                                </select>
                                <div class="invalid-feedback">Pilih status perkawinan</div>
                                <div class="valid-feedback">Terlihat bagus! Status perkawinan sudah dipilih</div>
                            </div>
                            <div class="mb-3">
                                <label for="no_telepon_temp" style="font-weight:bold"  class="form-label">Nomor Telepon (Rekomendasi Whatsapp)</label>
                                <input placeholder="Ex: 081234567890" type="tel" class="form-control" id="no_telepon_temp" name="no_telepon_temp" required>
                                <div class="invalid-feedback">Masukan nomor telepon yang valid</div>
                                <div class="valid-feedback">Terlihat bagus! Nomor telepon sudah terisi</div>
                            </div>
                            <div class="mb-3">
                                <label for="alamat_surel_temp" style="font-weight:bold"  class="form-label">Alamat surel</label>
                                <input placeholder="Ex: aries@erayadigital.co.id" type="text" class="form-control" id="alamat_surel_temp" name="alamat_surel_temp">
                            </div>
                            <div class="mb-3">
                              <label for="proses_kerja_temp" style="font-weight:bold"  class="form-label">Tentukan Proses Kerja</label>
                              <div class="form-check-size" style="padding-top:10px">
                                  <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                      <input class="form-check-input" name="proses_kerja_temp" id="duduk" value="Duduk" type="checkbox">
                                      <label class="form-check-label" for="duduk">Duduk</label>
                                  </div>
                                  <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                      <input class="form-check-input" name="proses_kerja_temp" id="berdiri" value="Berdiri" type="checkbox">
                                      <label class="form-check-label" for="berdiri">Berdiri</label>
                                  </div>
                                  <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                                      <input checked class="form-check-input" name="proses_kerja_temp" id="kombinasi" value="Kombinasi" type="checkbox">
                                      <label class="form-check-label" for="kombinasi">Kombinasi</label>
                                  </div>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="alamat_tempat_tinggal_temp" style="font-weight:bold"  class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat_tempat_tinggal_temp" name="alamat_tempat_tinggal_temp" rows="3" placeholder="Melak Ulu, Kec. Melak, Kabupaten Kutai Barat, Kalimantan Timur 75775" required></textarea>
                                <div class="invalid-feedback">Masukan alamat yang valid</div>
                                <div class="valid-feedback">Terlihat bagus! Alamat sudah terisi</div>
                            </div>
                        </div>
                    </div>
                  </div>
                <button class="btn btn-primary next-btn">Selanjutnya <i class="fa fa-arrow-right"></i> </button>
                </div>

                <!-- Step 2 -->
                <div class="step" data-step="2">
                  <h5>Formulir Lingkungan Kerja</h5>
                  <div class="row">
                      @foreach ($data['lingkungan_kerja'] as $index => $lk)
                      <div class="mb-3 col-lg-4 text-start">
                          <span class="nama-atribut-lingkungan-kerja" data-index="{{$index}}">{{$lk->nama_atribut_lk}}</span>
                      </div>
                      <div class="mb-3 col-lg-4">
                          <select class="form-select status-atribut-lingkungan-kerja" data-index="{{$index}}" aria-label="Status">
                              <option value="" selected>Status {{$lk->nama_atribut_lk}}</option>
                              <option value="1">Ya</option>
                              <option value="0">Tidak</option>
                          </select>
                      </div>
                      <div class="mb-3 col-lg-2">
                          <input type="text" class="form-control jamperhari-atribut" data-index="{{$index}}" placeholder="Berapa Jam / Hari">
                      </div>
                      <div class="mb-3 col-lg-2">
                          <input type="text" class="form-control selamaxtahun-atribut" data-index="{{$index}}" placeholder="Selama Berapa Tahun">
                      </div>
                      @endforeach
                  </div>
                  <button class="btn btn-secondary prev-btn"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                  <button class="btn btn-primary next-btn">Selanjutnya <i class="fa fa-arrow-right"></i> </button>
                </div>

                <!-- Step 3 -->
                <div class="step" data-step="3">
                  <h5>Formulir Kecalakan Kerja</h5>
                  <div class="mb-3">
                    <textarea placeholder="Jelaskan kecelakaan kerja jikalau ada" class="form-control" name="informasi_kecelakaan_kerja" id="informasi_kecelakaan_kerja_temp" rows="3"></textarea>
                  </div>
                  <button class="btn btn-secondary prev-btn"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                  <button class="btn btn-primary next-btn">Selanjutnya <i class="fa fa-arrow-right"></i> </button>
                </div>

                <!-- Step 4 -->
                <div class="step" data-step="4">
                  <h5>Formulir Kebiasaan Hidup</h5>
                  <div class="row">
                  @foreach ($data['kebiasaan_hidup'] as $index => $kh)
                  <div class="mb-3 col-lg-4 col-sm-12 text-start nama-atribut-kebiasaan-hidup" data-index="{{$index}}">
                    {{$kh->nama_atribut_kb}}
                  </div>
                  <div class="mb-3 col-lg-3 col-sm-12">
                    <select class="form-select status-atribut-kebiasaan-hidup" data-index="{{$index}}" aria-label="Status">
                      <option value="" selected>Status {{$kh->nama_atribut_kb}}</option>
                      <option value="1">Ya</option>
                      <option value="0">Tidak</option>
                    </select>
                  </div>
                  <div class="mb-3 col-lg-5 col-sm-6">
                    <input type="text" class="form-control nilai-atribut-kebiasaan-hidup" id="nilai_kebiasaan_hidup" placeholder="Berapa Kali {{$kh->nama_atribut_kb}} {{$kh->nama_satuan_kb}}" data-index="{{$index}}">
                  </div>
                  <div style="display: none;" class="info-atribut-kebiasaan-hidup" data-index="{{$index}}">{{$kh->nama_atribut_kb}} {{$kh->nama_satuan_kb}}</div>
                  @endforeach
                  </div>
                  <button class="btn btn-secondary prev-btn"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                  <button class="btn btn-primary next-btn">Selanjutnya <i class="fa fa-arrow-right"></i> </button>
                </div>

                <!-- Step 5 -->
                <div class="step" data-step="5">
                  <h5>Formulir Penyakit Terdahulu</h5>
                  <div class="row">
                  @foreach ($data['penyakit_terdahulu'] as $index => $pt)
                  <div class="mb-4 col-md-4 text-start nama-atribut-penyakit-terdahulu" data-index="{{$index}}">
                    {{$pt->nama_atribut_pt}}
                  </div>
                  <div class="mb-3 col-md-4">
                    <select class="form-select status-atribut-penyakit-terdahulu" data-index="{{$index}}" aria-label="Status">
                      <option value="" selected>Status {{$pt->nama_atribut_pt}}</option>
                      <option value="1">Ya</option>
                      <option value="0">Tidak</option>
                    </select>
                  </div>
                  <div class="mb-3 col-md-4">
                    <input type="text" class="form-control keterangan-atribut-penyakit-terdahulu" id="keterangan_penyakit_terdahulu" placeholder="Keterangan {{$pt->nama_atribut_pt}}" data-index="{{$index}}">
                  </div>
                  @endforeach
                  </div>
                  <button class="btn btn-secondary prev-btn"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                  <button class="btn btn-primary next-btn">Selanjutnya <i class="fa fa-arrow-right"></i> </button>
                </div>

                <!-- Step 6 -->
                <div class="step" data-step="6">
                  <h5>Formuilr Penyakit Keluarga</h5>
                  <div class="row">
                  @foreach ($data['penyakit_keluarga'] as $index => $pk)
                  <div class="mb-4 col-md-4 text-start nama-atribut-penyakit-keluarga" data-index="{{$index}}">
                    {{$pk->nama_atribut_pk}}
                  </div>
                  <div class="mb-3 col-md-4">
                    <select class="form-select status-atribut-penyakit-keluarga" data-index="{{$index}}" aria-label="Status">
                      <option value="" selected>Status {{$pk->nama_atribut_pk}}</option>
                      <option value="1">Ya</option>
                      <option value="0">Tidak</option>
                    </select>
                  </div>
                  <div class="mb-3 col-md-4">
                    <input type="text" class="form-control keterangan-atribut-penyakit-keluarga" id="keterangan_penyakit_keluarga" placeholder="Keterangan {{$pk->nama_atribut_pk}}" data-index="{{$index}}">
                  </div>
                  @endforeach
                  </div>
                  <button class="btn btn-secondary prev-btn"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                  <button class="btn btn-primary next-btn">Selanjutnya <i class="fa fa-arrow-right"></i> </button>
                </div>

                <!-- Step 7 -->
                <div class="step" data-step="7">
                  <h5>Formuilr Imunisasi</h5>
                  <div class="row">
                  @foreach ($data['imunisasi'] as $index => $im)
                  <div class="mb-4 col-md-4 text-start nama-atribut-imunisasi" data-index="{{$index}}">
                    {{$im->nama_atribut_im}}
                  </div>
                  <div class="mb-3 col-md-4">
                    <select class="form-select status-atribut-imunisasi" data-index="{{$index}}" aria-label="Status">
                      <option value="" selected>Status {{$im->nama_atribut_im}}</option>
                      <option value="1">Ya</option>
                      <option value="0">Tidak</option>
                    </select>
                  </div>
                  <div class="mb-3 col-md-4">
                    <input type="text" class="form-control keterangan-atribut-imunisasi" id="keterangan_imunisasi" placeholder="Keterangan {{$im->nama_atribut_im}}" data-index="{{$index}}">
                  </div>
                  @endforeach
                  </div>
                  <button class="btn btn-secondary prev-btn"><i class="fa fa-arrow-left"></i> Sebelumnya</button>
                  <button id="pratinjau_halaman" class="btn btn-success next-btn"><i class="fa fa-eye"></i> Pratinjau</button>
                </div>
              </div>
            </div>
            <div class="card-footer">
            </div>
          </div>
        </div>
      </div>
      </section>
      <section class="faq-section section-space overflow-hidden" id="faq">
        <div class="container-fluid">
          <div class="title">
            <h5 class="sub-title">Popular & Sleek Design Assortment</h5>
            <h2>Opt for Sandbox, economize time and funds.</h2>
            <p>We comes up with most creative and useful main general Purpose Dashboard Template</p>
          </div>
        </div>
        <div class="container">
          <div class="row align-items-center justify-content-center">
            <div class="col-lg-4 col-12">
              <div class="d-flex">
                <div class="flex-shrink-0"><img class="img-30 img-fluid" src="{{asset('mofi/assets/images/landing/faq/puzzle.png')}}" alt="Education"></div>
                <div class="flex-grow-1">
                  <h3>Color and Font Variety Choices</h3>
                  <p class="mb-0">Effortlessly modify colors and fonts, or select from available choices. </p>
                </div>
              </div>
              <div class="d-flex">
                <div class="flex-shrink-0"><img class="img-40 img-fluid" src="{{asset('mofi/assets/images/landing/faq/pen-tool.png')}}" alt="Education"></div>
                <div class="flex-grow-1">
                  <h3>Impressive Attributes & Components</h3>
                  <p class="mb-0">Loaded with captivating features and elements to craft attractive pages.</p>
                </div>
              </div>
              <div class="d-flex">
                <div class="flex-shrink-0"><img class="img-30 img-fluid" src="{{asset('mofi/assets/images/landing/faq/pen-tool1.png')}}" alt="Education"></div>
                <div class="flex-grow-1">
                  <h3>Contemporary Portfolio Arrangements</h3>
                  <p class="mb-0">Easily generate and uphold a visually striking and influential portfolio.</p>
                </div>
              </div>
            </div>
            <div class="col-lg-8 col-12"> 
              <div class="faq-img"><img class="img-fluid" src="{{asset('mofi/assets/images/landing/mi1.png')}}" alt="faq">
                <div class="faq-bg"><img class="img-fluid" src="{{asset('mofi/assets/images/landing/faq/1.png')}}" alt=""></div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
  <div class="modal fade" id="modalPratinjau" tabindex="-1" role="dialog" aria-labelledby="modalPratinjauLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-fullscreen" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalPratinjauLabel">Pratinjau Formulir Pendaftaran</h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <h5 class="text-center"> Pratinjau Formulir Data Pribadi</h5>
            <table class="table table-striped table-padding-sm-no-datatable">
              <tr>
                <th>Nomor Identitas</th><td>: <span id="modal_nomor_identitas"></span></td></tr>
              <tr>
                <th>Nama Peserta</th><td>: <span id="modal_nama_peserta"></span></td>
              </tr>
              <tr>
                <th>Tempat Lahir</th><td>: <span id="modal_tempat_lahir"></span></td></tr>
              <tr>
                <th>Tanggal Lahir</th><td>: <span id="modal_tanggal_lahir"></span></td>
              </tr>
              <tr>
                <th>Tipe Identitas</th><td>: <span id="modal_tipe_identitas"></span></td>
              </tr>
              <tr>
                <th>Jenis Kelamin</th><td>: <span id="modal_jenis_kelamin"></span></td>
              </tr>
              <tr>
                <th>Status Perkawaninan</th><td>: <span id="modal_status_perkawinan"></span></td>
              </tr>
              <tr>
                <th>No Telpon (Whatsapp Direkomendasikan)</th><td>: <span id="modal_no_telepon"></span></td>
              </tr>
              <tr>
                <th>Alamat Surel</th><td>: <span id="modal_alamat_surel"></span></td>
              </tr>
              <tr>
                <th>Alamat Tempat Tinggal</th><td>: <span id="modal_alamat_tempat_tinggal"></span></td>
              </tr>
              <tr>
                <th>Proses Kerja</th><td>: <span id="modal_proses_kerja"></span></td>
              </tr>
            </table>
            <h5 class="text-center"> Pratinjau Lingkungan Kerja</h5>
            <table class="table table-striped table-padding-sm-no-datatable" id="tabel_ini"></table>
            <h5 class="text-center"> Pratinjau Kecelakaan Kerja</h5>
            <div id="modal_informasi_kecelakaan_kerja"></div>
            <h5 class="text-center"> Pratinjau Kebiasaan Hidup</h5>
            <table class="table table-striped table-padding-sm-no-datatable" id="tabel_ini_kebiasaan_hidup"></table>
            <h5 class="text-center"> Pratinjau Penyakit Terdahulu</h5>
            <table class="table table-striped table-padding-sm-no-datatable" id="tabel_ini_penyakit_terdahulu"></table>
            <h5 class="text-center"> Pratinjau Penyakit Keluarga</h5>
            <table class="table table-striped table-padding-sm-no-datatable" id="tabel_ini_penyakit_keluarga"></table>
            <h5 class="text-center"> Pratinjau Imunisasi</h5>
            <table class="table table-striped table-padding-sm-no-datatable" id="tabel_ini_imunisasi"></table>
          </div>
          <div class="modal-footer">
              <input type="checkbox" style="cursor: pointer;" class="form-check-input" id="setuju_data_benar" name="setuju_data_benar">
              <label class="form-check-label" style="font-size: 20px;cursor: pointer;" for="setuju_data_benar">Data Sudah Benar dan Akurat</label>
              <button disabled class="btn btn-primary w-100" id="btn_kirim_formulir"><i class="fa fa-paper-plane"></i> Kirim Data</button>
          </div>
          </div>
      </div>
  </div>
</div>
@endsection
@section('css_load')
<link rel="stylesheet" type="text/css" href="{{asset('mofi/assets/css/vendors/flatpickr/flatpickr.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('mofi/assets/css/vendors/bt5-tag-input/tags.css')}}">
<style>
.demo-section{
    padding-top:10px;
    max-width:1320px;
    margin:0 auto;
  }
@media only screen and (max-width: 600px) {
  .step-indicators {
    overflow-x: auto;
    white-space: nowrap;
    -ms-overflow-style: none;  /* untuk Internet Explorer dan Edge */
    scrollbar-width: none;     /* untuk Firefox */
  }
  .step-indicators::-webkit-scrollbar {
    display: none;             /* untuk Chrome, Safari, dan Opera */
  }
  .step-indicator {
    flex-shrink: 0;
    margin-right: 10px;
  }
  .demo-section{
    padding-top:60px;
  }
}
.form-control, .form-select{
  border: 2px dashed rgba(106, 113, 133, 0.3);
}
/* Container untuk steps */
.steps {
  position: relative;
  overflow: hidden;
  width: 100%;
}

/* Setiap step */
.step {
  display: none;
  width: 100%;
  transition: transform 0.3s ease-in-out;
}

.step.active {
  display: block;
}
/* Indikator Step */
.step-indicators {
  margin-bottom: 20px;
}
.step-indicator {
  margin: 0 5px;
  flex: 1 0 auto; 
  padding: 10px 20px;
}
.btn.active {
  background-color: #D77748 !important;
    border-color: #D77748 !important;
    -webkit-tap-highlight-color: transparent;
    -webkit-transform: translateZ(0);
            transform: translateZ(0);
  color: white !important;
}
/* Responsive untuk mobile */
@media (max-width: 768px) {
  .steps {
    overflow-y: auto;
    height: 60vh; /* Sesuaikan tinggi sesuai kebutuhan */
  }

  .step {
    padding-bottom: 20px; /* Memberikan ruang untuk scroll */
  }
}
</style>
@endsection
@section('js_load')
<script src="{{asset('vendor/erayadigital/landingpage.js')}}"></script>
@endsection