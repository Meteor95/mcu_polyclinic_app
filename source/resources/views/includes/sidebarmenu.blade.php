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
          @if ($hasAccessBeranda)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="{{ route('admin.beranda') }}">
              <i class="fa-solid fa-house" style="padding-right: 8px;font-size: 18px;color: #fff;"></i>
              <span>Beranda</span>
            </a>
          </li>
          @endif
          @if ($hasAccessKasir)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa-solid fa-cash-register" style="padding-right: 10px;font-size: 18px;color: #fff;"></i>
              <span>Kasir</span>
            </a>
          </li>
          @endif
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
          @if ($hasAccessPendaftaran)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa-solid fa-book-open" style="padding-right: 5px;font-size: 18px;color: #fff;"></i> <span>Pendaftaran</span></a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessPendaftaranDaftarPeserta, 'url' => 'pendaftaran/daftar_peserta', 'label' => 'Daftar Peserta'],
                ['condition' => $hasAccessPendaftaranDaftarPasien, 'url' => 'pendaftaran/daftar_pasien', 'label' => 'Daftar Pasien']
              ] as $menuItem)
                @if ($menuItem['condition'])
                  <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                @endif
              @endforeach
              <li>
                <a class="submenu-title" href="javascript:void(0)">
                  Riwayat Informasi
                  <div class="according-menu">
                    <i class="fa fa-angle-right"></i>
                  </div>
                </a>
                <ul class="nav-sub-childmenu submenu-content">
                  @foreach([
                      ['condition' => $hasAccessPendaftaranFotoPasien, 'url' => 'pendaftaran/foto_pasien', 'label' => 'Foto Data Diri'],
                      ['condition' => $hasAccessPendaftaranLingkunganKerja, 'url' => 'pendaftaran/lingkungan_kerja', 'label' => 'Lingkungan Kerja'],
                      ['condition' => $hasAccessPendaftaranKecelakaanKerja, 'url' => 'pendaftaran/kecelakaan_kerja', 'label' => 'Kecelakaan Kerja'],
                      ['condition' => $hasAccessPendaftaranKebiasaanHidup, 'url' => 'pendaftaran/kebiasaan_hidup', 'label' => 'Kebiasaan Hidup'],
                      ['condition' => $hasAccessPendaftaranPenyakitTerdahulu, 'url' => 'pendaftaran/penyakit_terdahulu', 'label' => 'Penyakit Terdahulu'],
                      ['condition' => $hasAccessPendaftaranPenyakitKeluarga, 'url' => 'pendaftaran/penyakit_keluarga', 'label' => 'Penyakit Keluarga'],
                      ['condition' => $hasAccessPendaftaranImunisasi, 'url' => 'pendaftaran/imunisasi', 'label' => 'Imunisasi']
                  ] as $menuItem)
                      @if ($menuItem['condition'])
                          <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                      @endif
                  @endforeach
              </ul>
              </li>
            </ul>
          </li>
          @endif
          @if ($hasAccessPemeriksaanFisik)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa fa-stethoscope" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Pemeriksaan Fisik</span>
            </a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessTingkatKesadaran, 'url' => 'pemeriksaan_fisik/tingkat_kesadaran', 'label' => 'Tingkat Kesadaran'],
                ['condition' => $hasAccessTandaVital, 'url' => 'pemeriksaan_fisik/tanda_vital', 'label' => 'Tanda Vital'],
                ['condition' => $hasAccessPenglihatan, 'url' => 'pemeriksaan_fisik/penglihatan', 'label' => 'Penglihatan']
              ] as $menuItem)
                @if ($menuItem['condition'])
                  <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                @endif
              @endforeach
              <li>
                <a class="submenu-title" href="javascript:void(0)">
                  Kondisi Fisik
                  <div class="according-menu">
                    <i class="fa fa-angle-right"></i>
                  </div>
                </a>
                <ul class="nav-sub-childmenu submenu-content">
                  @foreach([
                      ['condition' => $hasAccessKondisiFisikKepala, 'url' => 'pemeriksaan_fisik/kondisi_fisik/kepala', 'label' => 'Kepala'],
                      ['condition' => $hasAccessKondisiFisikTelinga, 'url' => 'pemeriksaan_fisik/kondisi_fisik/telinga', 'label' => 'Telinga'],
                      ['condition' => $hasAccessKondisiFisikMata, 'url' => 'pemeriksaan_fisik/kondisi_fisik/mata', 'label' => 'Mata'],
                      ['condition' => $hasAccessKondisiFisikTenggorokan, 'url' => 'pemeriksaan_fisik/kondisi_fisik/tenggorokan', 'label' => 'Tenggorokan'],
                      ['condition' => $hasAccessKondisiFisikMulut, 'url' => 'pemeriksaan_fisik/kondisi_fisik/mulut', 'label' => 'Mulut'],
                      ['condition' => $hasAccessKondisiFisikGigi, 'url' => 'pemeriksaan_fisik/kondisi_fisik/gigi', 'label' => 'Gigi'],
                      ['condition' => $hasAccessKondisiFisikLeher, 'url' => 'pemeriksaan_fisik/kondisi_fisik/leher', 'label' => 'Leher'],
                      ['condition' => $hasAccessKondisiFisikThorax, 'url' => 'pemeriksaan_fisik/kondisi_fisik/thorax', 'label' => 'Thorax'],
                      ['condition' => $hasAccessKondisiFisikAbdomenUrogenital, 'url' => 'pemeriksaan_fisik/kondisi_fisik/abdomen_urogenital', 'label' => 'Abdomen & Urogenital'],
                      ['condition' => $hasAccessKondisiFisikAnorectalGenital, 'url' => 'pemeriksaan_fisik/kondisi_fisik/anorectal_genital', 'label' => 'Anorectal & Genital'],
                      ['condition' => $hasAccessKondisiFisikEkstremitas, 'url' => 'pemeriksaan_fisik/kondisi_fisik/ekstremitas', 'label' => 'Ekstremitas'],
                      ['condition' => $hasAccessKondisiFisikNeurologis, 'url' => 'pemeriksaan_fisik/kondisi_fisik/neurologis', 'label' => 'Neurologis']
                  ] as $menuItem)
                      @if ($menuItem['condition'])
                          <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                      @endif
                  @endforeach
                </ul>
              </li>
            </ul>
          </li>
          @endif
          @if ($hasAccessPoliklinik)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa-solid fa-hospital-user" style="padding-right: 5px;font-size: 18px;color: #fff;"></i>
              <span>Poliklinik</span>
            </a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessSpirometri, 'url' => 'poli/spirometri', 'label' => 'Spirometri'],
                ['condition' => $hasAccessAudiometri, 'url' => 'poli/audiometri', 'label' => 'Audiometri'],
                ['condition' => $hasAccessEkg, 'url' => 'poli/ekg', 'label' => 'EKG'],
                ['condition' => $hasAccessThreadmill, 'url' => 'poli/threadmill', 'label' => 'Threadmill'],
                ['condition' => $hasAccessRonsen, 'url' => 'poli/ronsen', 'label' => 'Ronsen']
              ] as $menuItem)
                <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
              @endforeach
            </ul>
          </li>
          @endif
          <li class="sidebar-main-title">
            <div>
              <h6>LAB & OBAT</h6>
            </div>
          </li>
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa fa-bars" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Paramter</span>
            </a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessTarifLaboratorium, 'url' => 'laboratorium/tarif', 'label' => 'Daftar Tarif'],
                ['condition' => $hasAccessKategoriLaboratorium, 'url' => 'laboratorium/kategori', 'label' => 'Kategori'],
                ['condition' => $hasAccessSatuanLaboratorium, 'url' => 'laboratorium/satuan', 'label' => 'Satuan'],
                ['condition' => $hasAccessRentangKenormalanLaboratorium, 'url' => 'laboratorium/rentang_kenormalan', 'label' => 'Rentang Kenormalan']
              ] as $menuItem)
                @if ($menuItem['condition'])
                  <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                @endif
              @endforeach
            </ul>
          </li>
          @if ($hasAccessTindakanLaboratorium)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa fa-flask" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Tindakan</span>
            </a>
          </li>
          @endif
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
          @if ($hasAccessMasterData)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa fa-book" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Master Data</span>
            </a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessMasterPerusahaan, 'url' => 'masterdata/daftar_perusahaan', 'label' => 'Perusahaan'],
                ['condition' => $hasAccessPaketHarga, 'url' => 'masterdata/daftar_paket_mcu', 'label' => 'Paket Harga'],
                ['condition' => $hasAccessJasaPelayanan, 'url' => 'masterdata/daftar_jasa_pelayanan', 'label' => 'Jasa Pelayanan'],
                ['condition' => $hasAccessDepartemenPeserta, 'url' => 'masterdata/daftar_departemen_peserta', 'label' => 'Departemen Peserta'],
                ['condition' => $hasAccessMemberMcu, 'url' => 'masterdata/daftar_member_mcu', 'label' => 'Member MCU'],
                ['condition' => $hasAccessDaftarBank, 'url' => 'masterdata/daftar_bank', 'label' => 'Daftar Bank']
              ] as $menuItem)
                @if ($menuItem['condition'])
                  <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                @endif
              @endforeach
            </ul>
          </li>
          @endif
          @if ($hasAccessPetugas)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa fa-user-md" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Petugas</span>
            </a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessPenggunaAplikasi || $hasAccessPermission, 'url' => 'admin/pengguna_aplikasi', 'label' => 'Pengguna Aplikasi'],
                ['condition' => $hasAccessHakAkses || $hasAccessPermission, 'url' => 'admin/role', 'label' => 'Hak Akses']
              ] as $menuItem)
                @if ($menuItem['condition'])
                  <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                @endif
              @endforeach
            </ul>
          </li>
          @endif
        </ul>
      </div>
      <div class="right-arrow" id="right-arrow">
        <i data-feather="arrow-right"></i>
      </div>
    </nav>      
  </div>
</div>