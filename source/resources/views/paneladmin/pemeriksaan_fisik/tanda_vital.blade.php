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
                            <p>Formulir untuk kelengkapan MCU berupa informasi tanda vital dan gizi terbaru dari pasien MCU saat test untuk kelengkapan yang akan digunakan untuk laporan MCU dari peserta.</p>
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
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <h1>Tanda Vital</h1>
                    <table class="table display datatables_tanda_vital" id="datatables_tanda_vital">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanda Vital</th>
                            <th>Nilai</th>
                            <th>Satuan</th>
                            <th>Keterangan</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data['tanda_vital'] as $item)
                        @if($item->jenis_tanda_vital == 'tanda_vital')
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{$item->nama_atribut_tv}}</td>
                            <td><input type="text" class="form-control" placeholder="Tentukan Nilai Paramter" id="nilai_tanda_vital_{{$item->id}}"></td>
                            <td>{{$item->satuan}}</td>
                            <td>
                            <input type="text" class="form-control" placeholder="Jika Ada" id="keterangan_tanda_vital_{{$item->id}}">
                            </td>
                        </tr>
                        @endif
                        @endforeach
                        </tbody>
                    </table>
                    <h1>Tanda Gizi</h1>
                    <table class="table display" id="datatables_tanda_gizi">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanda Gizi</th>
                            <th>Nilai</th>
                            <th>Satuan</th>
                            <th>Keterangan</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data['tanda_vital'] as $item)
                        @if($item->jenis_tanda_vital == 'tanda_gizi')
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{$item->nama_atribut_tv}}</td>
                            <td><input type="text" class="form-control" placeholder="Tentukan Nilai Paramter" id="nilai_tanda_vital_{{$item->id}}"></td>
                            <td>{{$item->satuan}}</td>
                            <td>
                            <input type="text" class="form-control" placeholder="Jika Ada" id="keterangan_tanda_vital_{{$item->id}}">
                            </td>
                        </tr>
                        @endif
                        @endforeach
                        </tbody>
                    </table> 
                    <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                        <button class="btn btn-danger w-100 mt-3" id="bersihkan_tanda_vital"><i class="fa fa-refresh"></i> Bersihkan Data</button>                   
                        <button class="btn btn-success w-100 mt-3" id="simpan_tanda_vital"><i class="fa fa-save"></i> Simpan Data</button>                   
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <h1 class="mb-2 text-center">Daftar Pemeriksaan Tanda Vital</h1>
            <input type="text" class="form-control" id="kotak_pencarian_tanda_vital" placeholder="Cari Informasi Tanda Vital">
            <div class="table-responsive theme-scrollbar">
              <table id="datatables_tanda_vital_list"></table>
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
            <h1>Tanda Vital</h1>
            <table class="table table-bordered" id="datatables_tanda_vital_modal_tanda_vital">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Tanda Vital</th>
                    <th>Nilai</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <h1>Tanda Gizi</h1>
            <table class="table table-bordered" id="datatables_tanda_vital_modal_tanda_gizi">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Tanda Vital</th>
                    <th>Nilai</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
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
<link rel="stylesheet" type="text/css" href="{{ asset('mofi/assets/css/vendors/tagify.css') }}">
<style>
table.dataTable tbody td.focus {
  outline: 1px dotted rgb(255, 153, 0);
  outline-offset: -2px;
}
</style>
@endsection
@section('js_load')
<script src="https://cdn.datatables.net/keytable/2.12.1/js/dataTables.keyTable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('mofi/assets/js/select2/tagify.js') }}"></script>
<script src="{{ asset('vendor/erayadigital/tingkatkesadaran/tandavital.js') }}"></script>
@endsection