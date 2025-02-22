@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row default-dashboard">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
            @include('komponen.information_user', ['title_card' => "Foto EKG", 'informasi_apa' => "informasi foto EKG"])
        </div>
        <div class="card-body">
             @include('komponen.unggah_image_poli', ['unggahan_citra_aktif' => $data['unggahan_citra_aktif']])
        </div>
        <div class="card-footer">
          <input type="text" class="form-control" id="kotak_pencarian_daftarpasien" placeholder="Cari data berdasarkan nama peserta">
          <div class="table">
            <table class="table display" id="datatables_daftarpeserta_unggah_citra"></table>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="modalLihatFoto" tabindex="-1" role="dialog" aria-labelledby="modalLihatFotoLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalTambahPenggunaLabel">Foto Pasien MCU Artha Medica Clinic</h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
              <img id="foto_lihat" class="rounded img-thumbnail mx-auto d-block">
          </div>
          <div class="modal-footer">
              <h5>Nama Peserta : <span id="nama_peserta_foto"></span></h5>
          </div>
      </div>
  </div>
</div>
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
              <img id="modalImage" src="" alt="Preview Image" class="img-fluid">
          </div>
      </div>
  </div>
</div>
<div class="modal fade" id="modalLihatInformasi" tabindex="-1" role="dialog" aria-labelledby="modalLihatInformasiLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="modalLihatFotoDetailLabel">Informasi Pasien</h5>
          <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
      </div>
      <div class="modal-body">
        <div class="table-responsive theme-scrollbar">
          <table class="table table-bordered" id="table_informasi">
            <thead>
              <tr>
                <th>Paramter</th>
                <th>Nilai / Hasil</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Judul Unggahan</td>
                <td><div id="judul_laporan_informasi"></div></td>
              </tr>
              <tr>
                <td>Kesimpulan</td>
                <td><div id="kesimpulan_informasi"></div></td>
              </tr>
              <tr>
                <td colspan="2">Detail Kesimpulan<br><div id="detail_kesimpulan_informasi"></div></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalLihatFotoDetail" tabindex="-1" role="dialog" aria-labelledby="modalLihatFotoDetailLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalLihatFotoDetailLabel">Foto Pasien</h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <div class="carousel slide" id="carouselExampleCaptions" data-bs-ride="false">
              <div class="carousel-indicators">
                <button class="active" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
              </div>
              <div class="carousel-inner">
                <div class="carousel-item active"><img class="d-block w-100" src="{{ asset('mofi/assets/images/slider/9.jpg')}}" alt="drawing-room">
                  <div class="carousel-caption d-none d-md-block">
                    <h5>The area in the house that is most comfortable.</h5>
                    <p> You can watch folks you wouldn't have in your house amuse you in your living room thanks to the development of television.</p>
                  </div>
                </div>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Sebelumnya</span></button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Selanjutnya</span></button>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection
@section('css_load')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" integrity="sha512-UtLOu9C7NuThQhuXXrGwx9Jb/z9zPQJctuAgNUBK3Z6kkSYT9wJ+2+dh6klS+TDBCV9kNPBbAxbVD+vCcfGPaA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="{{ asset('vendor/erayadigital/style/poliklinik.css') }}">
@endsection
@section('js_load')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js" integrity="sha512-JyCZjCOZoyeQZSd5+YEAcFgz2fowJ1F1hyJOXgtKu4llIa0KneLcidn5bwfutiehUTiOuK87A986BZJMko0eWQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('vendor/erayadigital/poliklinik/unggahan_citra.js') }}"></script>
<script>
  let title_poliklinik = "{{$data['title']}}"
  let jenis_poli = "{{$data['jenis_poli']}}"
</script>
@endsection
