@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row">
  <div class="col-md-12">
    <div class="card common-hover">
      <div class="card-header border-l-primary border-3">
        <h4>Informasi Dasar Aplikasi</h4>
        <p>Bagian ini menampilkan ringkasan data utama yang menggambarkan aktivitas dan penggunaan aplikasi secara keseluruhan. Informasi yang ditampilkan meliputi jumlah member yang terdaftar, total pasien terdaftar, jumlah pasien yang melakukan pendaftaran secara online, serta statistik transaksi yang sedang dalam proses maupun transaksi yang telah berhasil diselesaikan.</p>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-sm-4">
            <div class="card">
              <div class="card-body student">
                <div class="d-flex gap-2 align-items-end"> 
                  <div class="flex-grow-1">
                    <h2><span id="dashboard_total_berkas">{{ $data['jumlah_member_terdaftar'] }}</span> Orang</h2>
                    <p class="mb-0 text-truncate"> Terdaftar Pada Aplikasi AMC</p>
                    <div class="d-flex student-arrow text-truncate">
                      
                    </div>
                  </div>
                  <div class="flex-shrink-0"><img src="https://admin.pixelstrap.net/mofi/assets/images/dashboard-4/icon/student.png" alt=""></div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-4">
            <div class="card">
              <div class="card-body student">
                <div class="d-flex gap-2 align-items-end"> 
                  <div class="flex-grow-1">
                    <h2><span id="dashboard_total_berkas">{{ $data['jumlah_transaksi'] }}</span> Transaksi</h2>
                    <p class="mb-0 text-truncate"> Di Artha Medica Centre</p>
                    <div class="d-flex student-arrow text-truncate">
                      
                    </div>
                  </div>
                  <div class="flex-shrink-0"><img src="https://admin.pixelstrap.net/mofi/assets/images/dashboard-4/icon/student.png" alt=""></div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-4">
            <div class="card">
              <div class="card-body student">
                <div class="d-flex gap-2 align-items-end"> 
                  <div class="flex-grow-1">
                    <h2><span id="dashboard_total_berkas">{{ $data['jumlah_rekanan'] }}</span> Berkas</h2>
                    <p class="mb-0 text-truncate"> File Tersedia di AMC</p>
                    <div class="d-flex student-arrow text-truncate">
                      
                    </div>
                  </div>
                  <div class="flex-shrink-0"><img src="https://admin.pixelstrap.net/mofi/assets/images/dashboard-4/icon/student.png" alt=""></div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="card">
              <div class="card-body student">
                <div class="d-flex gap-2 align-items-end"> 
                  <div class="flex-grow-1">
                    <h2><span id="dashboard_total_berkas">{{ App\Helpers\GlobalHelper::singkat_angka($data['jumlah_tindakan_selesai']) }}</span></h2>
                    <p class="mb-0 text-truncate">Total Transaksi di Artha Medica Centre </p>
                    <div class="d-flex student-arrow text-truncate">
                      
                    </div>
                  </div>
                  <div class="flex-shrink-0"><img src="https://admin.pixelstrap.net/mofi/assets/images/dashboard-4/icon/student.png" alt=""></div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="card">
              <div class="card-body student">
                <div class="d-flex gap-2 align-items-end"> 
                  <div class="flex-grow-1">
                    <h2><span id="dashboard_total_berkas">{{ App\Helpers\GlobalHelper::singkat_angka($data['jumlah_tindakan_proses']) }}</span></h2>
                    <p class="mb-0 text-truncate">Total Tagihan Transaksi</p>
                    <div class="d-flex student-arrow text-truncate">
                      
                    </div>
                  </div>
                  <div class="flex-shrink-0"><img src="https://admin.pixelstrap.net/mofi/assets/images/dashboard-4/icon/student.png" alt=""></div>
                </div>
              </div>
            </div>
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
<script src="{{ asset('vendor/erayadigital/beranda/beranda.js') }}?v={{ filemtime(public_path('vendor/erayadigital/beranda/beranda.js')) }}"></script>
@endsection