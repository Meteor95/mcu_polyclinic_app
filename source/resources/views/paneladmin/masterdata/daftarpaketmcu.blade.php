@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
    <div class="card">
        <div class="card-header">
          <h4>Paket MCU Artha Medica Clinic</h4><span>Silahkan cari data paket MCU berdasarkan nama perusahaan yang terdaftar di MCU Artha Medica Clinic guna melakukan pengaturan data paket MCU yang tersedia.</span>
          <button class="mt-2 btn btn-outline-success w-100" id="tambah_paket_mcu_baru" type="button"><i class="fa fa-plus"></i> Formulir Tambah Paket MCU Baru</button>
        </div>
        <div class="card-body">
          <div class="col-md-12">
            <input type="text" class="form-control" id="kotak_pencarian_paket_mcu" placeholder="Cari data berdasarkan nama perusahaan yang terdaftar di MCU Artha Medica Clinic">
            <div class="table">
              <table class="display" id="datatable_paketmcu"></table>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="formulir_tambah_paket_mcu" tabindex="-1" role="dialog" aria-labelledby="formulir_tambah_paket_mcuLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="formulir_tambah_paket_mcu_text">Formulir Tambah Paket MCU Baru</h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <div class="mb-3">
                <label for="kodepaketmcu" class="form-label">Kode Paket MCU</label>
                <input placeholder="Ex: EDS" type="text" class="form-control" id="kodepaketmcu" name="kodepaketmcu" required>
                <div class="invalid-feedback">Masukan kode paket MCU yang valid</div>
                <div class="valid-feedback">Terlihat bagus! Kode paket MCU sudah terisi</div>
            </div>
            <div class="mb-3">
                <label for="namapaketmcu" class="form-label">Nama Paket MCU</label>
                <input placeholder="Ex: Paket MCU Basic" type="text" class="form-control" id="namapaketmcu" name="namapaketmcu" required>
                <div class="invalid-feedback">Masukan nama paket MCU yang valid</div>
                <div class="valid-feedback">Terlihat bagus! Nama paket MCU sudah terisi</div>
            </div>
            <div class="mb-3">
                <label for="hargapaketmcu" class="form-label">Harga Paket MCU</label>
                <input placeholder="Ex: 1.500.000" type="text" class="form-control" id="hargapaketmcu" name="hargapaketmcu" required>
                <div class="invalid-feedback">Masukan harga paket MCU yang valid</div>
                <div class="valid-feedback">Terlihat bagus! Harga paket MCU sudah terisi</div>
            </div>
            <div class="mb-3">
                <label for="keteranganpaketmcu" class="form-label">Keterangan</label>
                <input placeholder="Ex: Paket MCU Basic untuk pemeriksaan dasar" type="text" class="form-control" id="keteranganpaketmcu" name="keteranganpaketmcu" required>
                <div class="invalid-feedback">Masukan keterangan yang valid</div>
                <div class="valid-feedback">Terlihat bagus! Keterangan sudah terisi</div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table id="tabel_pemeriksaan" class="table table-bordered table-padding-sm-no-datatable">
                        <tr>
                            <th>Kategori</th>
                            <th>Nama Tindakan</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                        <tr>
                            <td rowspan="7">Riwayat Data Diri</td>
                            <td>Foto Data Diri</td>
                            <td><input type="checkbox" class="form-check-input" id="foto_data_diri" name="foto_data_diri"></td>
                        </tr>
                        <tr>
                            <td>Lingkungan Kerja</td>
                            <td><input type="checkbox" class="form-check-input" id="lingkungan_kerja" name="lingkungan_kerja"></td>
                        </tr>
                        <tr>
                            <td>Kecelakaan Kerja</td>
                            <td><input type="checkbox" class="form-check-input" id="kecelakaan_kerja" name="kecelakaan_kerja"></td>
                        </tr>
                        <tr>
                            <td>Kebiasaan Hidup</td>
                            <td><input type="checkbox" class="form-check-input" id="kebiasaan_hidup" name="kebiasaan_hidup"></td>
                        </tr>
                        <tr>
                            <td>Penyakit Terdahulu</td>
                            <td><input type="checkbox" class="form-check-input" id="penyakit_terdahulu" name="penyakit_terdahulu"></td>
                        </tr>
                        <tr>
                            <td>Penyakit Keluarga</td>
                            <td><input type="checkbox" class="form-check-input" id="penyakit_keluarga" name="penyakit_keluarga"></td>
                        </tr>
                        <tr>
                            <td>Imunisasi</td>
                            <td><input type="checkbox" class="form-check-input" id="imunisasi" name="imunisasi"></td>
                        </tr>
                        <tr>
                            <td rowspan="14">Pemeriksaan dan Kondisi Fisik</td>
                            <td>Tingkat Kesadaran</td>
                            <td><input type="checkbox" class="form-check-input" id="tingkat_kesadaran" name="tingkat_kesadaran"></td>
                        </tr>
                        <tr>
                            <td>Penglihatan</td>
                            <td><input type="checkbox" class="form-check-input" id="penglihatan" name="penglihatan"></td>
                        </tr>
                        <tr>
                            <td>Kepala</td>
                            <td><input type="checkbox" class="form-check-input" id="kondisi_fisik_kepala" name="kondisi_fisik_kepala"></td>
                        </tr>
                        <tr>
                            <td>Telinga</td>
                            <td><input type="checkbox" class="form-check-input" id="kondisi_fisik_telinga" name="kondisi_fisik_telinga"></td>
                        </tr>
                        <tr>
                            <td>Mata</td>
                            <td><input type="checkbox" class="form-check-input" id="kondisi_fisik_mata" name="kondisi_fisik_mata"></td>
                        </tr>
                        <tr>
                            <td>Tenggorokan</td>
                            <td><input type="checkbox" class="form-check-input" id="kondisi_fisik_tenggorokan" name="kondisi_fisik_tenggorokan"></td>
                        </tr>
                        <tr>
                            <td>Mulut</td>
                            <td><input type="checkbox" class="form-check-input" id="kondisi_fisik_mulut" name="kondisi_fisik_mulut"></td>
                        </tr>
                        <tr>
                            <td>Gigi</td>
                            <td><input type="checkbox" class="form-check-input" id="kondisi_fisik_gigi" name="kondisi_fisik_gigi"></td>
                        </tr>
                        <tr>
                            <td>Leher</td>
                            <td><input type="checkbox" class="form-check-input" id="kondisi_fisik_leher" name="kondisi_fisik_leher"></td>
                        </tr>
                        <tr>
                            <td>Thorax</td>
                            <td><input type="checkbox" class="form-check-input" id="kondisi_fisik_thorax" name="kondisi_fisik_thorax"></td>
                        </tr>
                        <tr>
                            <td>Abdomen & Urogenital</td>
                            <td><input type="checkbox" class="form-check-input" id="kondisi_fisik_abdomen_ugenital" name="kondisi_fisik_abdomen_ugenital"></td>
                        </tr>
                        <tr>
                            <td>Anorectal & Genitalia</td>
                            <td><input type="checkbox" class="form-check-input" id="kondisi_fisik_anorectal_genitalia" name="kondisi_fisik_anorectal_genitalia"></td>
                        </tr>
                        <tr>
                            <td>Ekstremitas</td>
                            <td><input type="checkbox" class="form-check-input" id="kondisi_fisik_ekstremitas" name="kondisi_fisik_ekstremitas"></td>
                        </tr>
                        <tr>
                            <td>Neurologis</td>
                            <td><input type="checkbox" class="form-check-input" id="kondisi_fisik_neurologis" name="kondisi_fisik_neurologis"></td>
                        </tr>
                        <tr>
                            <td rowspan="9">Poliklinik</td>
                            <td>Tanda Vital</td>
                            <td><input type="checkbox" class="form-check-input" id="poliklinik_tanda_vital" name="poliklinik_tanda_vital"></td>
                        </tr>
                        <tr>
                            <td>Spirometri</td>
                            <td><input type="checkbox" class="form-check-input" id="poliklinik_spirometri" name="poliklinik_spirometri"></td>
                        </tr>
                        <tr>
                            <td>Audiometri</td>
                            <td><input type="checkbox" class="form-check-input" id="poliklinik_audiometri" name="poliklinik_audiometri"></td>
                        </tr>
                        <tr>
                            <td>EKG</td>
                            <td><input type="checkbox" class="form-check-input" id="poliklinik_ekg" name="poliklinik_ekg"></td>
                        </tr>
                        <tr>
                            <td>Threadmill</td>
                            <td><input type="checkbox" class="form-check-input" id="poliklinik_threadmill" name="poliklinik_threadmill"></td>
                        </tr>
                        <tr>
                            <td>Rontgen Thorax</td>
                            <td><input type="checkbox" class="form-check-input" id="poliklinik_rontgen_thorax" name="poliklinik_rontgen_thorax"></td>
                        </tr>
                        <tr>
                            <td>Rontgen Lumbosacral</td>
                            <td><input type="checkbox" class="form-check-input" id="poliklinik_rontgen_lumbosacral" name="poliklinik_rontgen_lumbosacral"></td>
                        </tr>
                        <tr>
                            <td>USG Ubdomain</td>
                            <td><input type="checkbox" class="form-check-input" id="poliklinik_usg_ubdomain" name="poliklinik_usg_ubdomain"></td>
                        </tr>
                        <tr>
                            <td>Farmingham Score</td>
                            <td><input type="checkbox" class="form-check-input" id="poliklinik_farmingham_score" name="poliklinik_farmingham_score"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
            <button type="submit" id="simpan_paket_mcu" class="btn btn-primary">Simpan Data</button>
        </div>
        </div>
    </div>
</div>
@endsection
@section('css_load')
<link rel="stylesheet" type="text/css" href="{{asset('mofi/assets/css/vendors/tagify.css')}}">
<style>
.form-check-input {
   font-size: 20px;  
}
.table td {
    cursor: pointer;
}
.table td input[type="checkbox"] {
    display: block;
    margin: 0 auto;
}
</style>
@endsection
@section('js_load')
<script src="{{asset('mofi/assets/js/select2/tagify.js')}}"></script>
<script src="{{asset('mofi/assets/js/select2/tagify.polyfills.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.8.1/autoNumeric.min.js"></script>
<script src="{{asset('vendor/erayadigital/master_data/paketmcu.js')}}"></script>
@endsection