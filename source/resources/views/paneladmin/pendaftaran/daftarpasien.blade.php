@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Daftar Pasien</h4><span>Pada daftar pasien ini terdapat pasien yang sudah didaftarkan namun belum mendapatkan jadwal MCU. Informasi pasien akan tetap ada jikalau pasien tersebut belum dianggap selesai transaksi akhir / tutup transaksi. Silahkan lengkapi keperluan MCU berdasarkan nomor identitas yang sudah didaftarkan.</span>
          <a href="{{ route('admin.pendaftaran.formulir_tambah_peserta') }}" class="btn btn-success w-100 mt-2" id="btn_tambahpeserta">Tambah Peserta MCU</a>
        </div>
        <div class="card-body">
          <input type="text" class="form-control" id="kotak_pencarian_daftarpasien" placeholder="Cari data berdasarkan nama peserta">
          <div class="table-responsive theme-scrollbar">
            <table class="display" id="datatables_daftarpasien"></table>
          </div>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="modal modal-lg fade" id="modal_detail_paket_mcu" tabindex="-1" role="dialog" aria-labelledby="modal_detail_paket_mcuLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahPenggunaLabel">Informasi Detail Paket MCU</h5>
                <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
            </div>
            <div class="modal-body">
                <div id="detail_paket_mcu"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('css_load')
<style>
.dtfc-fixed-right {
    background-color: #f6f6f6 !important;
}
.dtfc-fixed-right_header {
    background-color: #ffffff !important;
}
body.dark-only .dtfc-fixed-right_header {
    background-color: #2a3650 !important;
}
</style>
@endsection
@section('js_load')
<script src="https://cdn.datatables.net/fixedcolumns/4.0.2/js/dataTables.fixedColumns.min.js"></script>
<script src="{{ asset('vendor/erayadigital/pendaftaran/pasien.js') }}"></script>
@endsection
