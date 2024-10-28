<div class="sidebar-wrapper" id="sidebar-wrapper" data-layout="stroke-svg">
    <div>
      <div class="logo-wrapper">
        <a href="{{ url('/admin/beranda') }}">
          <img class="img-fluid" src="{{ asset('mofi/assets/images/logo/logo_light.png')}}" alt="Logo">
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
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div id="sidebar-menu">
          <ul class="sidebar-links" id="simple-bar">
            <li class="back-btn"><a href="index.html"><img class="img-fluid" src="{{ asset('mofi/assets/images/logo/logo-icon.png')}}" alt=""></a>
              <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
            </li>
            <li class="pin-title sidebar-main-title">
              <div> 
                <h6>Pinned</h6>
              </div>
            </li>
            <li class="sidebar-main-title">
              <div>
                <h6 class="lan-1">General</h6>
              </div>
            </li>
            <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title" href="javascript:void(0)">
                <svg class="stroke-icon">
                  <use href="{{ asset('mofi/assets/svg/icon-sprite.svg#stroke-home')}}"></use>
                </svg>
                <svg class="fill-icon">
                  <use href="{{ asset('mofi/assets/svg/icon-sprite.svg#fill-home')}}"></use>
                </svg><span>Dashboard </span></a>
              <ul class="sidebar-submenu">
                <li><a href="index.html">Default</a></li>
                <li><a href="dashboard-02.html">Project</a></li>
                <li><a href="dashboard-03.html">Ecommerce</a></li>
                <li><a href="dashboard-04.html">Education</a></li>
              </ul>
            </li>
            <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title" href="javascript:void(0)">
                <svg class="stroke-icon">
                  <use href="{{ asset('mofi/assets/svg/icon-sprite.svg#stroke-widget')}}"></use>
                </svg>
                <svg class="fill-icon">
                  <use href="{{ asset('mofi/assets/svg/icon-sprite.svg#fill-widget')}}"></use>
                </svg><span class="lan-6">Widgets</span></a>
              <ul class="sidebar-submenu">
                <li><a href="general-widget.html">General</a></li>
                <li><a href="chart-widget.html">Chart</a></li>
              </ul>
            </li>
            <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title" href="javascript:void(0)">
                <svg class="stroke-icon">
                  <use href="{{ asset('mofi/assets/svg/icon-sprite.svg#stroke-layout')}}"></use>
                </svg>
                <svg class="fill-icon">
                  <use href="{{ asset('mofi/assets/svg/icon-sprite.svg#fill-layout')}}"></use>
                </svg><span class="lan-7">Page layout</span></a >
              <ul class="sidebar-submenu">
                <li><a href="box-layout.html">Boxed</a></li>
                <li><a href="layout-rtl.html">RTL</a></li>
                <li><a href="layout-dark.html">Dark Layout</a></li>
                <li><a href="hide-on-scroll.html">Hide Nav Scroll</a></li>
              </ul>
            </li>
            <li class="sidebar-main-title">
              <div>
                <h6>PENGATURAN</h6>
              </div>
            </li>
            <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title {{ $data['menu_aktif'] === 'petugas' ? 'active' : '' }}" href="javascript:void(0)">
                <svg class="stroke-icon">
                  <use href="{{asset('mofi/assets/svg/icon-sprite.svg#stroke-editors')}}"></use>
                </svg>
                <svg class="fill-icon">
                  <use href="{{ asset('mofi/assets/svg/icon-sprite.svg#fill-editors')}}"></use>
                </svg><span>Petugas</span></a>
              <ul class="sidebar-submenu">
                <li><a class="{{ $data['sub_menu_aktif'] === 'pengguna_aplikasi' ? 'active' : '' }}" href="{{url('/admin/pengguna_aplikasi')}}">Pengguna Aplikasi </a></li>
                <li><a class="{{ $data['sub_menu_aktif'] === 'daftar_hakakses' ? 'active' : '' }}" href="{{url('/admin/role')}}">Hak Akses</a></li>
              </ul>
            </li>
          </ul>
        </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
      </nav>
    </div>
</div>