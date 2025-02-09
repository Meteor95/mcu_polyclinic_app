@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row default-dashboard">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          @include('komponen.information_user', ['title_card' => "Bahaya Paparan Kerja", 'informasi_apa' => "informasi bahaya paparan kerja"])
        </div>
        <div class="card-body">
            <h1 class="mb-2 text-center">Formulir Bahaya Riwayat Lingkungan Kerja (Paparan Kerja)</h1>
            <table class="table display" id="datatables_riwayat_lingkungan_kerja">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Paparan Kerja</th>
                  <th>Status</th>
                  <th>Jam / Hari</th>
                  <th>Selama X Tahun</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data['bahaya_paparan_kerja'] as $item)
                <tr>
                  <td>{{$item->id}}</td>
                  <td>{{$item->nama_atribut_lk}}</td>
                  <td>
                    <select id="status_{{$item->id}}" class="form-select">
                      <option value="1">Ya</option>
                      <option value="0" selected>Tidak</option>
                    </select>
                  </td>
                  <td>
                    <input type="text" class="form-control" placeholder="Harus Angka" id="jam_hari_{{$item->id}}">
                  </td>
                  <td>
                    <input type="text" class="form-control" placeholder="Harus Angka" id="selama_tahun_{{$item->id}}">
                  </td>
                  <td>
                    <input type="text" class="form-control" placeholder="Jika Ada" id="keterangan_{{$item->id}}">
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>     
            <div class="d-flex justify-content-between gap-2 background_fixed_right_row">
              <button class="btn btn-danger w-100 mt-3" id="bersihkan_data_riwayat_lingkungan_kerja"><i class="fa fa-refresh"></i> Bersihkan Data</button>                   
              <button class="btn btn-success w-100 mt-3" id="simpan_riwayat_lingkungan_kerja"><i class="fa fa-save"></i> Simpan Data</button>                   
            </div>
            @if (isset($data['dataNavigasi']))
              @include('komponen.navigasi_riwayat_informasi', $data['dataNavigasi'])
            @endif
        </div>
        <div class="card-footer">
            <h1 class="mb-2 text-center">Daftar Bahaya Riwayat Lingkungan Kerja (Paparan Kerja)</h1>
            <input type="text" class="form-control" id="kotak_pencarian_daftar_bahaya_riwayat_lingkungan_kerja" placeholder="Cari Nama Bahaya">
            <div class="table-responsive theme-scrollbar">
              <table class="display" id="datatables_daftar_bahaya_riwayat_lingkungan_kerja"></table>
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
            <table id="datatables_riwayat_lingkungan_kerja_modal" class="table table-bordered">
              <thead>
                <tr>
                  <th>Paparan Kerja</th>
                  <th>Status</th>
                  <th>Jam / Hari</th>
                  <th>Selama X Tahun</th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.8.1/autoNumeric.min.js"></script>
<script src="https://cdn.datatables.net/keytable/2.12.1/js/dataTables.keyTable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('vendor/erayadigital/riwayat/riwayat_lingkungan_kerja.js') }}"></script>
<script>
  let param_nomor_identitas = '{{$data['nomor_identitas']}}'
  let param_nama_peserta = '{{$data['nama_peserta']}}'
</script>
@endsection
