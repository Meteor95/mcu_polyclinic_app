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
                <div class="table table-responsive theme-scrollbar">
                    <table class="display" id="datatables_gigi">
                        <thead>
                        <tr>
                            <td rowspan="2" style="text-align: center;">Posisi</td>
                            <td colspan="8" style="text-align: center;">Kanan</td>
                            <td colspan="8" style="text-align: center;">Kiri</td>
                        </tr>
                        <tr style="text-align: center;">
                            <td>8</td>
                            <td>7</td>
                            <td>6</td>
                            <td>5</td>
                            <td>4</td>
                            <td>3</td>
                            <td>2</td>
                            <td>1</td>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>5</td>
                            <td>6</td>
                            <td>7</td>
                            <td>8</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr style="text-align: center;">
                            <td>Atas</td>
                            <td><input type="text" class="form-control" id="atas_kanan_8" name="atas_kanan_8"></td>
                            <td><input type="text" class="form-control" id="atas_kanan_7" name="atas_kanan_7"></td>
                            <td><input type="text" class="form-control" id="atas_kanan_6" name="atas_kanan_6"></td>
                            <td><input type="text" class="form-control" id="atas_kanan_5" name="atas_kanan_5"></td>
                            <td><input type="text" class="form-control" id="atas_kanan_4" name="atas_kanan_4"></td>
                            <td><input type="text" class="form-control" id="atas_kanan_3" name="atas_kanan_3"></td>
                            <td><input type="text" class="form-control" id="atas_kanan_2" name="atas_kanan_2"></td>
                            <td><input type="text" class="form-control" id="atas_kanan_1" name="atas_kanan_1"></td>
                            <td><input type="text" class="form-control" id="atas_kiri_1" name="atas_kiri_1"></td>
                            <td><input type="text" class="form-control" id="atas_kiri_2" name="atas_kiri_2"></td>
                            <td><input type="text" class="form-control" id="atas_kiri_3" name="atas_kiri_3"></td>
                            <td><input type="text" class="form-control" id="atas_kiri_4" name="atas_kiri_4"></td>
                            <td><input type="text" class="form-control" id="atas_kiri_5" name="atas_kiri_5"></td>
                            <td><input type="text" class="form-control" id="atas_kiri_6" name="atas_kiri_6"></td>
                            <td><input type="text" class="form-control" id="atas_kiri_7" name="atas_kiri_7"></td>
                            <td><input type="text" class="form-control" id="atas_kiri_8" name="atas_kiri_8"></td>
                        </tr>
                        <tr style="text-align: center;">
                            <td>Bawah</td>
                            <td><input type="text" class="form-control" id="bawah_kanan_8" name="bawah_kanan_8"></td>
                            <td><input type="text" class="form-control" id="bawah_kanan_7" name="bawah_kanan_7"></td>
                            <td><input type="text" class="form-control" id="bawah_kanan_6" name="bawah_kanan_6"></td>
                            <td><input type="text" class="form-control" id="bawah_kanan_5" name="bawah_kanan_5"></td>
                            <td><input type="text" class="form-control" id="bawah_kanan_4" name="bawah_kanan_4"></td>
                            <td><input type="text" class="form-control" id="bawah_kanan_3" name="bawah_kanan_3"></td>
                            <td><input type="text" class="form-control" id="bawah_kanan_2" name="bawah_kanan_2"></td>
                            <td><input type="text" class="form-control" id="bawah_kanan_1" name="bawah_kanan_1"></td>
                            <td><input type="text" class="form-control" id="bawah_kiri_1" name="bawah_kiri_1"></td>
                            <td><input type="text" class="form-control" id="bawah_kiri_2" name="bawah_kiri_2"></td>
                            <td><input type="text" class="form-control" id="bawah_kiri_3" name="bawah_kiri_3"></td>
                            <td><input type="text" class="form-control" id="bawah_kiri_4" name="bawah_kiri_4"></td>
                            <td><input type="text" class="form-control" id="bawah_kiri_5" name="bawah_kiri_5"></td>
                            <td><input type="text" class="form-control" id="bawah_kiri_6" name="bawah_kiri_6"></td>
                            <td><input type="text" class="form-control" id="bawah_kiri_7" name="bawah_kiri_7"></td>
                            <td><input type="text" class="form-control" id="bawah_kiri_8" name="bawah_kiri_8"></td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered">
                        <thead>
                        <tr style="background-color: #2A3650;">
                            <td style="color: white;">Singkatan</td>
                            <td style="color: white;">Keterangan</td>
                            <td style="color: white;">Singkatan</td>
                            <td style="color: white;">Keterangan</td>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>C</td>
                                <td>Carries</td>
                                <td>I</td>
                                <td>Impaksi Gigi</td>
                            </tr>
                            <tr>
                                <td>T</td>
                                <td>Tambal Gigi</td>
                                <td>P</td>
                                <td>Gigi Palsu</td>
                            </tr>
                            <tr>
                                <td>M</td>
                                <td>Missing</td>
                                <td>AB</td>
                                <td>Abrasi</td>
                            </tr>
                            <tr>
                                <td>GR</td>
                                <td>Gangren Radix</td>
                                <td>KG</td>
                                <td>karang Gigi</td>
                            </tr>
                            <tr>
                                <td>GP</td>
                                <td>Gangren Pulpae</td>
                                <td>Plaque</td>
                                <td>Plaque</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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
  <div class="modal-dialog modal-lg">
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
              <table class="table table-bordered" id="datatables_gigi_modal">
                <thead>
                <tr>
                    <td rowspan="2" style="text-align: center;">Posisi</td>
                    <td colspan="8" style="text-align: center;">Kanan</td>
                    <td colspan="8" style="text-align: center;">Kiri</td>
                </tr>
                <tr style="text-align: center;">
                    <td>8</td>
                    <td>7</td>
                    <td>6</td>
                    <td>5</td>
                    <td>4</td>
                    <td>3</td>
                    <td>2</td>
                    <td>1</td>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
                </tr>
                </thead>
                <tbody>
                <tr style="text-align: center;">
                    <td>Atas</td>
                    <td><span id="atas_kanan_8_modal"></span></td>
                    <td><span id="atas_kanan_7_modal"></span></td>
                    <td><span id="atas_kanan_6_modal"></span></td>
                    <td><span id="atas_kanan_5_modal"></span></td>
                    <td><span id="atas_kanan_4_modal"></span></td>
                    <td><span id="atas_kanan_3_modal"></span></td>
                    <td><span id="atas_kanan_2_modal"></span></td>
                    <td><span id="atas_kanan_1_modal"></span></td>
                    <td><span id="atas_kiri_1_modal"></span></td>
                    <td><span id="atas_kiri_2_modal"></span></td>
                    <td><span id="atas_kiri_3_modal"></span></td>
                    <td><span id="atas_kiri_4_modal"></span></td>
                    <td><span id="atas_kiri_5_modal"></span></td>
                    <td><span id="atas_kiri_6_modal"></span></td>
                    <td><span id="atas_kiri_7_modal"></span></td>
                    <td><span id="atas_kiri_8_modal"></span></td>
                </tr>
                <tr style="text-align: center;">
                    <td>Bawah</td>
                    <td><span id="bawah_kanan_8_modal"></span></td>
                    <td><span id="bawah_kanan_7_modal"></span></td>
                    <td><span id="bawah_kanan_6_modal"></span></td>
                    <td><span id="bawah_kanan_5_modal"></span></td>
                    <td><span id="bawah_kanan_4_modal"></span></td>
                    <td><span id="bawah_kanan_3_modal"></span></td>
                    <td><span id="bawah_kanan_2_modal"></span></td>
                    <td><span id="bawah_kanan_1_modal"></span></td>
                    <td><span id="bawah_kiri_1_modal"></span></td>
                    <td><span id="bawah_kiri_2_modal"></span></td>
                    <td><span id="bawah_kiri_3_modal"></span></td>
                    <td><span id="bawah_kiri_4_modal"></span></td>
                    <td><span id="bawah_kiri_5_modal"></span></td>
                    <td><span id="bawah_kiri_6_modal"></span></td>
                    <td><span id="bawah_kiri_7_modal"></span></td>
                    <td><span id="bawah_kiri_8_modal"></span></td>
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
<script src="{{ asset('vendor/erayadigital/kondisifisik/gigi.js') }}"></script>
<script src="{{ asset('vendor/erayadigital/kondisifisik/kondisi_fisik.js') }}"></script>
@endsection