@extends('templatebody')
@section('konten_utama')
<div class="tap-top"><i data-feather="chevrons-up"></i></div>
<div class="landing-page">
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
    @if($data['peserta_sudah_ada'] == 0)
    <div class="error-wrapper">
        <div class="container"><img class="img-100" src="{{asset('mofi/assets/images/other-images/sad3.gif')}}" alt="">
          <div class="error-heading">
            <h2 class="headline font-danger">404</h2>
          </div>
          <div class="col-md-8 offset-md-2">
            <p class="sub-content">Mohon maaf. Untuk halaman atau informasi yang anda cari tidak ditemukan pada database kami. Silahkan lakukan konsultasi dengan kami terkait inforamasi yang anda inginkan.</p>
          </div>
          <div><a class="btn btn-danger-gradien btn-lg" href="{{url('/')}}">BACK TO HOME PAGE</a></div>
        </div>
    </div>
    @else
    <div class="container-fluid">
        <div class="row">
            <div class="col-12" style="margin-top:100px">
            <div class="knowledgebase-bg"><img class="bg-img-cover bg-center" src="{{asset('mofi/assets/images/landing/header_formulir.gif')}}" alt="looginpage"></div>
            </div>
        <div>
        <div class="row">
            <div class="col-sm-12 col-xl-12" style="max-width:1320px;margin:0 auto;">
                <div class="card vertical-ribbon-animate">
                    <div class="ribbon-vertical-right-wrapper border border-1 vertical-rp-space height-equal alert-light-light">
                    <div class="ribbon ribbon-bookmark ribbon-vertical-right ribbon-warning">
                        <div><i class="fa fa-book"></i><span>No Antrian Kamu</span></div>
                    </div>
                    <div class="maintenance-heading">
                        <h2 class="headline" id="nomor_antrian_uuid">{{strtoupper($data['uuid'])}}</h2>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-success" disabled><i class="fa fa-whatsapp"></i> Kirim WhatsApp</button>
                        <button class="btn btn-danger" disabled><i class="fa fa-envelope"></i> Kirim Email</button>
                        <button id="salin_ke_clip" class="btn btn-primary"><i class="fa fa-clone"></i> Salin Ke Clip</button>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-12 col-md-6" style="max-width:1320px;margin:0 auto;">
                <div class="card common-hover vertical-ribbon-animate">
                  <div class="card-header border-l-warning border-3">
                    <h4>Selamat Informasi Anda Sudah Terekam Pada Database Kami</h4>
                  </div>
                  <div class="card-body">
                    <ol class="list-group list-group-numbered">
                      <li class="list-group-item txt-danger fw-bold">Simpan informasi perserta sebisa mungkin jangan diberkan kepada orang lain</li>
                      <li class="list-group-item txt-success fw-bold">Silahkan anda datang ke lokasi clinic untuk mendapatkan verifikasi yang dilakukan oleh petugas clinic</li>
                      <li class="list-group-item txt-success fw-bold">Jangan lupa untuk membawa bukti pendaftaran anda serta membaca kartu identias yang anda daftarkan seperti KTP, SIM atau PASPORT</li>
                      <li class="list-group-item txt-success fw-bold">Gunakan pakaian yang pantas dan sesuai dengan kondisi karena akan dilakukan pengambilan FOTO sebagai bukti bawah anda yang melakukan MCU</li>
                    </ol>
                  </div>
                </div>
            </div>
        </div>
    </div>
    @endIf
</div>
@endsection
@section('css_load')
<style>
.vertical-ribbon-animate .ribbon-vertical-right{
    width:255px
}
.maintenance-heading{
    margin:0;
}
.headline {
    text-align: center;
    font-size: 3rem;
    font-weight: 900;
    color: var(--theme-default);
    z-index: 2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
@media (max-width: 768px) {
    .headline {
        font-size: 1rem;
        overflow: visible;
        text-overflow: clip;
        white-space: normal;
    }
}
</style>
@endsection
@section('js_load')
<script>
$("#salin_ke_clip").click(function() {
    const teks = $.trim($("#nomor_antrian_uuid").text());
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(teks).then(() => {
            createToast('Berhasil', 'top-right', 'Nomor Antrian ' + teks + ' berhasil disalin ke clipboard! Silahkan paste dengan aman ke lain tempat seperti aplikasi NOTE', 'success', 5000);
        }).catch(err => {
            console.error("Gagal menyalin teks: ", err);
        });
    } else {
        alert("Browser tidak mendukung Clipboard API.");
    }
});
</script>
@endsection