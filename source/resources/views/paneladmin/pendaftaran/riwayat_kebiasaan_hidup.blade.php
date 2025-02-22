@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row default-dashboard">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          @include('komponen.information_user', ['title_card' => "Kebiasaan Hidup", 'informasi_apa' => "informasi kebiasaan hidup peserta"])
        </div>
        <div class="card-body">
          <div class="row formulir_group">
            <h1 class="mb-2 text-center">Formulir Kebiasaan Hidup</h1>
            <table class="table display" id="datatables_riwayat_kebiasaan_hidup">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Kebiasaan Hidup</th>
                  <th>Status</th>
                  <th>Nilai</th>
                  <th>Satuan</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data['kebiasaan_hidup'] as $item)
                @if ($item->status == 1)
                <tr>
                  <td>{{$item->id}}</td>
                  <td>{{$item->nama_atribut_kb}}</td>
                  <td>
                    <select id="status_{{$item->id}}" class="form-select">
                      <option value="1">Ya</option>
                      <option value="0" selected>Tidak</option>
                    </select>
                  </td>
                  <td>
                    <input type="text" class="form-control" placeholder="Harus Angka" id="nilai_kebiasaan_{{$item->id}}">
                  </td>
                  <td>
                    {{$item->nama_satuan_kb}}
                  </td>
                  <td>
                    <input type="text" class="form-control" placeholder="Jika Ada" id="keterangan_{{$item->id}}">
                  </td>
                </tr>
                @endif
                @endforeach
              </tbody>
            </table> 
            <div id="kebiasaan_hidup_perempuan">
              <h1 class="mb-2 text-center">Khusus Perempuan</h1>  
              <table class="table display" id="datatables_kebiasaan_hidup_perempuan">
                  <thead>
                      <tr>
                        <th>ID</th>
                        <th>Kebiasaan Hidup</th>
                        <th>Status</th>
                        <th>Nilai</th>
                        <th>Satuan</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($data['kebiasaan_hidup'] as $item)
                      @if ($item->status == 2)
                      <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->nama_atribut_kb}}</td>
                        <td>
                          <select id="status_{{$item->id}}" class="form-select">
                            <option value="1">Ya</option>
                            <option value="0" selected>Tidak</option>
                          </select>
                        </td>
                        <td>
                          <input type="text" class="form-control" placeholder="Harus Angka" id="waktu_kebiasaan_perempuan_{{$item->id}}">
                        </td>
                        <td>
                          {{$item->nama_satuan_kb}}
                        </td>
                        <td>
                          <input type="text" class="form-control" placeholder="Jika Ada" id="keterangan_perempuan_{{$item->id}}">
                        </td>
                      </tr>
                      @endif
                      @endforeach
                  </tbody>
              </table>
            </div>   
          </div> 
            <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
              <button class="btn btn-danger w-100 mt-3 formulir_group_button" id="bersihkan_data_riwayat_kebiasaan_hidup"><i class="fa fa-refresh"></i> Bersihkan Data</button>                   
              <button class="btn btn-success w-100 mt-3 formulir_group_button" id="simpan_riwayat_kebiasaan_hidup"><i class="fa fa-save"></i> Simpan Data</button>                   
            </div>
            @if(isset($data['dataNavigasi']))
                @include('komponen.navigasi_riwayat_informasi', $data['dataNavigasi'])
            @endif
        </div>
        <div class="card-footer">
            <h1 class="mb-2 text-center">Daftar Kebiasaan Hidup</h1>
            <input type="text" class="form-control" placeholder="Cari Data" id="cari_data_kebiasaan_hidup">
            <div class="table-responsive theme-scrollbar">
              <table class="display" id="datatables_daftar_kebiasaan_hidup"></table>
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
            <table id="datatables_riwayat_kebiasaan_hidup_modal" class="table table-bordered">
              <thead>
                <tr>
                  <th>Kebiasaan Hidup</th>
                  <th>Status</th>
                  <th>Nilai</th>
                  <th>Satuan</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <table id="datatables_riwayat_kebiasaan_hidup_perempuan_modal" class="table table-bordered">
              <thead>
                <tr>
                  <th>Kebiasaan Hidup</th>
                  <th>Status</th>
                  <th>Waktu</th>
                  <th>Satuan</th>
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
<link rel="stylesheet" type="text/css" href="{{ asset('mofi/assets/css/vendors/flatpickr/flatpickr.min.css') }}">
<style>
table.dataTable tbody td.focus {
  outline: 1px dotted rgb(255, 153, 0);
  outline-offset: -2px;
}
</style>
@endsection
@section('js_load')
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.8.1/autoNumeric.min.js"></script>
<script src="https://cdn.datatables.net/keytable/2.12.1/js/dataTables.keyTable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('mofi/assets/js/flat-pickr/flatpickr.js') }}"></script>
<script src="{{ asset('vendor/erayadigital/riwayat/riwayat_kebiasaan_hidup.js') }}"></script>
<script>
  let param_nomor_identitas = '{{$data['nomor_identitas']}}'
  let param_nama_peserta = '{{$data['nama_peserta']}}'
</script>
@endsection
