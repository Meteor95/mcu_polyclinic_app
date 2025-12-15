@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Validasi Laporan Tindakan MCU atau Pengobatan Pasien</h4><span>Pada tabel ini adalah pasien yang sudah mendapatkan jadwal MCU atau Pengobatan dan belum melakukan validasi tindakan, silahkan lakukan validasi pada masing masing pasien agar dapat melihat apakah pasien tersebut sudah melakukan tindakan atau belum atau bahkan tindakan yang diterima apakah sesuai paket atau tidak.</span>
        </div>
        <div class="card-body">
            <div class="row g-2 mb-2">
              <!-- Kolom 1 -->
              <div class="col-md-6">
                <input type="text" class="form-control" id="kotak_pencarian_daftarpasien" placeholder="Cari data berdasarkan nama peserta">
              </div>
            
              <!-- Kolom 2 -->
              <div class="col-md-6">
                <select class="form-select" onchange="kategoriChanged(this.value)" id="kategori_antrian" name="kategori_antrian" style="cursor: pointer">
                  <option value="">Semua</option>
                  <option value="selesai">Selesai</option>
                  <option value="proses" selected="">Proses</option>
                  <option value="dibatalkan">Batal</option>
                </select>
              </div>
            </div>

          <div class="table-responsive theme-scrollbar">
            <table class="display" id="datatables_daftarpasien"></table>
          </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
@section('css_load')
@endsection
@section('js_load')
<script src="{{ asset('vendor/erayadigital/laporan/validasi_mcu.js') }}?v={{ filemtime(public_path('vendor/erayadigital/laporan/validasi_mcu.js')) }}"></script>
@endsection