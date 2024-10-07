@extends('templatebody')
@section('konten_utama')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-7"><img class="bg-img-cover bg-center" src="{{asset('mofi/assets/images/login/2.jpg')}}" alt="looginpage"></div>
    <div class="col-xl-5 p-0">
      <div class="login-card login-dark">
        <div>
          <div class="d-flex justify-content-center">
            <a class="logo text-center" href="{{url('')}}">
                <img class="img-fluid for-light mx-auto d-block" src="{{asset('mofi/assets/images/logo/Logo_AMC_Full.png')}}" alt="looginpage">
                <img class="img-fluid for-dark" src="{{asset('mofi/assets/images/logo/Logo_AMC_Full.png')}}" alt="looginpage">
            </a>
          </div>
          <div class="login-main"> 
            <div class="theme-form">
              <h4>Masukan Identitas Terdaftar </h4>
              <p>Ketikan Surel / Nama Pengguna dan Katasandi Terdaftar</p>
              <div class="form-group">
                <label class="col-form-label">Alamat Surel / Nama Pengguna</label>
                <input autocomplete="off" class="form-control" type="text" id="namapengguna" required="" placeholder="hallo@arthamedicalcenter.com">
              </div>
              <div class="form-group">
                <label class="col-form-label">Katasandi</label>
                <div class="form-input position-relative">
                  <input autocomplete="off" class="form-control" type="password" id="katasandi" required="" placeholder="*********">
                  <div class="show-hide"><span class="show"></span></div>
                </div>
              </div>
              <div class="form-group mb-0">
                <div class="checkbox p-0">
                  <input id="checkbox1" type="checkbox">
                  <label class="text-muted" for="checkbox1">Ingat Katasandi</label>
                </div>
                <button class="btn btn-primary btn-block w-100" id="btn_login">Kirim Data</button>
              </div>
              <h6 class="text-muted mt-4 or">Atau Dengan</h6>
              <div class="social mt-4">
                <div class="btn-showcase"><a class="btn btn-light" href="https://www.linkedin.com/login" target="_blank"><i class="txt-linkedin" data-feather="linkedin"></i> LinkedIn </a><a class="btn btn-light" href="https://twitter.com/login?lang=en" target="_blank"><i class="txt-twitter" data-feather="twitter"></i>twitter</a><a class="btn btn-light" href="https://www.facebook.com/" target="_blank"><i class="txt-fb" data-feather="facebook"></i>facebook</a></div>
              </div>
              <p class="mt-4 mb-0 text-center">Tidak memiliki akun AMC ?<a class="ms-2" href="sign-up.html">Buat Akun Sekarang</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('css_load')
@endsection
@section('js_load')
<script src="{{asset('mofi/assets/js/system/login.js')}}"></script>
@endsection