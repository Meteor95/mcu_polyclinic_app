@extends('paneladmin.templateadmin')
@section('konten_utama_admin')
<div class="row default-dashboard">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4>Formulir Pendaftaran Peserta MCU</h4><span>Silahkan isikan informasi yang valid agar dapat melakukan pendaftaran peserta MCU. Jika ada informasi yang tidak valid maka petugas dapat melakukan koreksi sebelum data benar-benar disimpan ke dalam sistem MCU Arta Medica.</span>
        </div>
        <div class="card-body">
            @if (isset($data['peserta']))
            <input type="hidden" id="type_data_peserta" value="1">
            <div class="row" id="kartu_informasi_peserta">
                <div class="col-xl-7 col-md-6 proorder-xl-1 proorder-md-1">  
                    <div class="card profile-greeting p-0">
                        <div class="card-body">
                            <div class="img-overlay">
                                <h1>Good day, {{ $data['peserta']->nama_peserta }}</h1>
                                <p>Jadikan Peserta ini sebagai keluarga MCU Arta Medica Clinic! Silahkan isi formulir pendaftaran peserta MCU dengan data yang valid.</p>
                                <button class="btn" id="btnIsiFormulirPakaiDataIni">Isi Formulir Pakai Data Ini</button>
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
                                    <button class="btn btn-danger" type="button">Dibuat pada : {{ $data['peserta']->created_at->format('d-m-Y') }}</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body live-meet">
                            <div class="row">
                                <div class="col-4">
                                    Nomor Identitas<br>
                                    Nama Peserta<br>
                                    Jenis Kelamin<br>
                                    Alamat Peserta<br>
                                    No. HP Peserta<br>
                                    Email Peserta<br>
                                    Tempat Tangga Lahir<br>
                                    Status Perkawinan<br>
                                </div>
                                <div class="col-8">
                                    : <span id="nomor_identitas_temp">{{ $data['peserta']->nomor_identitas }}</span><br>
                                    : <span id="nama_peserta_temp">{{ $data['peserta']->nama_peserta }}</span><br>
                                    : <span id="jenis_kelamin_temp">{{ $data['peserta']->jenis_kelamin }}</span><br>
                                    : <span id="alamat_temp">{{ $data['peserta']->alamat }}</span><br>
                                    : <span id="no_telepon_temp">{{ $data['peserta']->no_telepon }}</span><br>
                                    : <span id="email_temp">{{ $data['peserta']->email }}</span><br>
                                    : <span>{{ $data['peserta']->tempat_lahir." ".\Carbon\Carbon::parse($data['peserta']->tanggal_lahir)->format('d-m-Y') }} ( {{ $data['peserta']->umur }} Tahun)</span><br>
                                    : <span>{{ $data['peserta']->status_kawin }}</span><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <input type="hidden" id="type_data_peserta" value="0">
            <div class="row" id="kartu_informasi_peserta">
                <div class="col-md-12">
                    <select class="form-select" id="pencarian_member_mcu" name="pencarian_member_mcu"></select>
                </div>
            </div>
            @endif
            <form id="formulir_pendaftaran_peserta">
            <div class="row">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nomor_identitas" class="form-label">Nomor Identitas (KTP /SIM / Paspor)</label>
                            <input placeholder="Ex: 3602041211870001" type="text" class="form-control" id="nomor_identitas" name="nomor_identitas" required>
                            <div class="invalid-feedback">Masukan nomor identitas yang valid</div>
                            <div class="valid-feedback">Terlihat bagus! Nomor identitas sudah terisi</div>
                        </div>
                        <div class="mb-3">
                            <label for="nama_peserta" class="form-label">Nama Peserta</label>
                            <input placeholder="Ex: John Doe" type="text" class="form-control" id="nama_peserta" name="nama_peserta" required>
                            <div class="invalid-feedback">Masukan nama peserta yang valid</div>
                            <div class="valid-feedback">Terlihat bagus! Nama peserta sudah terisi</div>
                        </div>
                        <div class="mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                            <input placeholder="Ex: Jakarta" type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                            <div class="invalid-feedback">Masukan tempat lahir yang valid</div>
                            <div class="valid-feedback">Terlihat bagus! Tempat lahir sudah terisi</div>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_lahir_peserta" class="form-label">Tanggal Lahir</label>
                            <input type="text" class="form-control" id="tanggal_lahir_peserta" name="tanggal_lahir_peserta" placeholder="dd-mm-yyyy" required>
                            <div class="invalid-feedback">Masukan tanggal lahir yang valid</div>
                            <div class="valid-feedback">Terlihat bagus! Tanggal lahir sudah terisi</div>
                        </div>
                        <div class="mb-3">
                            <label for="tipe_identitas" class="form-label">Tipe Identitas</label>
                            <select class="form-select" id="tipe_identitas" name="tipe_identitas" required>
                                <option value="KTP">KTP</option>
                                <option value="SIM">SIM</option>
                                <option value="Paspor">Paspor</option>
                                <option value="Visa">Visa</option>
                            </select>
                            <div class="invalid-feedback">Pilih tipe identitas</div>
                            <div class="valid-feedback">Terlihat bagus! Tipe identitas sudah dipilih</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="Laki-Laki">Laki-Laki</option>
                                <option value="Perempuan">Perempuan</option>
                                <option value="Alien">Alien</option>
                            </select>
                            <div class="invalid-feedback">Pilih jenis kelamin</div>
                            <div class="valid-feedback">Terlihat bagus! Jenis kelamin sudah dipilih</div>
                        </div>
                        <div class="mb-3">
                            <label for="status_kawin" class="form-label">Status Perkawinan</label>
                            <select class="form-select" id="status_kawin" name="status_kawin" required>
                                <option value="Belum Menikah">Belum Menikah</option>
                                <option value="Menikah">Menikah</option>
                                <option value="Cerai Hidup">Cerai Hidup</option>
                                <option value="Cerai Mati">Cerai Mati</option>
                            </select>
                            <div class="invalid-feedback">Pilih status perkawinan</div>
                            <div class="valid-feedback">Terlihat bagus! Status perkawinan sudah dipilih</div>
                        </div>
                        <div class="mb-3">
                            <label for="no_telepon" class="form-label">Nomor Telepon (Rekomendasi Whatsapp)</label>
                            <input placeholder="Ex: 081234567890" type="tel" class="form-control" id="no_telepon" name="no_telepon" required>
                            <div class="invalid-feedback">Masukan nomor telepon yang valid</div>
                            <div class="valid-feedback">Terlihat bagus! Nomor telepon sudah terisi</div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat surel</label>
                            <input placeholder="Ex: aries@erayadigital.co.id" type="text" class="form-control" id="email" name="email">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Melak Ulu, Kec. Melak, Kabupaten Kutai Barat, Kalimantan Timur 75775" required></textarea>
                            <div class="invalid-feedback">Masukan alamat yang valid</div>
                            <div class="valid-feedback">Terlihat bagus! Alamat sudah terisi</div>
                        </div>
                    </div>
                </div>
                <div class="card-body main-divider">
                    <div class="divider-body divider-body-1 divider-primary"> 
                        <div class="divider-p-primary">
                            <i class="fa fa-modx me-2 txt-primary f-20"></i>
                            <span class="txt-primary">Formulir Transaksi Paket MCU</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="text-center text-muted">Nomor Transaksi MCU akan dibuat ketika informasi transaksi sudah disimpan ke dalam sistem MCU Arta Medica. Jadi isi formulir transaksi MCU dengan benar!</div>
                        <div id="akses_poli_dipilih"></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tanggal_pendaftaran" class="form-label mt-2">Waktu Transaksi Pendaftaran</label>
                    </div>
                    <div class="col-md-6">
                        <input class="form-control" id="tanggal_pendaftaran" type="text" placeholder="dd-mm-yyyy">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="jenis_transaksi_pendaftaran" class="form-label mt-2">Jenis Layanan Pendaftaran</label>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select" id="jenis_transaksi_pendaftaran" name="jenis_transaksi_pendaftaran" required>
                            <option value="MCU">MCU</option>
                            <option value="Follow_Up">Follow Up</option>
                            <option value="Berobat">Berobat</option>
                            <option value="MCU-Threadmill">MCU-Threadmill</option>
                            <option value="Threadmill">Threadmill</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="no_telepon" class="form-label mt-3">Pilih Perusahaan</label>
                    </div>
                    <div class="col-md-6">
                        <div id="pilih_perusahaan"><select class="form-select" id="select2_perusahaan" name="select2_perusahaan" required></select></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="no_telepon" class="form-label mt-3">Pilih Departemen</label>
                    </div>
                    <div class="col-md-6">
                        <div id="pilih_departemen"><select class="form-select" id="select2_departemen" name="select2_departemen" required></select></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="no_telepon" class="form-label mt-3">Tentukan Paket Layanan</label>
                    </div>
                    <div class="col-md-6">
                    <div id="pilih_paket_mcu"><select class="form-select" id="select2_paket_mcu" name="select2_paket_mcu" required></select></div>
                    </div>
                </div>
                <div class="row mb-3">
                <div class="col-md-6">
                        <label for="no_telepon" class="form-label">Tentukan Proses Kerja</label>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check-size">
                            <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                              <input class="form-check-input" name="proses_kerja" id="duduk" value="Duduk" type="checkbox">
                              <label class="form-check-label" for="duduk">Duduk</label>
                            </div>
                            <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                              <input class="form-check-input" name="proses_kerja" id="berdiri" value="Berdiri" type="checkbox">
                              <label class="form-check-label" for="berdiri">Berdiri</label>
                            </div>
                            <div class="form-check form-check-inline checkbox checkbox-dark mb-0">
                              <input checked class="form-check-input" name="proses_kerja" id="kombinasi" value="Kombinasi" type="checkbox">
                              <label class="form-check-label" for="kombinasi">Kombinasi</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <button id="btnKonfirmasiPendaftaran" class="btn btn-success w-100">Konfirmasi Pendaftaran</button>
                </div>
            </div>
            </form>
        </div>
      </div>
    </div>
</div>
<div class="modal modal-xl fade" id="modalKonfimasiPendaftaran" tabindex="-1" role="dialog" aria-labelledby="modalKonfimasiPendaftaranLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahPenggunaLabel">Konfirmasi Pembayaran Peserta MCU</h5>
                <i class="fa fa-times" data-bs-dismiss="modal" style="cursor: pointer;"></i>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-start" style="font-family: 'DS-Digital', sans-serif; font-size: 5vw; color: red;">
                                <strong>IDR</strong>
                            </span>
                            <input type="text"  value="0" class="form-control text-end" id="nominal_bayar_konfirmasi" name="nominal_bayar_konfirmasi" placeholder="0.00" readonly>
                        </div>
                        <div id="akses_poli_dipilih_konfirmasi"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <h4 class="text-start mt-1" for="surat_pengantar">Unggah surat pengantar jika ada</h4>
                    </div>
                    <div class="col-md-8 mb-2">
                        <input type="file" class="form-control" id="surat_pengantar" name="surat_pengantar">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <h4 class="text-start mt-1">Tipe Pembayaran</h4>
                    </div>
                    <div class="col-md-8">
                        <div class="mega-inline">
                            <div class="row">
                                <div class="col-sm-6">
                                <div id="card-hutang" class="card">
                                    <div class="d-flex p-20">
                                    <div class="form-check radio radio-primary m-0">
                                        <input checked class="form-check-input" id="hutang" type="radio" name="tipe_pembayaran" value="0">
                                        <label class="form-check-label" for="hutang"><span class="flex-grow-1 megaoption-space"><span class="mt-0 mega-title-badge">Invoices<span class="badge badge-primary pull-right digits">Hutang</span></span><span>Transaksi MCU ini akan dicatat sebagai hutang berdasarkan No Identitas Member</span></span></label>
                                    </div>
                                    </div>
                                </div>
                                </div>
                                <div class="col-sm-6">
                                <div id="card-tunai" class="card">
                                    <div class="d-flex p-20">
                                    <div class="form-check radio radio-secondary m-0">
                                        <input class="form-check-input" id="tunai" type="radio" name="tipe_pembayaran" value="1">
                                        <label class="form-check-label" for="tunai"><span class="flex-grow-1 megaoption-space"><span class="mt-0 mega-title-badge">Tunai Langsung<span class="badge badge-secondary pull-right digits">Tunai</span></span><span>Transaksi MCU ini akan dicatat sebagai Kas Kecil berdasarkan No Transaksi</span></span></label>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="pembayaran_tunai">
                    <div class="row">
                        <div class="col-md-4">
                            <h4 class="text-start mt-2">Metode Pembayaran</h4>
                        </div>
                        <div class="col-md-8">
                            <select class="form-select mt-2" id="select2_metode_pembayaran" name="select2_metode_pembayaran" required>
                                <option value="0">Tunai</option>
                                <option value="1">Transfer</option>
                            </select>  
                        </div>
                    </div>
                    <div class="row transaksi_transfer">
                        <div class="col-md-4">
                            <h4 class="text-start mt-2">Penerima Bank</h4>
                        </div>
                        <div class="col-md-8">
                            <select class="form-select mt-2" id="beneficiary_bank" name="beneficiary_bank" required>
                                @foreach ($data['bank'] as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_bank }}</option>
                                @endforeach
                            </select>  
                        </div>
                    </div>
                    <div class="row transaksi_transfer">
                        <div class="col-md-4">
                            <h4 class="text-start mt-2">Nomor Transaksi Transfer</h4>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control mt-2" id="nomor_transaksi_transfer" name="nomor_transaksi_transfer" placeholder="Ex: 12002158840023654">
                        </div>
                    </div>
                    <div class="row transaksi_tunai">
                        <div class="col-md-4">
                            <h4 class="text-start mt-2">Nominal Pembayaran</h4>
                        </div>
                        <div class="col-md-8">
                        <input type="text" class="form-control mt-2" id="nominal_bayar" name="nominal_bayar" placeholder="0.00">
                        </div>
                    </div>
                    <div class="row transaksi_tunai">
                        <div class="col-md-4">
                            <h4 class="text-start mt-2">Kembalian Pembayaran</h4>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control mt-2" id="nominal_kembalian" name="nominal_kembalian" placeholder="0.00" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary" id="btnSimpanPendaftaran">
                    <i class="fa fa-save"></i> Simpan Data
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('css_load')
<link href="https://fonts.cdnfonts.com/css/ds-digital" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('mofi/assets/css/vendors/flatpickr/flatpickr.min.css') }}">
<style>
.select2-container--default .select2-selection--single .select2-selection__arrow {
    margin-top: 10px;
    margin-right: 10px;
}
.select2-container--open .select2-dropdown--below {
  margin-top: -20px;
  border-top-left-radius:2;
  border-top-right-radius:2;
}
#nominal_bayar, #nominal_kembalian, #nominal_bayar_konfirmasi{
    text-align: right;
    font-family: 'DS-Digital', sans-serif;
    font-size: 5vw;
    color: red;
    padding: 0; 
    line-height: 1;
    font-weight: bold;
}
#nominal_bayar::placeholder, #nominal_kembalian::placeholder, #nominal_bayar_konfirmasi::placeholder {
    font-family: 'DS-Digital', sans-serif;
    font-size: 5vw;
    color: red;
}
</style>
@endsection
@section('js_load')
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.8.1/autoNumeric.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('mofi/assets/js/flat-pickr/flatpickr.js') }}"></script>
<script src="{{ asset('vendor/erayadigital/pendaftaran/formulirtambahpeserta.js') }}"></script>
<script>
    let isedit = false, id_detail_transaksi_mcu = null;
    $('#tanggal_pendaftaran').val(moment().format("DD-MM-YYYY"));
    @if (!empty($data['ubah']))
        isedit = true;
        id_detail_transaksi_mcu = "{{ $data['uuid'] }}";
        $("#nomor_identitas").val("{{ $data['peserta']->nomor_identitas }}");
        $("#nama_peserta").val("{{ $data['peserta']->nama_peserta }}");
        $("#tempat_lahir").val("{{ $data['peserta']->tempat_lahir }}");
        $("#tanggal_lahir_peserta").val("{{ \Carbon\Carbon::parse($data['peserta']->tanggal_lahir)->format('d-m-Y') }}");
        $("#tipe_identitas").val("{{ $data['peserta']->tipe_identitas }}").trigger('change');
        $("#jenis_kelamin").val("{{ $data['peserta']->jenis_kelamin }}").trigger('change');
        $("#status_kawin").val("{{ $data['peserta']->status_kawin }}").trigger('change');
        $("#no_telepon").val("{{ $data['peserta']->no_telepon }}");
        $("#email").val("{{ $data['peserta']->email }}");
        $("#alamat").val("{{ $data['peserta']->alamat }}");
        $("#jenis_transaksi_pendaftaran").val("{{ $data['peserta']->jenis_transaksi_pendaftaran }}").trigger('change');
        $("#tanggal_pendaftaran").val("{{ \Carbon\Carbon::parse($data['peserta']->tanggal_transaksi)->format('d-m-Y') }}");
    
        let newOptionPerusahaan = new Option("[{{ $data['perusahaan']->company_code }}] - {{ $data['perusahaan']->company_name }}", "{{ $data['perusahaan']->id }}", true, false);
        $('#select2_perusahaan').append(newOptionPerusahaan).trigger('change');
        $("#select2_perusahaan").val("{{ $data['perusahaan']->id }}").trigger('change');
    
        let newOptionDepartemen = new Option("[{{ $data['departemen']->kode_departemen }}] - {{ $data['departemen']->nama_departemen }}", "{{ $data['departemen']->id }}", true, false);
        $('#select2_departemen').append(newOptionDepartemen).trigger('change');
        $("#select2_departemen").val("{{ $data['departemen']->id }}").trigger('change');
    
        let newOptionPaketMcu = new Option("[{{ $data['paket_mcu']->kode_paket }}] - {{ $data['paket_mcu']->nama_paket }} | Harga : {{ number_format($data['paket_mcu']->harga_paket, 0, ',', '.') }}", "{{ $data['paket_mcu']->id }}|{{ $data['paket_mcu']->kode_paket }}|{{ $data['paket_mcu']->nama_paket }}|{{ $data['paket_mcu']->harga_paket }}|{{ $data['paket_mcu']->akses_poli }}", true, false);
        $('#select2_paket_mcu').append(newOptionPaketMcu).trigger('change');
        $("#select2_paket_mcu").val("{{ $data['paket_mcu']->id }}|{{ $data['paket_mcu']->kode_paket }}|{{ $data['paket_mcu']->nama_paket }}|{{ $data['paket_mcu']->harga_paket }}|{{ $data['paket_mcu']->akses_poli }}").trigger('change');
        const data = {
            id: "{{ $data['paket_mcu']->id }}|{{ $data['paket_mcu']->kode_paket }}|{{ $data['paket_mcu']->nama_paket }}|{{ $data['paket_mcu']->harga_paket }}|{{ $data['paket_mcu']->akses_poli }}",
            text: "[{{ $data['paket_mcu']->kode_paket }}] - {{ $data['paket_mcu']->nama_paket }} | Harga : {{ number_format($data['paket_mcu']->harga_paket, 0, ',', '.') }}"
        };
        $('#select2_paket_mcu').trigger({
            type: 'select2:select',
            params: { data: data }
        });
        let selectedProsesKerja = "[{{ $data['proses_kerja'] }}]";
        document.querySelectorAll('input[name="proses_kerja"]').forEach((checkbox) => {
            checkbox.checked = false;
            if (selectedProsesKerja.includes(checkbox.value)) {
                checkbox.checked = true;
            }
        });
    @endif
</script>    
@endsection
