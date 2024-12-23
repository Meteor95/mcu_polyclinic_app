@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row default-dashboard">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
            @include('komponen.information_user', ['title_card' => "Tingkat Kesadaran", 'informasi_apa' => "informasi tingkat kesadaran"])
          </div>
        <div class="card-body">
            <div class="row">
              <div class="col-sm-12 col-md-12">
                <table class="table">
                    <tr>
                        <td>Keadaan Umum</td>
                        <td>
                            <select class="form-select" id="tingkat_kesadaran_keadaan_umum" name="tingkat_kesadaran_keadaan_umum">
                                <option value="">Pilih Kondisi Keadaan Umum</option>
                                @foreach ($data['tingkat_kesadaran'] as $item)
                                    @if ($item->jenis_tingkat_kesadaran == 'keadaan_umum')
                                        <option value="{{ $item->id }}">{{ $item->nama_tingkat_kesadaran }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                        <td>Keterangan</td>
                        <td><textarea class="form-control" id="keterangan_tingkat_kesadaran_keadaan_umum" name="keterangan_tingkat_kesadaran_keadaan_umum"></textarea></td>
                    </tr>
                    <tr>
                        <td>Status Kesadaran</td>
                        <td>
                            <select class="form-select" id="tingkat_kesadaran_status_kesadaran" name="tingkat_kesadaran_status_kesadaran">
                                <option value="">Pilih Status Kesadaran</option>
                                @foreach ($data['tingkat_kesadaran'] as $item)
                                    @if ($item->jenis_tingkat_kesadaran == 'status_kesadaran')
                                        <option 
                                        value="{{ $item->id }}"
                                        data-keterangan="{{ $item->keterangan_tingkat_kesadaran ?? '' }}"
                                        >{{ $item->nama_tingkat_kesadaran }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                        <td>Keterangan</td>
                        <td><textarea class="form-control" id="keterangan_tingkat_kesadaran_status_kesadaran" name="keterangan_tingkat_kesadaran_status_kesadaran"></textarea></td>
                    </tr>
                    <tr>
                        <td>Keluhan</td>
                        <td colspan="3">
                            <textarea class="form-control" id="keterangan_keluhan" placeholder="Ceritakan keluhan pasien disini"></textarea>
                        </td>
                    </tr>
                </table>
                <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
                    <button class="btn btn-danger w-100 mt-3" id="bersihkan_tingkat_kesadaran"><i class="fa fa-refresh"></i> Bersihkan Data</button>                   
                    <button class="btn btn-success w-100 mt-3" id="simpan_tingkat_kesadaran"><i class="fa fa-save"></i> Simpan Data</button>                   
                </div>
              </div>
            </div>
        </div>
        <div class="card-footer">
            <h1 class="mb-2 text-center">Daftar Pemeriksaan Tingkat Kesadaran</h1>
            <input type="text" class="form-control" id="kotak_pencarian_tingkat_kesadaran" placeholder="Cari Informasi Tingkat Kesadaran">
            <div class="table-responsive theme-scrollbar">
              <table id="datatables_tingkat_kesadaran"></table>
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
            <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Paramter</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Keadaan Umum <span id="modal_keadaan_umum_temp"></span></td>
                        <td><span id="modal_keterangan_keadaan_umum_temp"></span></td>
                    </tr>
                    <tr>
                        <td>Status Kesadaran <span id="modal_status_kesadaran_temp"></span></td>
                        <td><span id="modal_keterangan_status_kesadaran_temp"></span></td>
                    </tr>
                    <tr>
                        <td>Keluhan</td>
                        <td><span id="modal_keluhan_temp"></span></td>
                    </tr>
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
<script src="{{ asset('vendor/erayadigital/tingkatkesadaran/tingkatkesadaran.js') }}"></script>
@endsection