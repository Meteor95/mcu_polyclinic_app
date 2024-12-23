@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row default-dashboard">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
            @include('komponen.information_user', ['title_card' => 'Kondisi Fisik '.ucwords($data['lokasi_fisik']), 'informasi_apa' => "informasi kondisi fisik ".strtolower($data['lokasi_fisik'])])
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
<link href="https://cdn.datatables.net/rowgroup/1.5.1/css/rowGroup.dataTables.min.css" rel="stylesheet">
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
<script src="https://cdn.datatables.net/rowgroup/1.5.1/js/dataTables.rowGroup.min.js"></script>
<script src="{{ asset('vendor/erayadigital/kondisifisik/kondisi_fisik.js') }}"></script>
@endsection