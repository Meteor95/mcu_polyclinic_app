@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row default-dashboard">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <div class="row mt-2">
            <div class="col-sm-12 col-md-12">
              <select class="form-select" id="pencarian_member_mcu" name="pencarian_member_mcu"></select>
            </div>
          </div>
          <div class="row" id="kartu_informasi_peserta">
            <div class="col-xl-7 col-md-6 proorder-xl-1 proorder-md-1">  
                <div class="card profile-greeting p-0">
                    <div class="card-body">
                        <div class="img-overlay">
                            <h1>LKP MCU, <span id="nama_peserta_temp"></span></h1>
                            <p>Formulir untuk kelengkapan MCU berupa informasi foto terbaru dari pasien MCU yang akan digunakan untuk laporan MCU. Foto peserta wajib diisi setiap dia melakukan pemeriksaan MCU baru.</p>
                        </div>
                        <button class="mt-3 btn btn-secondary" id="btnCekDataIni">Cek Data Ini</button>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-md-6 proorder-md-5"> 
                <div class="card">
                    <div class="card-header card-no-border pb-0">
                        <div class="header-top">
                            <h4>Informasi Peserta</h4>
                            <div class="location-menu dropdown">
                                <button class="btn btn-danger" type="button">Dibuat pada : <span id="created_at_temp">{{date('d-m-Y')}}</span></button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body live-meet">
                        <div class="row">
                            <div class="col-4">
                                Nomor Identitas<br>
                                Nama Peserta<br>
                                Jenis Kelamin<br>
                                Nomor Transaksi MCU<br>
                                No. HP Peserta<br>
                                Email Peserta<br>
                                Perusahaan<br>
                                Departemen<br>
                            </div>
                            <div class="col-8">
                                : <span id="nomor_identitas_temp"></span> (<span id="user_id_temp"></span>)<br>
                                : <span id="nama_peserta_temp_1"></span><br>
                                : <span id="jenis_kelamin_temp"></span><br>
                                : <span id="nomor_transaksi_temp"></span> (<span id="id_transaksi_mcu"></span>)<br>
                                : <span id="no_telepon_temp"></span><br>
                                : <span id="email_temp"></span><br>
                                : <span id="tempat_lahir_temp"></span><br>
                                : <span id="status_kawin_temp"></span><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
        <div class="card-body">
            <h1 class="mb-2 text-center">Formulir Riwayat Penyakit Keluarga</h1>
            <table class="table display" id="datatables_riwayat_penyakit_keluarga">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Riwayat Penyakit Keluarga</th>
                  <th>Status</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data['penyakit_keluarga'] as $item)
                <tr>
                  <td>{{$item->id}}</td>
                  <td>{{$item->nama_atribut_pk}}</td>
                  <td>
                    <select id="status_riwayat_penyakit_keluarga_{{$item->id}}" class="form-select">
                      <option value="1">Ya</option>
                      <option value="0" selected>Tidak</option>
                    </select>
                  </td>
                  <td>
                    <input type="text" class="form-control" placeholder="Jika Ada" id="keterangan_riwayat_penyakit_keluarga_{{$item->id}}">
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>     
            <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
              <button class="btn btn-danger w-100 mt-3" id="bersihkan_data_riwayat_penyakit_keluarga"><i class="fa fa-refresh"></i> Bersihkan Data</button>                   
              <button class="btn btn-success w-100 mt-3" id="simpan_riwayat_penyakit_keluarga"><i class="fa fa-save"></i> Simpan Data</button>                   
            </div>
        </div>
        <div class="card-footer">
            <h1 class="mb-2 text-center">Daftar Riwayat Penyakit Keluarga</h1>
            <input type="text" class="form-control" id="kotak_pencarian_daftar_riwayat_penyakit_keluarga" placeholder="Cari Nama Peserta">
            <div class="table-responsive theme-scrollbar">
              <table class="display" id="datatables_daftar_riwayat_penyakit_keluarga"></table>
            </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="modalLihatParameter" tabindex="-1" role="dialog" aria-labelledby="modalLihatParameterLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Nama Pasien : <span id="modal_nama_peserta_parameter"></span></h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <table id="datatables_riwayat_penyakit_keluarga_modal" class="table table-bordered">
              <thead>
                <tr>
                  <th>Riwayat Penyakit Keluarga</th>
                  <th>Status</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
          <div class="modal-footer">
          </div>
      </div>
  </div>
</div>
@endsection
@section('css_load')
<link href="https://cdn.datatables.net/keytable/2.12.1/css/keyTable.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container--default .select2-selection--single .select2-selection__arrow {
    margin-top: 10px;
    margin-right: 10px;
}
.select2-container--open .select2-dropdown--below {
  margin-top: -20px;
  border-top-left-radius:2;
  border-top-right-radius:2;
}
table.dataTable tbody td.focus {
  outline: 1px dotted rgb(255, 153, 0);
  outline-offset: -2px;
}
</style>
@endsection
@section('js_load')
<script src="https://cdn.datatables.net/keytable/2.12.1/js/dataTables.keyTable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('mofi/assets/js/system/riwayat/riwayat_penyakit_keluarga.js') }}"></script>
@endsection
