@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4 style="margin-top: 5px;">Formulir Tambah Pemeriksaan Lab</h4>
                <button class="btn btn-primary text-white float-end" id="collapse_formulir">Sembunyikan Formulir</button>
            </div>
        </div>
        <div class="card-body collapse show" id="formulir_tambah_tindakan">
          <form id="formulir_tambah_tindakan_cek">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label for="kode_pemeriksaan_lab" class="form-label">Kode Pemeriksaan Lab</label>
                    <div class="input-group">
                        <input class="form-control" type="text" placeholder="Contoh : LK, TN, AU, dll." aria-label="Kode Tindakan" aria-describedby="button-addon2">
                        <button class="btn btn-success" id="button-addon2" type="button">Generate</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="nama_pemeriksaan_lab" class="form-label">Nama Pemeriksaan Lab</label>
                    <input type="text" class="form-control" id="nama_pemeriksaan_lab" name="nama_pemeriksaan_lab" placeholder="Contoh : Leokosit, Test Napza, Asam Urat, dll." required>
                </div>
                <div class="col-md-6">
                    <label for="kelompok" class="form-label">Kelompok</label>
                    <select class="form-control" id="kelompok" name="kelompok" required>
                        <option value="">Pilih Kelompok</option>
                        <option value="1">Hematologi Rutin</option>
                        <option value="2">Kimia Klinik</option>
                        <option value="3">Imun Serologi</option>
                        <option value="4">Urinalisa</option>
                        <option value="5">Parasitologi</option>
                        <option value="6">Bakteriologi</option>
                        <option value="7">Test Narkoba</option>
                        <option value="8">Hitung Jenis Leukosit</option>
                        <option value="9">Anti HAV</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="satuan" class="form-label">Satuan Pemeriksaan Lab</label>
                    <select class="form-control" id="satuan" name="satuan" required>
                        <option value="">Pilih Satuan</option>
                        <option value="%">%</option>
                        <option value="/ejakulat">/ejakulat</option>
                        <option value="/LPB">/LPB</option>
                        <option value="/LPK">/LPK</option>
                        <option value="/ml">/ml</option>
                        <option value="/ul">/ul</option>
                        <option value="/µl/ml">/µl/ml</option>
                        <option value="µU/mL">µU/mL</option>
                        <option value="10^3/ml">10^3/ml</option>
                        <option value="10^3/uL">10^3/uL</option>
                        <option value="10^6/ul">10^6/ul</option>
                        <option value="cm">cm</option>
                        <option value="detik">detik</option>
                        <option value="fl">fl</option>
                        <option value="g/24 jam">g/24 jam</option>
                        <option value="g/dL">g/dL</option>
                        <option value="gr/dL/24 jam">gr/dL/24 jam</option>
                        <option value="gram">gram</option>
                        <option value="juta/ul">juta/ul</option>
                        <option value="mecIU">mecIU</option>
                        <option value="menit">menit</option>
                        <option value="meQ/l">meQ/l</option>
                        <option value="mg/24Jam">mg/24Jam</option>
                        <option value="mg/dl">mg/dl</option>
                        <option value="mg/L">mg/L</option>
                        <option value="miligram">miligram</option>
                        <option value="mIU/ml">mIU/ml</option>
                        <option value="ml">ml</option>
                        <option value="mL/menit">mL/menit</option>
                        <option value="mlO2/d">mlO2/d</option>
                        <option value="mm/jam">mm/jam</option>
                        <option value="mmHg">mmHg</option>
                        <option value="mmol/L">mmol/L</option>
                        <option value="ng/dl">ng/dl</option>
                        <option value="ng/mL">ng/mL</option>
                        <option value="nmol/L">nmol/L</option>
                        <option value="P mol/L">P mol/L</option>
                        <option value="pg/cell">pg/cell</option>
                        <option value="pg/ml">pg/ml</option>
                        <option value="SH">SH</option>
                        <option value="U.E/dl">U.E/dl</option>
                        <option value="U/L">U/L</option>
                        <option value="ug/dL">ug/dL</option>
                        <option value="ug/mL">ug/mL</option>
                        <option value="x10^3 /ejakulat">x10^3 /ejakulat</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Berikan keterangan untuk pemeriksaan lab ini jikalau ada.">
                </div>
                <div class="col-md-12 mt-2">
                    <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active txt-danger" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home" role="tab" aria-controls="icon-home" aria-selected="true">
                            <i class="icofont icofont-prescription"></i> Jenis Kualitatif
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link txt-danger" id="profile-icon-tabs" data-bs-toggle="tab" href="#profile-icon" role="tab" aria-controls="profile-icon" aria-selected="false">
                            <i class="icofont icofont-prescription"></i> Jenis Kuantitatif
                          </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="icon-tabContent">
                        <div class="tab-pane fade show active" id="icon-home" role="tabpanel" aria-labelledby="icon-home-tab">
                            <div class="row mt-2 g-1">
                                <div class="col-md-6">
                                    <select class="form-control" id="rentang_kenormalan" name="rentang_kenormalan" required>
                                        <option value="">Rentang Kenormalan</option>
                                        <option value="1">Laki-laki 1-5 Tahun</option>
                                        <option value="2">Laki-laki 6-10 Tahun</option>
                                        <option value="3">Laki-laki 11-15 Tahun</option>
                                        <option value="4">Laki-laki 16-20 Tahun</option>
                                        <option value="5">Laki-laki 21-25 Tahun</option>
                                        <option value="6">Laki-laki 26-30 Tahun</option>
                                        <option value="7">Laki-laki 31-35 Tahun</option>
                                        <option value="8">Laki-laki 36-40 Tahun</option>
                                        <option value="9">Laki-laki 41-45 Tahun</option>
                                        <option value="10">Perempuan 1-5 Tahun</option>
                                        <option value="11">Perempuan 6-10 Tahun</option>
                                        <option value="12">Perempuan 11-15 Tahun</option>
                                        <option value="13">Perempuan 16-20 Tahun</option>
                                        <option value="14">Perempuan 21-25 Tahun</option>
                                        <option value="15">Perempuan 26-30 Tahun</option>
                                        <option value="16">Perempuan 31-35 Tahun</option>
                                        <option value="17">Perempuan 36-40 Tahun</option>
                                        <option value="18">Perempuan 41-45 Tahun</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="nilai_kenormalan" name="nilai_kenormalan" placeholder="Keterangan Batas">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="nilai_kenormalan" name="nilai_kenormalan" placeholder="Masukan Batas Atas">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="nilai_kenormalan" name="nilai_kenormalan" placeholder="Antara">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="nilai_kenormalan" name="nilai_kenormalan" placeholder="Masukan Batas Bawah">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile-icon" role="tabpanel" aria-labelledby="profile-icon-tabs">
 
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary mt-2 w-100"><i class="icofont icofont-save"></i> Simpan Informasi Tindakan Cek</button>
                </div>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Daftar Tindakan Tersedia</h4><span>Berikut adalah daftar tindakan yang tersedia untuk pemeriksaan laboratorium. Silahkan buatkan template untuk mempermudah proses transaksi lab pada fitur transaksi lab. Jikalau tindakan ini sudah tertransaksi pada pasien usahakan untuk tidak menghapusnya silahkan lakukan penonaktifkan saja.</span>
        </div>
        <div class="card-body">
            <table id="table_tindakan_lab" class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Kode Pemeriksaan Lab</th>
                  <th>Nama Pemeriksaan Lab</th>
                  <th>Kelompok</th>
                  <th>Satuan</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
            </table>
        </div>
      </div>
    </div>
</div>
@endsection
@section('css_load')
@endsection
@section('js_load')
<script src="{{ asset('vendor/erayadigital/laboratorium/tindakan.js') }}"></script>
@endsection