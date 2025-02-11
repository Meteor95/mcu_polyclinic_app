@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row default-dashboard">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          @include('komponen.information_user', ['title_card' => "Riwayat Penyakit Keluarga", 'informasi_apa' => "informasi riwayat penyakit keluarga"])
        </div>
        <div class="card-body">
          <div class="row formulir_group">
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
          </div>
            <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
              <button class="btn btn-danger w-100 mt-3 formulir_group_button" id="bersihkan_data_riwayat_penyakit_keluarga"><i class="fa fa-refresh"></i> Bersihkan Data</button>                   
              <button class="btn btn-success w-100 mt-3 formulir_group_button" id="simpan_riwayat_penyakit_keluarga"><i class="fa fa-save"></i> Simpan Data</button>                   
            </div>
            @if(isset($data['dataNavigasi']))
                @include('komponen.navigasi_riwayat_informasi', $data['dataNavigasi'])
            @endif
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
table.dataTable tbody td.focus {
  outline: 1px dotted rgb(255, 153, 0);
  outline-offset: -2px;
}
</style>
@endsection
@section('js_load')
<script src="https://cdn.datatables.net/keytable/2.12.1/js/dataTables.keyTable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('vendor/erayadigital/riwayat/riwayat_penyakit_keluarga.js') }}"></script>
<script>
  let param_nomor_identitas = '{{$data['nomor_identitas']}}'
  let param_nama_peserta = '{{$data['nama_peserta']}}'
</script>
@endsection
