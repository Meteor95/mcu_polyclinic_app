<div class="header-logo-wrapper col-auto">
    <div class="logo-wrapper"><a href="index.html"><img class="img-fluid" src="{{asset('mofi/assets/images/logo/Logo_AMC_Full.png')}}" alt=""/></a></div>
  </div>
  <div class="col-4 col-xl-4 page-title">
    <h4 class="f-w-700">{{ $data['title'] }}</h4>
    <nav>
      <ol class="breadcrumb justify-content-sm-start align-items-center mb-0">
        <li class="breadcrumb-item"><a href="{{ url('admin/beranda') }}"> <i data-feather="home"> </i></a></li>
        @foreach ($data['breadcrumb'] as $key => $value)
          <li class="breadcrumb-item f-w-400"><a href="{{ $value }}">{{ $key }}</a></li>
        @endforeach
      </ol>
    </nav>
  </div>
  <!-- Page Header Start-->
  <div class="header-wrapper col m-0">
    <div class="row">
      <form class="form-inline search-full col" action="#" method="get">
        <div class="form-group w-100">
          <div class="Typeahead Typeahead--twitterUsers">
            <div class="u-posRelative">
              <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="Search Mofi .." name="q" title="" autofocus>
              <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading...</span></div><i class="close-search" data-feather="x"></i>
            </div>
            <div class="Typeahead-menu"></div>
          </div>
        </div>
      </form>
      <div class="header-logo-wrapper col-auto p-0">
        <div class="logo-wrapper"><img class="img-fluid" src="{{ asset('mofi/assets/images/logo/logo.png')}}" alt=""></div>
        <div class="toggle-sidebar">
          <svg class="stroke-icon sidebar-toggle status_toggle middle">
            <use href="{{ asset('mofi/assets/svg/icon-sprite.svg#toggle-icon')}}"></use>
          </svg>
        </div>
      </div>
      <div class="nav-right col-xxl-8 col-xl-6 col-md-7 col-8 pull-right right-header p-0 ms-auto">
        <ul class="nav-menus">
          <li class="onhover-dropdown">
            <div class="mode" id="mode-toggle">
              <i id="moon-icon" data-feather="moon"></i>
              <i id="sun-icon" data-feather="sun" style="display: none;"></i> 
            </div>
          </li>
          <li>
            <div class="mode" id="mode-toggle">
              <i id="moon-icon" data-feather="moon"></i>
              <i id="sun-icon" data-feather="sun" style="display: none;"></i> 
            </div>
          </li>          
          <li class="profile-nav onhover-dropdown px-0 py-0">
            <div class="d-flex profile-media align-items-center"><img class="img-30" src="{{ asset('mofi/assets/images/dashboard/profile.png')}}" alt="">
              <div class="flex-grow-1"><span>{{ $data['user_details']->nama_pegawai }}</span>
                <p class="mb-0 font-outfit">{{ $data['user_details']->jabatan }} <i class="fa fa-angle-down"></i></p>
              </div>
            </div>
            <ul class="profile-dropdown onhover-show-div">
              <li><a href="{{ route('admin.akun.profile') }}"><i data-feather="user"></i><span>Profile</span></a></li>
              <li><a href="letter-box.html"><i data-feather="mail"></i><span>Inbox</span></a></li>
              <li><a href="task.html"><i data-feather="file-text"></i><span>Taskboard</span></a></li>
              <li><a href="edit-profile.html"><i data-feather="settings"></i><span>Settings</span></a></li>
              <li><a href="{{ url('pintukeluar') }}"><i data-feather="log-out"> </i><span>Keluar</span></a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
</div>