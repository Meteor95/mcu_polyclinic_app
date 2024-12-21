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
                            <h1>Kondisi {{$data['lokasi_fisik']}}, <span id="nama_peserta_temp"></span></h1>
                            <p>Formulir untuk kelengkapan MCU berupa informasi kondisi {{strtolower($data['lokasi_fisik'])}} terbaru dari pasien MCU saat test untuk kelengkapan yang akan digunakan untuk laporan MCU dari peserta.</p>
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
                <div class="table col-sm-12 col-md-12">
                    <table class="table table-bordered" id="datatables_kondisi_fisik">
                        <thead>
                            <tr style="vertical-align: middle;">
                                <th rowspan="2">ID</th>
                                <th rowspan="2" style="display: none;">Kategori Lokasi Fisik</th>
                                <th rowspan="2">Jenis Pemeriksaan</th>
                                <th colspan="2" style="text-align: center;">Status</th>
                                <th rowspan="2">Keterangan</th>
                            </tr>
                            <tr>
                                <th>Ab-Normal</th>
                                <th>Normal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['kondisi_fisik'] as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td style="display: none;">{{ $item->kategori_lokasi_fisik }}</td>
                                    <td>{{ $item->jenis_pemeriksaan }}</td>
                                    <td>
                                        <div class="form-check checkbox checkbox-primary mb-0">
                                            <input class="form-check-input" onclick="cek_ab_normal({{ $item->id }},0)" id="ab_normal_{{ $item->id }}" type="checkbox">
                                            <label class="form-check-label" for="ab_normal_{{ $item->id }}">Ab-Normal</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check checkbox checkbox-primary mb-0">
                                            <input class="form-check-input" onclick="cek_ab_normal({{ $item->id }},1)" id="normal_{{ $item->id }}" type="checkbox">
                                            <label class="form-check-label" for="normal_{{ $item->id }}">Normal</label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="keterangan_{{ $item->id }}" name="keterangan_{{ $item->id }}" value="{{ $item->keterangan }}" placeholder="Berikan keterangan fisik kepala : {{$item->jenis_pemeriksaan}} jika ada">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>                    
                </div>
                <div class="col-sm-12 col-md-12">
                    <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                        <button class="btn btn-danger w-100 mt-3" id="bersihkan_kondisi_fisik"><i class="fa fa-refresh"></i> Bersihkan Data</button>                   
                        <button class="btn btn-success w-100 mt-3" id="simpan_kondisi_fisik"><i class="fa fa-save"></i> Simpan Data</button>                   
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <h1 class="mb-2 text-center">Daftar Pemeriksaan Kondisi {{ucwords($data['lokasi_fisik'])}}</h1>
            <input type="text" class="form-control" id="kotak_pencarian_kondisi_fisik" placeholder="Cari Informasi Kondisi {{ucwords($data['lokasi_fisik']) }}">
            <div class="table-responsive theme-scrollbar">
              <table id="datatables_kondisi_fisik_log"></table>
            </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="modalLihatParameter" tabindex="-1" role="dialog" aria-labelledby="modalLihatParameterLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Nama Pasien : <span id="modal_nama_peserta_parameter"></span><br>Lokasi Fisik : <span id="modal_lokasi_fisik_parameter"></span></h5>
              <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
          </div>
          <div class="modal-body">
            <div class="table-responsive theme-scrollbar">
              <table class="table table-bordered" id="datatables_kondisi_fisik_log_modal">
                <thead>
                    <tr style="vertical-align: middle;">
                        <th rowspan="2">ID</th>
                        <th rowspan="2" style="display: none;">Kategori Lokasi Fisik</th>
                        <th rowspan="2">Jenis Atribut</th>
                        <th colspan="2" style="text-align: center;">Status</th>
                        <th rowspan="2">Keterangan</th>
                    </tr>
                    <tr style="vertical-align: middle;text-align: center;">
                        <th>Ab-Normal</th>
                        <th>Normal</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </div>
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
table.dataTable tbody td.focus {
  outline: 1px dotted rgb(255, 153, 0);
  outline-offset: -2px;
}
</style>
@endsection
@section('js_load')
<script> let lokasi_fisik_let = "{{$data['lokasi_fisik']}}"; </script>
<script src="https://cdn.datatables.net/keytable/2.12.1/js/dataTables.keyTable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('vendor/erayadigital/kondisifisik/kondisi_fisik.js') }}"></script>
@endsection