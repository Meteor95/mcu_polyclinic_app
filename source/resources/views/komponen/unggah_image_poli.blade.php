
<div class="formulir_group">
<div class="row">
    @if ($unggahan_citra_aktif)
    <div class="col-md-12 mb-2">
      <div class="alert alert-success d-flex align-items-center border-left-light" role="alert">
        <div>
          <i class="fa fa-info-circle"></i>
        </div>
        <span class="txt-light"> Anda dapat menambahkan foto lebih dari 1. Maksimal kapasitas yang diizinkan adalah 20Mb untuk keseluruhan foto.
        </span>
      </div>
    </div>
    @endif
    <div class="col-md-12">
      <h3>Tentukan Dokter Bertugas</h3>
      <select class="form-select" data-choices name="dokter_citra_unggah_poli" id="dokter_citra_unggah_poli">
        @foreach ($data['daftar_dokter'] as $item)
          <option value="{{$item['id']}}">{{$item['nama_pegawai']}}</option>
        @endforeach
      </select>
    </div>
    @if ($unggahan_citra_aktif)
    <div class="col-md-6">
      <h3>Citra Sebelum</h3>
      <input type="file" id="citra_pasien" class="form-control mt-2 mb-2" accept="image/*">
      <div id="cropper-container">
        <div id="citra_proses_crop"><img id="tampilan_citra_unggahan" style="display: none;"></div>
      </div>
    </div>
    <div class="col-md-6">
      <h3>Citra Sesudah Dengan AR 1:1</h3>
      <div class="row">
        <div class="col-md-12">
          <button id="crop-btn" class="btn btn-primary w-100 mt-2 mb-2"> Potong Foto </button>
        </div>
        <div class="col-md-6" style="display: none;">
          <button class="btn btn-primary w-100 mt-2 mb-2" id="lihat_foto_ukuran_asli_hc"> Lihat Foto Asli </button>
        </div>
      </div>
      <div id="preview_citra_pasien_canvas" class="d-flex justify-content-center align-items-center bg-light" >
          <img id="preview_citra_pasien_img" style="display: none;" alt="Preview Citra Pasien" />
      </div>
    </div>
    <div class="col-md-12">
      <button class="btn w-100 btn-primary mt-3" id="tambah_foto_poliklinik"><i class="fa fa-photo"></i> Tambah Foto</button>
    </div>
    @endif
</div>
 @if ($unggahan_citra_aktif)
<div class="row">
  <div class="col-md-12">
    <h3>Hasil Scan {{ucwords(str_replace('_', ' ', $data['title']))}}</h3>
    <div id="preview-list" class="row g-1"></div>
  </div>
</div>
@endif
<div class="row mt-2">
  <div class="col-md-12">
    <h3>Formulir informasi {{ucwords(str_replace('_', ' ', $data['title']))}}</h3>
  </div>
  <div class="col-md-12">
      <label>Judul Citra {{ucwords(str_replace('_', ' ', $data['title']))  }}</label>
      <input type="text" id="judul_citra_unggah_poli" class="form-control" value="Hasil scan citra {{$data['title']}} pasien MCU" placeholder="Hasil scan citra {{$data['title']}} pasien MCU">
  </div>
  <div class="col-md-12">
    <label>Kesimpulan {{ucwords(str_replace('_', ' ', $data['title']))  }}</label>
    <select class="form-control" data-choices name="kesimpulan_citra_unggah_poli" id="kesimpulan_citra_unggah_poli">
      @foreach ($data['kesimpulan'] as $item)
        <option value="{{$item->id}}">{{$item->keterangan_kesimpulan}}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-12">
    <label>Detail Penjelasan {{ucwords(str_replace('_', ' ', $data['title']))  }}</label>
    <select class="form-control" data-choices name="detail_penjelasan_citra_unggah_poli" id="detail_penjelasan_citra_unggah_poli">
      @foreach ($data['detail_kesimpulan'] as $item)
        <option value="{{$item->id}}">{{$item->keterangan}}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-12 mt-1">
    <div id="editor_poliklinik_container">
      <div id="editor_poliklinik"></div>
    </div>
  </div>
  <div class="col-md-12">
    <label>Catatan Kaki {{ucwords(str_replace('_', ' ', $data['title']))  }}</label>
    <textarea class="form-control" id="catatan_kaki_citra_unggah_poli" placeholder="Isikan catatan kaki pada laporan {{$data['title']}} jika memungkinkan">{{$data['catatan_kaki']}}</textarea>
  </div>
</div>
</div>
<div class="row">
  <div class="col-md-6">
    <button class="btn btn-danger w-100 mt-2 mb-2 formulir_group_button" id="bersihkan_formulir_unggah_citra"><i class="fa fa-trash"></i> Bersihkan Formulir </button>
  </div>
  <div class="col-md-6">
    <button class="btn btn-primary w-100 mt-2 mb-2 formulir_group_button" id="simpan_foto_perserta"><i class="fa fa-save"></i> Simpan Data {{ucwords($data['title'])}} </button>
  </div>
</div>
