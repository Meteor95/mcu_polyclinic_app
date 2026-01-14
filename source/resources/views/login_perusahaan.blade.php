@extends('templatebody')
@section('konten_utama')
<div class="login-background">
  <div class="login-center">
    <div class="login-card login-dark">
      <div class="text-center mb-3">
        <a href="{{ url('') }}" class="logo">
          <img src="{{ asset('mofi/assets/images/logo/Logo_AMC_Full.png') }}" alt="Logo AMC" class="img-fluid" style="max-width: 250px;">
        </a>
      </div>
      <div class="theme-form">
        <h4 class="text-center mb-2">Masukkan Informasi Yang Diberikan TIM AMC</h4>
        <p class="text-center mb-4">Ketikan Surel / Nama Pengguna dan Katasandi Terdaftar</p>
        <div class="form-group">
          <label>ID Perusahaan</label>
          <input autocomplete="off" value="erayadigitalstudio" class="form-control" type="text" id="namapengguna" placeholder="hallo@arthamedicalcenter.com">
        </div>
        <div class="form-group">
          <label>Katasandi</label>
          <div class="form-input position-relative">
            <input autocomplete="off" value="Salam1jiwa" class="form-control" type="password" id="katasandi" placeholder="*********">
            <div class="show-hide"><span class="show"></span></div>
          </div>
        </div>
        <div class="form-group mb-3">
          <button class="btn btn-primary btn-block w-100" id="btn_login">Kirim Data</button>
        </div>
        <p class="text-center mb-0">Versi Aplikasi : {{ env('APP_VERSION') }}</p>
        <p class="text-center text-muted" id="visitor_id"></p>
      </div>
    </div>
  </div>
</div>
@endsection

@section('css_load')
<style>
/* Fullscreen background */
.login-background {
    background: url('{{ asset("mofi/assets/images/logo/banner_perusahaan.jpg") }}') no-repeat center center;
    background-size: cover;
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Center login card */
.login-center {
    width: 100%;
    max-width: 400px; /* ukuran card */
    padding: 15px;
}

/* Optional: login card styling */
.login-card {
    background: rgba(0,0,0,0.65); /* semi-transparent background */
    padding: 30px;
    border-radius: 12px;
    color: #fff;
}
</style>
@endsection

@section('js_load')
<script src="https://cdn.jsdelivr.net/npm/@fingerprintjs/fingerprintjs@4.5.1/dist/fp.min.js"></script>
<script src="{{ asset('mofi/assets/js/script.js') }}"></script>
<script src="{{ asset('mofi/assets/js/system/login.js') }}?v={{ filemtime(public_path('mofi/assets/js/system/login.js')) }}"></script>
@endsection
