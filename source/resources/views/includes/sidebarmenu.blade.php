<div class="sidebar-wrapper" id="sidebar-wrapper" data-layout="stroke-svg">
    <div>
      <div class="logo-wrapper">
        <a href="{{ url('/admin/beranda') }}">
          <img class="img-fluid" src="{{asset('mofi/assets/images/logo/Logo_AMC_Full.png')}}" alt="Logo MCU Artha Medical Center" style="height: 100%;width: 75%;">
        </a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
        <div class="toggle-sidebar">
          <svg class="stroke-icon sidebar-toggle status_toggle middle">
            <use href="{{ asset('mofi/assets/svg/icon-sprite.svg#toggle-icon')}}"></use>
          </svg>
          <svg class="fill-icon sidebar-toggle status_toggle middle">
            <use href="{{ asset('mofi/assets/svg/icon-sprite.svg#fill-toggle-icon')}}"></use>
          </svg>
        </div>
      </div>
      <div class="logo-icon-wrapper"><a href="index.html"><img class="img-fluid" src="{{ asset('mofi/assets/images/logo/logo-icon.png')}}" alt=""></a></div>
      <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow">
          <i data-feather="arrow-left"></i>
        </div>
        <div id="sidebar-menu">
          <ul class="sidebar-links" id="simple-bar">
            <li class="back-btn">
              <a href="index.html">
                <img class="img-fluid" src="{{ asset('mofi/assets/images/logo/logo-icon.png') }}" alt="">
              </a>
              <div class="mobile-back text-end">
                <span>Back</span>
                <i class="fa fa-angle-right ps-2" aria-hidden="true"></i>
              </div>
            </li>
      
            <!-- Pinned Section -->
            <li class="pin-title sidebar-main-title">
              <div>
                <h6>Pinned</h6>
              </div>
            </li>
            <li class="sidebar-main-title">
              <div>
                <h6>Fitur Aplikasi</h6>
              </div>
            </li>
            <li class="sidebar-list">
              <i class="fa fa-thumb-tack"></i>
              <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                <svg class="stroke-icon">
                  <use href="{{ asset('mofi/assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                </svg>
                <svg class="fill-icon">
                  <use href="{{ asset('mofi/assets/svg/icon-sprite.svg#fill-home') }}"></use>
                </svg>
                <span>Beranda</span>
              </a>
            </li>
            <li class="sidebar-list">
              <i class="fa fa-thumb-tack"></i>
              <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                <i class="fa-solid fa-cash-register" style="padding-right: 10px;font-size: 18px;color: #fff;"></i>
                <span>Kasir</span>
              </a>
            </li>
            <!-- Doctor Section -->
            <li class="sidebar-main-title">
              <div>
                <h6>Area Dokter</h6>
              </div>
            </li>
            <!-- Medical Check Up Section -->
            <li class="sidebar-main-title">
              <div>
                <h6>Medical Check Up</h6>
              </div>
            </li>
            <li class="sidebar-list">
              <i class="fa fa-thumb-tack"></i>
              <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                <i data-feather="book-open" class="stroke-icon"></i> <span>Pendaftaran</span></a>
              <ul class="sidebar-submenu">
                <li><a href="{{ url('pendaftaran/daftar_peserta') }}">Daftar Peserta</a></li>
                <li><a href="{{ url('pendaftaran/daftar_pasien') }}">Daftar Pasien</a></li>
                <li>
                  <a class="submenu-title" href="javascript:void(0)">
                    Riwayat Informasi
                    <div class="according-menu">
                      <i class="fa fa-angle-right"></i>
                    </div>
                  </a>
                  <ul class="nav-sub-childmenu submenu-content">
                    <li><a href="{{ url('pendaftaran/foto_pasien') }}">Foto Data Diri</a></li>
                    <li><a href="{{ url('pendaftaran/lingkungan_kerja') }}">Lingkungan Kerja</a></li>
                    <li><a href="{{ url('pendaftaran/kecelakaan_kerja') }}">Kecelakaan Kerja</a></li>
                    <li><a href="{{ url('pendaftaran/kebiasaan_hidup') }}">Kebiasaan Hidup</a></li>
                    <li><a href="{{ url('pendaftaran/penyakit_terdahulu') }}">Penyakit Terdahulu</a></li>
                    <li><a href="{{ url('pendaftaran/penyakit_keluarga') }}">Penyakit Keluarga</a></li>
                    <li><a href="{{ url('pendaftaran/imunisasi') }}">Imunisasi</a></li>
                  </ul>
                </li>
              </ul>
            </li>
      
            <li class="sidebar-list">
              <i class="fa fa-thumb-tack"></i>
              <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                <i class="fa fa-stethoscope" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
                <span>Pemeriksaan Fisik</span>
              </a>
              <ul class="sidebar-submenu">
                <li><a href="{{ url('pemeriksaan_fisik/tingkat_kesadaran') }}">Tingkat Kesadaran</a></li>
                <li><a href="{{ url('pemeriksaan_fisik/tanda_vital') }}">Tanda Vital</a></li>
                <li><a href="{{ url('pemeriksaan_fisik/penglihatan') }}">Penglihatan</a></li>
                <li>
                  <a class="submenu-title" href="javascript:void(0)">
                    Kondisi Fisik
                    <div class="according-menu">
                      <i class="fa fa-angle-right"></i>
                    </div>
                  </a>
                  <ul class="nav-sub-childmenu submenu-content">
                    <li><a href="{{ url('pemeriksaan_fisik/kondisi_fisik/kepala') }}">Kepala</a></li>
                    <li><a href="{{ url('pemeriksaan_fisik/kondisi_fisik/telinga') }}">Telinga</a></li>
                    <li><a href="{{ url('pemeriksaan_fisik/kondisi_fisik/mata') }}">Mata</a></li>
                    <li><a href="{{ url('pemeriksaan_fisik/kondisi_fisik/tenggorokan') }}">Tenggorokan</a></li>
                    <li><a href="{{ url('pemeriksaan_fisik/kondisi_fisik/mulut') }}">Mulut</a></li>
                    <li><a href="{{ url('pemeriksaan_fisik/kondisi_fisik/gigi') }}">Gigi</a></li>
                    <li><a href="{{ url('pemeriksaan_fisik/kondisi_fisik/leher') }}">Leher</a></li>
                    <li><a href="{{ url('pemeriksaan_fisik/kondisi_fisik/thorax') }}">Thorax</a></li>
                    <li><a href="{{ url('pemeriksaan_fisik/kondisi_fisik/abdomen_urogenital') }}">Abdomen & Urogenital</a></li>
                    <li><a href="{{ url('pemeriksaan_fisik/kondisi_fisik/anorectal_genital') }}">Anorectal & Genital</a></li>
                    <li><a href="{{ url('pemeriksaan_fisik/kondisi_fisik/ekstremitas') }}">Ekstremitas</a></li>
                    <li><a href="{{ url('pemeriksaan_fisik/kondisi_fisik/neurologis') }}">Neurologis</a></li>
                  </ul>
                </li>
              </ul>
            </li>
            <li class="sidebar-list">
              <i class="fa fa-thumb-tack"></i>
              <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                <i class="fa-solid fa-hospital-user" style="padding-right: 5px;font-size: 18px;color: #fff;"></i>
                <span>Poliklinik</span>
              </a>
              <ul class="sidebar-submenu">
                <li><a href="{{ url('poliklinik/spirometri') }}">Spirometri</a></li>
                <li><a href="{{ url('poliklinik/audiometri') }}">Audiometri</a></li>
                <li><a href="{{ url('poliklinik/ekg') }}">EKG</a></li>
                <li><a href="{{ url('poliklinik/threadmill') }}">Threadmill</a></li>
                <li><a href="{{ url('poliklinik/ronsen') }}">Ronsen</a></li>
              </ul>
            </li>
            <li class="sidebar-main-title">
              <div>
                <h6>LABORATORIUM</h6>
              </div>
            </li>
            <li class="sidebar-list">
              <i class="fa fa-thumb-tack"></i>
              <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                <i class="fa fa-bars" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
                <span>Paramter</span>
              </a>
              <ul class="sidebar-submenu">
                <li><a href="{{ route('admin.laboratorium.kategori') }}">Kategori</a></li>
                <li><a href="{{ route('admin.laboratorium.satuan') }}">Satuan</a></li>
                <li><a href="{{ route('admin.laboratorium.template') }}">Template</a></li>
                <li><a href="{{ route('admin.laboratorium.rentang_kenormalan') }}">Rentang Kenormalan</a></li>
              </ul>
            </li>
            <li class="sidebar-list">
              <i class="fa fa-thumb-tack"></i>
              <a class="sidebar-link sidebar-title" href="{{ route('admin.laboratorium.tindakan') }}">
                <i class="fa fa-flask" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
                <span>Tindakan</span>
              </a>
            </li>
            <li class="sidebar-main-title">
              <div>
                <h6>LAPORAN</h6>
              </div>
            </li>
            <li class="sidebar-list">
              <i class="fa fa-thumb-tack"></i>
              <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                <i class="fa fa-file-pdf-o" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
                <span>Validasi MCU</span>
              </a>
            </li>
            <!-- Pengaturan Section -->
            <li class="sidebar-main-title">
              <div>
                <h6>PENGATURAN</h6>
              </div>
            </li>
            <li class="sidebar-list">
              <i class="fa fa-thumb-tack"></i>
              <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                <i class="fa fa-book" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
                <span>Master Data</span>
              </a>
              <ul class="sidebar-submenu">
                <li><a href="{{ url('masterdata/daftar_perusahaan') }}">Perusahaan</a></li>
                <li><a href="{{ url('masterdata/daftar_paket_mcu') }}">Paket Harga</a></li>
                <li><a href="{{ url('masterdata/daftar_jasa_pelayanan') }}">Jasa Pelayanan</a></li>
                <li><a href="{{ url('masterdata/daftar_departemen_peserta') }}">Departemen Peserta</a></li>
                <li><a href="{{ url('masterdata/daftar_member_mcu') }}">Member MCU</a></li>
                <li><a href="{{ url('masterdata/daftar_bank') }}">Daftar Bank</a></li>
              </ul>
            </li>
            <li class="sidebar-list">
              <i class="fa fa-thumb-tack"></i>
              <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                <i class="fa fa-user-md" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
                <span>Petugas</span>
              </a>
              <ul class="sidebar-submenu">
                <li><a href="{{ url('/admin/pengguna_aplikasi') }}">Pengguna Aplikasi</a></li>
                <li><a href="{{ url('/admin/role') }}">Hak Akses</a></li>
              </ul>
            </li>
          </ul>
        </div>
        <div class="right-arrow" id="right-arrow">
          <i data-feather="arrow-right"></i>
        </div>
      </nav>      
    </div>
</div>