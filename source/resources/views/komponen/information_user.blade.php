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
                    <h1>{{ $title_card }}, <span id="nama_peserta_temp"></span></h1>
                    <p>Formulir untuk kelengkapan MCU berupa {{ $informasi_apa }} terbaru dari pasien MCU saat test untuk kelengkapan yang akan digunakan untuk laporan MCU dari peserta.</p>
                </div>
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