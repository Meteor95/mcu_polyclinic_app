<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Berkas Treadmill {{ $data['nomor_mcu'] }}</title>
<style>
@page { 
    margin:0;
}

body {
    margin: 0;
    padding: 0;
}
.full-image-page {
    page-break-after: always;
    margin: 0;
    padding: 0;
    text-align: center;
}

.full-image-page img {
    width: 100%;
    height: auto;
    max-height: 100%;
    display: block;
}
.cover {
    width: 100%;
    height: 100%;
    z-index: 999;
}
.header {
    margin-top: 0px;
    margin-bottom:0px;
}
watermark {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 102;
}
</style>
</head>
<body>
    @php
    if (!function_exists('header_mcu')) {
        function header_mcu($data){
            echo '
            <table style="border-collapse: collapse; width: 100%;font-weight: bold;font-size: 13px;">
                <tr style="text-align: left;">
                    <td style="width: 15%;">Nama</td>
                    <td style="width: 30%;">: ' . $data['informasi_data_diri']['nama_peserta'] . '</td>
                    <td style="width: 15%;">No MCU</td>
                    <td style="width: 30%;">: ' . $data['nomor_mcu'] . '</td>
                </tr>
                <tr style="text-align: left;">
                    <td style="width: 15%;">TTL / Umur</td>
                    <td style="width: 30%;">: ' . $data['informasi_data_diri']['tempat_lahir'] . ', ' . date('d-m-Y', strtotime($data['informasi_data_diri']['tanggal_lahir'])) . ' / ' . $data['informasi_data_diri']['umur'] . ' Tahun</td>
                    <td style="width: 15%;">Tanggal Pemeriksaan</td>
                    <td style="width: 30%;">: ' . date('d-m-Y', strtotime($data['informasi_data_diri']['tanggal_mcu'])) . '</td>
                </tr>
                <tr style="text-align: left;">
                    <td style="width: 15%;">NIK / NRR</td>
                    <td style="width: 30%;">: ' . $data['informasi_data_diri']['nomor_identitas'] . '</td>
                    <td style="width: 15%;">Perusahaan</td>
                    <td style="width: 30%;">: ' . $data['informasi_data_diri']['company_name'] . '</td>
                </tr>
                <tr style="text-align: left;">
                    <td style="width: 15%;">Jenis Kelamin</td>
                    <td style="width: 30%;">: ' . $data['informasi_data_diri']['jenis_kelamin'] . '</td>
                    <td style="width: 15%;">Departemen</td>
                    <td style="width: 30%;">: ' . $data['informasi_data_diri']['nama_departemen'] . '</td>
                </tr>
                <tr style="text-align: left;">
                    <td style="width: 15%;">Tipe MCU</td>
                    <td style="width: 30%;">: ' . $data['informasi_data_diri']['jenis_transaksi_pendaftaran'] . '</td>
                    <td style="width: 15%;">Dokter</td>
                    <td style="width: 30%;">: dr. Muhammad Taufiq Amrullah, S.Ked</td>
                </tr>
            </table>
            <hr style="border: 2px solid #000;">
            ';
        }
    }
    @endphp
    <div class="cover">
        <img src="{{ asset('mofi/assets/images/logo/cover_threadmill.jpg') }}" alt="Cover AMC" style="width: 100%;height: 100%;">
    </div>
    <div class="header">
        <img src="{{ asset('mofi/assets/images/logo/border_hasil_mcu_atas.png') }}" alt="Border Hasil MCU" style="top: 0;right: 0;width: 100%;z-index: -1;opacity: 0.6;">
        <table style="width: 100%;padding-right: 25px;margin-top:-235px;margin-bottom: -100px;">
            <tr>
                <td style="width:30%; vertical-align: center;">
                    <img src="{{ asset('mofi/assets/images/logo/Logo_AMC_Full.png') }}" alt="Logo AMC" style="width: 65%;padding-top: 20px;">
                </td>
                <td style="width:70%; text-align: right;">
                    <p>
                        <span style="font-size: 25px; font-weight: bold;">Klinik {{ config('app.name') }}</span><br>
                        <span style="font-size: 15px;">Alamat: Jl. Sendawar Raya RT 029 Kel. Melak Ulu Kec. Melak, Kutai Barat 75765</span><br>
                        <span style="font-size: 15px;">E-Mail: amc.clinic.yhs@gmail.com | website: arthamedicalcentre.com</span><br>
                        <span style="font-size: 15px;">Contact Person: 0812-3456-7890 | 0812-3456-7890</span>
                    </p>
                </td>
            </tr>
        </table>
    </div>
    <watermark>
        <img src="{{ asset('mofi/assets/images/logo/confidential_wlogo.png') }}" alt="Watermark" style="width: 100%; opacity: 0.1;">
    </watermark>
    <main>
        <div style="page-break-after: always;" class="portrait-page">
            <div style="text-align: center;">
                <h3>UJI LATIH JANTUNG<br>(TREADMILL STRESS TEST)</h3>
                <img src="{{ $data['riwayat_informasi_foto']['data_foto'] }}" style="height: 250px;border-radius: 10px;">
            </div>
            <table style="width: 80%; margin: 10px auto;font-weight: bold;">
                <tr>
                    <td style="white-space: nowrap;">NOMOR MEDICAL CHECKUP</td>
                    <td>:</td>
                    <td>{{ $data['nomor_mcu'] }}</td>
                </tr>
                <tr>
                    <td style="white-space: nowrap;">NAMA PESERTA</td>
                    <td>:</td>
                    <td>{{ $data['informasi_data_diri']['nama_peserta'] }}</td>
                </tr>
                <tr>
                    <td style="white-space: nowrap;">NIK / NRR</td>
                    <td>:</td>
                    <td>{{ $data['informasi_data_diri']['nomor_identitas'] }}</td>
                </tr>
                <tr>
                    <td style="white-space: nowrap;">TEMPAT TANGGAL LAHIR / UMUR</td>
                    <td>:</td>
                    <td>{{ $data['informasi_data_diri']['tempat_lahir'] }}, {{ date('d-m-Y', strtotime($data['informasi_data_diri']['tanggal_lahir'])) }} / {{ $data['informasi_data_diri']['umur'] }} Tahun</td>
                </tr>
                <tr>
                    <td style="white-space: nowrap;">JENIS KELAMIN</td>
                    <td>:</td>
                    <td>{{ $data['informasi_data_diri']['jenis_kelamin'] }}</td>
                </tr>
                <tr>
                    <td style="white-space: nowrap;">PERUSAHAAN</td>
                    <td>:</td>
                    <td>{{ $data['informasi_data_diri']['company_name'] }}</td>
                </tr>
                <tr>
                    <td style="white-space: nowrap;">DEPARTEMEN JABATAN</td>
                    <td>:</td>
                    <td>{{ $data['informasi_data_diri']['nama_departemen'] }}</td>
                </tr>
                <tr>
                    <td style="white-space: nowrap;">ALAMAT</td>
                    <td>:</td>
                    <td>{{ $data['informasi_data_diri']['alamat'] }}</td>
                </tr>
                <tr>
                    <td style="white-space: nowrap;">TANGGAL THREADMILL</td>
                    <td>:</td>
                    <td>{{ date('d-m-Y', strtotime($data['informasi_data_diri']['tanggal_mcu'])) }} </td>
                </tr>
            </table>
        </div>
    </main>
    <main>
    <div class="header">
        <img src="{{ asset('mofi/assets/images/logo/border_hasil_mcu_atas.png') }}" alt="Border Hasil MCU" style="top: 0;right: 0;width: 100%;z-index: -1;opacity: 0.6;">
        <table style="width: 100%;padding-right: 25px;margin-top:-235px;margin-bottom: -100px;">
            <tr>
                <td style="width:30%; vertical-align: center;">
                    <img src="{{ asset('mofi/assets/images/logo/Logo_AMC_Full.png') }}" alt="Logo AMC" style="width: 65%;padding-top: 20px;">
                </td>
                <td style="width:70%; text-align: right;">
                    <p>
                        <span style="font-size: 25px; font-weight: bold;">Klinik {{ config('app.name') }}</span><br>
                        <span style="font-size: 15px;">Alamat: Jl. Sendawar Raya RT 029 Kel. Melak Ulu Kec. Melak, Kutai Barat 75765</span><br>
                        <span style="font-size: 15px;">E-Mail: amc.clinic.yhs@gmail.com | website: arthamedicalcentre.com</span><br>
                        <span style="font-size: 15px;">Contact Person: 0812-3456-7890 | 0812-3456-7890</span>
                    </p>
                </td>
            </tr>
        </table>
    </div>
    @forelse ($data['all_citra_data']->groupBy('jenis_poli') as $jenis_poli => $dataPoli)
        @php $firstItem = $dataPoli->first(); @endphp
        <!-- HALAMAN INTERPRETASI -->
        <div style="page-break-after: always;padding:0 50 0 50">
            @php header_mcu($data); @endphp
            <div style="text-align: center; font-weight: bold; font-size: 20px;">
                INTERPRETASI HASIL {{ strtoupper(str_replace('_', ' ', $jenis_poli)) }}
            </div>

            <table style="width: 100%;">
                <tr>
                    <td style="width:35%;padding-left:20px;font-size:14px;">Resume</td>
                    <td style="width:65%;font-size:14px;">{!! $firstItem->kesimpulan_citra_spirometri !!}</td>
                </tr>
                <tr>
                    <td style="padding-left:20px;font-size:14px;">Kesimpulan</td>
                    <td style="font-size:14px;">{{ $firstItem->kesimpulan ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-left:20px;font-size:14px;">Saran / Catatan Kaki</td>
                    <td style="font-size:14px;">{{ $firstItem->catatan_kaki ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="width:35%;padding-left:20px;font-size:14px;">Dokter Yang Bertugas</td>
                    <td style="width:65%;font-size:14px;">{{ $firstItem->nama_pegawai ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-left:20px;font-size:14px;">Petugas Poliklinik Spirometri</td>
                    <td style="font-size:14px;">{{ $firstItem->nama_petugas ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding-left:20px;font-size:14px;">Judul Interpretasi</td>
                    <td style="font-size:14px;">{{ $firstItem->judul_laporan ?? '-' }}</td>
                </tr>
            </table>

            <!-- Footer/TTD bisa ditaruh di interpretasi halaman ini -->
            <div style="margin-top: 50px; width:100%; text-align:center; font-size:13px;">
                <div style="display:inline-block; width:45%;">
                    Petugas {{ ucwords(str_replace('_', ' ', $jenis_poli)) }}<br>
                    Sendawar, {{ $data['tanggal_cetak'] }}<br>
                    <img src="data:image/png;base64,{{ $data['qrcode'] }}"><br>
                    <b><u>{{ $firstItem->nama_petugas }}</u></b><br>
                    <b>{{ $firstItem->departemen_petugas }}</b>
                </div>
                <div style="display:inline-block; width:45%;">
                    Mengetahui<br>
                    Sendawar, {{ $data['tanggal_cetak'] }}<br>
                    <img src="data:image/png;base64,{{ $data['qrcode'] }}"><br>
                    <b><u>{{ $firstItem->nama_pegawai }}</u></b><br>
                    <b>{{ $firstItem->departemen }}</b>
                </div>
            </div>
        </div>
        @empty
    @endforelse
    </main>
    @forelse ($data['all_citra_data']->groupBy('jenis_poli') as $jenis_poli => $dataPoli)
        @foreach ($dataPoli as $item)
            <div class="full-image-page">
                <img src="{{ $item->data_foto }}">
            </div>
        @endforeach
    @empty
    @endforelse
</body>
</html>