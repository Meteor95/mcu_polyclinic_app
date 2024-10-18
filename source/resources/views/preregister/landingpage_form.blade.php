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
          <div class="sticky-header">
            <header>                       
              <nav class="navbar navbar-b navbar-dark navbar-trans navbar-expand-xl fixed-top nav-padding" id="sidebar-menu"><a class="navbar-brand p-0" href="#"><img class="img-fluid for-light mx-auto d-block" style="width:130px;height:60px" src="{{asset('mofi/assets/images/logo/Logo_AMC_Full.png')}}" alt="looginpage"></a>
                <button class="navbar-toggler navabr_btn-set custom_nav" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDefault" aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation"><span></span><span></span><span></span></button>
                <div class="navbar-collapse justify-content-center collapse hidenav" id="navbarDefault">
                  <ul class="navbar-nav navbar_nav_modify" id="scroll-spy">
                    <li class="nav-item"><a class="nav-link" href="javascript:void(0);" onclick="document.getElementById('home').scrollIntoView({ behavior: 'smooth' });">Beranda</a>
                    <li class="nav-item"><a class="nav-link" href="javascript:void(0);" onclick="document.getElementById('formulir_mcu').scrollIntoView({ behavior: 'smooth' });">Formulir MCU</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="javascript:void(0);" onclick="document.getElementById('frameworks').scrollIntoView({ behavior: 'smooth' });">Alur Pendaftaran</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="javascript:void(0);" onclick="document.getElementById('feature').scrollIntoView({ behavior: 'smooth' });">Info MCU Terkini</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="https://docs.pixelstrap.net/mofi/document/" target="_blank">Website</a></li>
                  </ul>
                </div>
                <div class="buy-btn"><a class="nav-link js-scroll" href="https://themeforest.net/user/pixelstrap/portfolio" target="_blank">Cek Kode Pemesanan</a></div>
              </nav>
            </header>
          </div>
        </div>
        <div class="home-bg" id="home">
          <ul> 
            <li> <img src="{{asset('mofi/assets/images/landing/home/4.png')}}" alt=""></li>
            <li> <img src="{{asset('mofi/assets/images/landing/home/5.png')}}" alt=""></li>
            <li> <img src="{{asset('mofi/assets/images/landing/home/6.png')}}" alt=""></li>
            <li> <img src="{{asset('mofi/assets/images/landing/home/7.png')}}" alt=""></li>
            <li> <img src="{{asset('mofi/assets/images/landing/home/8.png')}}" alt=""></li>
            <li> <img src="{{asset('mofi/assets/images/landing/home/9.png')}}" alt=""></li>
          </ul>
          <div class="row align-items-center justify-content-center">
            <div class="col-lg-6 col-md-7">
              <div class="home-text">
                <div class="main-title">
                  <div class="d-flex align-items-center gap-2">
                    <div class="flex-shrink-0"><img src="{{asset('mofi/assets/images/landing/icon/Rocket.png')}}" alt=""></div>
                    <div class="flex-grow-1">
                      <p class="m-0">Layanan Kesehatan Masyarakat Terpercaya</p>
                    </div>
                  </div>
                </div>
                <h2><span>AMC</span> - <img class="line-text" src="{{asset('mofi/assets/images/landing/home/line.png')}}" alt=""> Artha Medical Center</h2>
                <p>Mau MCU yang cepat, harga bersaing serta dengan ditunjang sistem dan tenaga admin yang berpengalaman, hasil dapat keluar 12 - 24 jam kerja. Silahkan isi formulir dibawah ini untuk mendapatkan <strong>kode pemesanan</strong></p>
                <div class="docutment-button"><a class="btn-amc-orange btn text-white" href="https://docs.pixelstrap.net/mofi/document/" target="_blank"> 
                    <svg>
                      <use href="{{asset('mofi/assets/svg/icon-sprite.svg')}}#fill-layout"></use>
                    </svg>Isi Formulir Peserta MCU</a></div>
              </div>
            </div>
            <div class="col-lg-6 col-md-5">
              <div class="home-screen">
                <div class="screen-1"><img class="img-fluid" src="{{asset('mofi/assets/images/landing/home/1.png')}}" alt=""></div>
                <div class="screen-2"><img class="img-fluid" src="{{asset('mofi/assets/images/landing/home/2.png')}}" alt=""></div>
                <div class="screen-3"><img class="img-fluid" src="{{asset('mofi/assets/images/landing/home/3.png')}}" alt=""></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <section class="section-space app-section overflow-hidden" id="formulir_mcu">
        <div class="container-fluid">
          <div class="title">
            <h5 class="sub-title">AMC - Artha Medical Clinic</h5>
            <h2>Formulir Pengambila Kode Pemesanan MCU</h2>
            <p>Silahkan isi formulir dibawah ini dengan tepat, akurat, benar dan jujur demi mendapatkan informasi MCU yang akurat dan benar. Jangan lupa simpan kode pemesanan untuk diberikan ke ADMIN MCU saat melakukan TEST MCU</p>
          </div>
        </div>
        <div class="container demo-section">
          <div class="row demo-block">
            <div class="col-xl-12">
              <div class="card height-equal">
                <div class="card-header">
                  <h2>INFORM CONSENT<br>SAYA YANG BERTANDA TANGAN DIBAWAH INI MENYATAKAN :</h2>
                  <ol class="text-start">
                    <li>Mengetahui jenis pemeriksaan yang akan dilakukan</li>
                    <li>Bersedia mengikuti pemeriksaan kesehatan (Medical Check Up) tanpa paksaan</li>
                    <li>Mengizinkan Klinik Artha Medical Center menyerahkan hasil MCU kepada perusahaan yang mengirim saya</li>
                  </ol> 
                </div>
                <div class="card-body basic-wizard important-validation">
                  <div class="stepper-horizontal theme-scrollbar" id="stepper1">
                    <div class="stepper-one stepper step editing active">
                      <div class="step-circle"><span>1</span></div>
                      <div class="step-title">Informasi Biodata</div>
                      <div class="step-bar-left"></div>
                      <div class="step-bar-right"></div>
                    </div>
                    <div class="stepper-two step">
                      <div class="step-circle"><span>2</span></div>
                      <div class="step-title">Riwayat Bahaya<br>Lingkungan Kerja</div>
                      <div class="step-bar-left"></div>
                      <div class="step-bar-right"></div>
                    </div>
                    <div class="stepper-three step">
                      <div class="step-circle"><span>3</span></div>
                      <div class="step-title">Riwayat Penyakit<br>Keluarga dan Rutinitas</div>
                      <div class="step-bar-left"></div>
                      <div class="step-bar-right"></div>
                    </div>
                    <div class="stepper-four step">
                      <div class="step-circle"><span>4</span></div>
                      <div class="step-title">Rekap Formulir<br>Pendaftaran</div>
                      <div class="step-bar-left"></div>
                      <div class="step-bar-right"></div>
                    </div>
                    <div class="stepper-five step">
                      <div class="step-circle"><span>5</span></div>
                      <div class="step-title">Pendaftaran Selesai</div>
                      <div class="step-bar-left"></div>
                      <div class="step-bar-right"></div>
                    </div>
                  </div>
                  <div id="msform">
                    <form class="stepper-one row g-3 needs-validation custom-input" novalidate="">
                      <div class="row">
                        <div class="col mt-3 text-start">
                          <div class="mb-3 row">
                            <label class="col-sm-3" for="landing_nama_lengkap">Nama Lengkap</label>
                            <div class="col-sm-9">
                              <input id="landing_nama_lengkap" class="form-control" type="text" placeholder="Ex: Mochamad Aries Setyawan">
                            </div>
                          </div>
                          <div class="mb-3 row">
                            <label for="landing_alamat" class="col-sm-3">Alamat</label>
                            <div class="col-sm-9">
                              <input id="landing_alamat" class="form-control" type="text" placeholder="Ex: Jl. Tarupala Gang 2 No 2">
                            </div>
                          </div>
                          <div class="mb-3 row">
                            <label for="landing_nik" class="col-sm-3">NIK / KTP / SIM</label>
                            <div class="col-sm-9">
                              <input id="landing_nik" class="form-control" type="text" placeholder="Pastikan identitas resmi masih aktiv dan benar">
                            </div>
                          </div>
                          <div class="mb-3 row">
                            <label for="landing_dept" class="col-sm-3">Dept / Bagian</label>
                            <div class="col-sm-9">
                              <input id="landing_dept" class="form-control" type="text" placeholder="Ex: Chef Officer Technologi">
                            </div>
                          </div>
                          <div class="mb-3 row">
                            <label for="landing_no_hp" class="col-sm-3">No Telepon / HP<br><span>(Rekomendasi WA)</span></label>
                            <div class="col-sm-9">
                              <input id="landing_no_hp" class="form-control" type="text" id="nomorwa" placeholder="Pastikan nomor telpon anda aktif">
                            </div>
                          </div>
                          <div class="mb-3 row">
                            <label for="landing_email" class="col-sm-3">E-Mail</label>
                            <div class="col-sm-9">
                              <input id="landing_email" class="form-control" type="text" placeholder="Pastikan alamat surel aktif dan benar">
                            </div>
                          </div>
                          <div class="mb-3 row">
                            <label for="landing_tempat_lahir" class="col-sm-3">Tempat Tanggal Lahir</label>
                            <div class="col-sm-5">
                              <input id="landing_tempat_lahir" class="form-control" type="text" placeholder="Tempat Lahir. Ex: Malang">
                            </div>
                            <div class="col-sm-4">
                              <input id="landing_tanggal_lahir" class="form-control digits" id="tanggal_lahir" type="datetime-local" value="">
                            </div>
                          </div>
                          <div class="mb-3 row">
                            <label class="col-sm-3">Jenis Kelamin</label>
                            <div class="col-sm-9">
                              <div class="card-wrapper border rounded-3 checkbox-checked">
                                <div class="form-check">
                                  <input class="form-check-input" id="jklaki" type="radio" name="jeniskelamin" checked=>
                                  <label class="form-check-label" for="jklaki">Laki-Laki</label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" id="jkperempuan" type="radio" name="jeniskelamin">
                                  <label class="form-check-label" for="jkperempuan">Perempuan</label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" id="jkalien" type="radio" name="jeniskelamin">
                                  <label class="form-check-label" for="jkalien">Alien</label>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="mb-3 row">
                            <label class="col-sm-3">Status Menikah</label>
                            <div class="col-sm-9">
                              <div class="card-wrapper border rounded-3 checkbox-checked">
                                <div class="form-check">
                                  <input class="form-check-input" id="menikah" type="radio" name="statusmenikah" checked=>
                                  <label class="form-check-label" for="menikah">Menikah</label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" id="belummenikah" type="radio" name="statusmenikah">
                                  <label class="form-check-label" for="belummenikah">Belum Menikah</label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" id="dudajanda" type="radio" name="statusmenikah">
                                  <label class="form-check-label" for="dudajanda">Duda / Janda</label>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="mb-3 row">
                            <label class="col-sm-3">Jenis Pekerjaan</label>
                            <div class="col-sm-9">
                              <div class="card-wrapper border rounded-3 checkbox-checked">
                                <div class="form-check">
                                  <input class="form-check-input" id="operator" type="radio" name="jenispekerjaan" checked=>
                                  <label class="form-check-label" for="operator">Operator</label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" id="andministrasi" type="radio" name="jenispekerjaan">
                                  <label class="form-check-label" for="andministrasi">Administrasi</label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" id="manajerial" type="radio" name="jenispekerjaan">
                                  <label class="form-check-label" for="manajerial">Manajerial</label>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="mb-3 row">
                            <label class="col-sm-3">Proses Kerja</label>
                            <div class="col-sm-9">
                              <div class="card-wrapper border rounded-3 checkbox-checked">
                                <div class="form-check">
                                  <input class="form-check-input" id="duduk" type="radio" name="proseskerja" checked=>
                                  <label class="form-check-label" for="duduk">Duduk</label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" id="berdiri" type="radio" name="proseskerja">
                                  <label class="form-check-label" for="berdiri">Berdiri</label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" id="kombinasi" type="radio" name="proseskerja">
                                  <label class="form-check-label" for="kombinasi">Kombinasi</label>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="mb-3 row">
                            <label class="col-sm-3">Sampling Darah</label>
                            <div class="col-sm-4">
                              <div class="form-floating">
                                <input id="landing_sampling_darah" class="form-control digits" id="tanggal_pemgambila_sample" type="datetime-local" value="">
                                <label for="landing_sampling_darah">Waktu Terakhir Pengambilan</label>
                              </div>
                            </div>
                            <div class="col-sm-5">
                              <div class="form-floating">
                                <select class="form-select" id="landing_goldar" aria-label="Goldar">
                                  <option value="NO">Sudah Test Tapi Tidak Bisa Baca</option>
                                  <option value="A+">A+</option>
                                  <option value="A-">A-</option>
                                  <option value="B+">B+</option>
                                  <option value="B-">B-</option>
                                  <option value="AB+">AB+</option>
                                  <option value="AB-">AB-</option>
                                  <option value="O+">O+</option>
                                  <option value="O-">O-</option>
                                </select>
                                <label for="landing_goldar">Pilih Golongan Darah</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-check">
                        </div>
                      </div>
                    </form>
                    <form class="stepper-two row g-3 needs-validation custom-input" novalidate="">
                      <div class="row">
                        <div class="col-md-12 text-start">
                          <label for="landing_rblk_bising" class="form-label">Bising</label>
                          <div class="input-group mb-2"><span class="input-group-text list-light-primary">
                            <input class="form-check-input mt-0" type="checkbox" value="" aria-label=""></span>
                            <input id="landing_rblk_bising"  class="form-control" type="text">
                            <span class="input-group-text"> jam/hari, selama </span>
                            <input class="form-control" type="text" placeholder="">
                            <span class="input-group-text">Tahun</span>
                          </div>
                          <label id="landing_rblk_getaran" class="form-label">Getaran</label>
                          <div class="input-group mb-2"><span class="input-group-text list-light-primary">
                            <input class="form-check-input mt-0" type="checkbox" value="" aria-label=""></span>
                            <input id="landing_rblk_getaran" class="form-control" type="text">
                            <span class="input-group-text"> jam/hari, selama </span>
                            <input class="form-control" type="text" placeholder="">
                            <span class="input-group-text">Tahun</span>
                          </div>
                          <label for="landing_rblk_debu" class="form-label">Debu</label>
                          <div class="input-group mb-2"><span class="input-group-text list-light-primary">
                            <input class="form-check-input mt-0" type="checkbox" value="" aria-label=""></span>
                            <input id="landing_rblk_debu" class="form-control" type="text">
                            <span class="input-group-text"> jam/hari, selama </span>
                            <input class="form-control" type="text" placeholder="">
                            <span class="input-group-text">Tahun</span>
                          </div>
                          <label for="landing_rblk_zat_kimia" class="form-label">Zat Kimia</label>
                          <div class="input-group mb-2"><span class="input-group-text list-light-primary">
                            <input class="form-check-input mt-0" type="checkbox" value="" aria-label=""></span>
                            <input id="landing_rblk_zat_kimia" class="form-control" type="text">
                            <span class="input-group-text"> jam/hari, selama </span>
                            <input class="form-control" type="text" placeholder="">
                            <span class="input-group-text">Tahun</span>
                          </div>
                          <label for="landing_rblk_prod_makanan" class="form-label">Mengelola Prod. Makanan</label>
                          <div class="input-group mb-2"><span class="input-group-text list-light-primary">
                            <input class="form-check-input mt-0" type="checkbox" value="" aria-label=""></span>
                            <input id="landing_rblk_prod_makanan" class="form-control" type="text">
                            <span class="input-group-text"> jam/hari, selama </span>
                            <input class="form-control" type="text" placeholder="">
                            <span class="input-group-text">Tahun</span>
                          </div>
                          <label for="landing_rblk_monitor_komputer" class="form-label">Monitor Komputer</label>
                          <div class="input-group mb-2"><span class="input-group-text list-light-primary">
                            <input class="form-check-input mt-0" type="checkbox" value="" aria-label=""></span>
                            <input id="landing_rblk_monitor_komputer" class="form-control" type="text">
                            <span class="input-group-text"> jam/hari, selama </span>
                            <input class="form-control" type="text" placeholder="">
                            <span class="input-group-text">Tahun</span>
                          </div>
                          <label for="landing_rblk_gerakan_berulang" class="form-label">Gerakan Berulang-ulang</label>
                          <div class="input-group mb-2"><span class="input-group-text list-light-primary">
                            <input class="form-check-input mt-0" type="checkbox" value="" aria-label=""></span>
                            <input id="landing_rblk_gerakan_berulang" class="form-control" type="text">
                            <span class="input-group-text"> jam/hari, selama </span>
                            <input class="form-control" type="text" placeholder="">
                            <span class="input-group-text">Tahun</span>
                          </div>
                          <label for="landing_rblk_mendoro_menarik" class="form-label">Mendorong / Menarik</label>
                          <div class="input-group mb-2"><span class="input-group-text list-light-primary">
                            <input class="form-check-input mt-0" type="checkbox" value="" aria-label=""></span>
                            <input id="landing_rblk_mendorong_menarik" class="form-control" type="text">
                            <span class="input-group-text"> jam/hari, selama </span>
                            <input class="form-control" type="text" placeholder="">
                            <span class="input-group-text">Tahun</span>
                          </div>
                          <label for="landing_rblk_berat_25" class="form-label">Angkat beban tanpa alat seberat 25Kg</label>
                          <div class="input-group mb-2"><span class="input-group-text list-light-primary">
                            <input class="form-check-input mt-0" type="checkbox" value="" aria-label=""></span>
                            <input id="landing_rblk_berat_25" class="form-control" type="text">
                            <span class="input-group-text"> jam/hari, selama </span>
                            <input class="form-control" type="text" placeholder="">
                            <span class="input-group-text">Tahun</span>
                          </div>
                        </div>
                        <div class="col-md-12 text-start">
                          <label for="landing_rblk_riwayat_kecelakaan_kerja" class="form-label">Riwayat Kecelakaan Kerja</label>
                          <textarea id="landing_rblk_riwayat_kecelakaan_kerja" class="form-control" id="keterangan_riwayat_kecelakaan_kerja" placeholder="Berikan keterangan secara detail JIKALAU anda pernah mengalami atau memiliki RIWAYAT KECELAKAN KERJA, jika TIDAK maka anda bisa abaikan" rows="5"></textarea>
                        </div>
                        <div class="col-12">
                          <div class="form-check">
                          </div>
                        </div>
                      </div>
                    </form>
                    <form class="stepper-three row g-3 needs-validation custom-input" novalidate="">
                      <div class="row">
                        <h2>Riwayat Penyakit Keluarga</h2>
                        <div class="col-sm-4 text-start">
                          <div class="card-wrapper rounded-3 checkbox-checked">
                            <input class="checkbox_animated" id="penyakit_jantung" type="checkbox"><span style="cursor:pointer;" onclick="document.getElementById('penyakit_jantung').click()">Penyakit Jantung</span>
                            <label class="d-block" for="chk-ani1"></label>
                            <input class="checkbox_animated" id="penyakit_darah_tinggi" type="checkbox"><span style="cursor:pointer;" onclick="document.getElementById('penyakit_darah_tinggi').click()">Penyakit Darah Tinggi</span>
                            <label class="d-block" for="chk-ani2"></label>
                            <input class="checkbox_animated" id="penyakit_diabetes_melitus" type="checkbox"><span style="cursor:pointer;" onclick="document.getElementById('penyakit_diabetes_melitus').click()">Penyakit Kencing Manis / Diabetes Melitus</span>
                          </div>
                        </div>
                        <div class="col-sm-4 text-start">
                          <div class="card-wrapper rounded-3 checkbox-checked">
                            <input class="checkbox_animated" id="penyakit_jantung" type="checkbox"><span style="cursor:pointer;" onclick="document.getElementById('penyakit_jantung').click()">Penyakit Stroke</span>
                            <label class="d-block" for="chk-ani1"></label>
                            <input class="checkbox_animated" id="penyakit_darah_tinggi" type="checkbox"><span style="cursor:pointer;" onclick="document.getElementById('penyakit_darah_tinggi').click()">Penyakit Paru-Paru Menaun / Asma / TBC</span>
                            <label class="d-block" for="chk-ani2"></label>
                            <input class="checkbox_animated" id="penyakit_diabetes_melitus" type="checkbox"><span style="cursor:pointer;" onclick="document.getElementById('penyakit_diabetes_melitus').click()">Penyakit Kangker Tumor</span>
                          </div>
                        </div>
                        <div class="col-sm-4 text-start">
                          <div class="card-wrapper rounded-3 checkbox-checked">
                            <label class="d-block" for="chk-ani2"></label>
                            <input class="checkbox_animated" id="chk-ani2" type="checkbox">Penyakit Hemofilia
                            <label class="d-block" for="chk-ani2"></label>
                            <input class="checkbox_animated" id="chk-ani2" type="checkbox">Penyakit Thalassemia
                            <label class="d-block" for="chk-ani2"></label>
                            <input class="checkbox_animated" id="chk-ani2" type="checkbox">Penyakit Buta Warna
                          </div>
                        </div>
                        <div class="col-sm-12 text-start">
                          <label for="tags-input" class="form-label">Punya Penyakit Lainnya ? Ketikkan Namanya Pisahkan Dengan Koma Antar Penyakit</label>
                          <div class="tags-input form-control" data-placeholder="Ex: Vertigo, Nyeri Otot" data-splitchars="[,]"></div>
                        </div>
                        <h2>Khusus Wanita</h2>
                        <div class="col mt-3 text-start">
                          <div class="mb-3 row">
                            <label class="col-sm-3">Hadi Pertama dan Haid Terakhir</label>
                            <div class="col-sm-9">
                              <input class="form-control" id="range-date-haid" type="date" placeholder="">
                            </div>
                          </div>
                        </div>
                        <div class="col mt-3 text-start">
                          <div class="mb-3 row">
                            <label class="col-sm-3">Keluarga Berencana (KB)</label>
                            <div class="col-sm-9">
                              <div class="input-group flatpicker-calender">
                                <input class="form-control" type="text">
                              </div>
                            </div>
                          </div>
                        </div>
                        <h2>Formulir Kebiasaan Gaya Hidup</h2>
                        <div class="col-md-12 text-start">
                          <label class="form-label">Olahraga</label>
                          <div class="input-group mb-2"><span class="input-group-text list-light-primary">
                            <input class="form-check-input mt-0" type="checkbox" value="" aria-label=""></span>
                            <input class="form-control" type="text">
                            <span class="input-group-text"> kali dalam 1 Minggu </span>
                          </div>
                        </div>
                        <div class="col-md-12 text-start">
                          <label class="form-label">Merokok</label>
                          <div class="input-group mb-2"><span class="input-group-text list-light-primary">
                            <input class="form-check-input mt-0" type="checkbox" value="" aria-label=""></span>
                            <input class="form-control" type="text">
                            <span class="input-group-text"> batang per hari </span>
                          </div>
                        </div>
                        <div class="col-md-12 text-start">
                          <label class="form-label">Minum Alkohol</label>
                          <div class="input-group mb-2"><span class="input-group-text list-light-primary">
                            <input class="form-check-input mt-0" type="checkbox" value="" aria-label=""></span>
                            <input class="form-control" type="text">
                            <span class="input-group-text"> botol per hari </span>
                          </div>
                        </div>
                        <div class="col-md-12 text-start">
                          <label class="form-label">Minum Kopi</label>
                          <div class="input-group mb-2"><span class="input-group-text list-light-primary">
                            <input class="form-check-input mt-0" type="checkbox" value="" aria-label=""></span>
                            <input class="form-control" type="text">
                            <span class="input-group-text"> gelas per hari </span>
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="form-check">
                          </div>
                        </div>
                      </div>
                    </form>
                    <form class="stepper-four row g-3 needs-validation" novalidate="">
                      <div class="col-12 m-0">
                        <div class="successful-form"><img class="img-fluid" src="{{asset('mofi/assets/images/gif/dashboard-8/successful.gif')}}" alt="successful">
                          <h6>Congratulations </h6>
                          <p>Well done! You have successfully completed. </p>
                        </div>
                      </div>
                    </form>
                    <form class="stepper-five row g-3 needs-validation" novalidate="">
                      <div class="col-12 m-0">
                        <div class="successful-form"><img class="img-fluid" src="{{asset('mofi/assets/images/gif/dashboard-8/successful.gif')}}" alt="successful">
                          <h6>Kode Pemesanan Anda </h6>
                          <p>Silahkan simpan kode pemesanan anda dan informasikan kepada petugas jikalau ingin melanjutkan proses MCU. Masa tenggang 30 Hari. </p>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="wizard-footer d-flex gap-2 justify-content-end">
                    <button class="btn badge-light-primary" id="backbtn" onclick="backStep()">Sebelumnya</button>
                    <button class="btn btn-primary" id="nextbtn" onclick="nextStep()">Selanjutnya</button>
                  </div>
                </div>
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
              <div class="faq-img"> <img class="img-fluid" src="{{asset('mofi/assets/images/landing/mi1.png')}}" alt="faq">
                <div class="faq-bg">  <img class="img-fluid" src="{{asset('mofi/assets/images/landing/faq/1.png')}}" alt=""></div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
@endsection
@section('css_load')
<link rel="stylesheet" type="text/css" href="{{asset('mofi/assets/css/vendors/flatpickr/flatpickr.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('mofi/assets/css/vendors/bt5-tag-input/tags.css')}}">
@endsection
@section('js_load')
<script src="{{asset('mofi/assets/js/flat-pickr/flatpickr.js')}}"></script>
<script src="{{asset('mofi/assets/js/bt5-tag-input/tags.js')}}"></script>
<script src="{{asset('mofi/assets/js/cleave/cleave.min.js')}}"></script>
<script src="{{asset('mofi/assets/js/form-wizard/form-wizard.js')}}"></script>
<script>
(function () {
initTags();
let cleave = new Cleave("#nomorwa", {
    delimiters: ["", "-", "-"],
    blocks: [3, 3, 4, 5],
    numericOnly: true,
    prefix: "+62",
    noImmediatePrefix: true,
});
flatpickr("#range-date-haid", {
    mode: "range",
});
})();

</script>
@endsection