<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Berkas MCU {{ $data['nomor_mcu'] }}</title>
<style>
@page { 
    margin:110px 0px 80px 40px;
}
@page:first {
    margin: 0; 
}
.header {
    position: fixed;
    margin-top: -120px;
    margin-bottom:0px;
}
main {
    margin-right: 25px;
}
.break-before { page-break-after: auto; }
.section {margin-bottom: 0px; }
.judul_riwayat {
    text-align: left; 
    background-color: #f1491f; 
    color: #fff;
    padding: 5px;
    border-bottom-right-radius: 10px; 
    border-top-right-radius: 10px; 
    border-bottom-left-radius: 10px; 
    border-top-left-radius: 10px; 
    display: inline-block;
    margin-bottom: 5px;
}
.judul_kondisi_fisik {
    margin-top: 0px;
    text-align: left; 
    background-color: #4ab1c7; 
    color: #fff; 
    padding: 10px; 
    border-radius: 10px; 
    display: inline-block;
    margin-bottom: 5px;
}
footer {
    position: fixed;
    bottom: -70px;
    left: 0;
    right: 0;
    text-align: center;
    font-size: 13px;
    z-index: 103;
}
footer .page-number:after {
}
watermark {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 102;
}
background_bottom {
    position: fixed;
    bottom: -90px;
    left: -50px;
    z-index: -1;
    opacity: 0.6;
}
#medical-checkup-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 10px;
  font-size: 13px;
}
#medical-checkup-table th, #medical-checkup-table td {
  border: 1px solid black;
  padding: 5px;
}
.box {
    display: inline-block;
    width: 15px;
    height: 15px;
    margin-right: 5px;
    border: 1px solid #000;
    vertical-align: middle;
}
.sehat-resiko-ringan {
  background-color: #ffff00;
}
.sehat {
  background-color: #90ee90;
}
.resiko-sedang-tinggi {
  background-color: #ffa500;
}
.tidak-sehat {
  background-color: #f8786e;
}
.tabel_riwayat {
    border-collapse: collapse;
}
.tabel_riwayat tr:nth-child(even) {
    background-color: #f2f2f2;
}
.tabel_riwayat tr:nth-child(odd) {
    background-color: #ffffff;
}
.lapang_pandang_table {
  border-collapse: collapse;
}
.lapang_pandang_table tr,
.lapang_pandang_table th,
.lapang_pandang_table td {
  border: 1px solid black;
}
.cover {
    width: 100%;
    height: 100%;
    z-index: 999;
}
.cover_back {
    width: 100%;
    height: 100%;
    z-index: 999;
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
                    <td style="width: 30%;">: ' . $data['informasi_data_diri']['tipe_mcu_peserta'] . '</td>
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
       <img src="{{ asset('mofi/assets/images/logo/compress_cover.jpg') }}" alt="Cover AMC" style="width: 100%;height: 100%;">
    </div>
    <div class="header">
        <img src="{{ asset('mofi/assets/images/logo/border_hasil_mcu_atas.png') }}" alt="Border Hasil MCU" style="position: absolute;top: 0;right: 0;width: 100%;z-index: -1;opacity: 0.6;">
        <table style="width: 100%;padding-right: 25px;">
            <tr>
                <td style="width:30%; vertical-align: center;">
                    <img src="{{ asset('mofi/assets/images/logo/Logo_AMC_Full.png') }}" alt="Logo AMC" style="width: 100%;padding-top: 20px;">
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
    <footer>
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; text-align: left; padding-left: 20px;">
                    <div style="vertical-align: middle;">
                        <img src="{{ asset('mofi/assets/images/logo/IASCB.png') }}" alt="Logo IASCB" style="height: 50px;">
                        <img src="{{ asset('mofi/assets/images/logo/KEMENTAKER.png') }}" alt="Logo Kementerian Ketenagakerjaan" style="height: 50px;">
                        <img src="{{ asset('mofi/assets/images/logo/VRC.png') }}" alt="Logo VRC" style="height: 50px;">
                    </div>
                </td>
                <td style="width: 50%; text-align: right; padding-right: 25px; vertical-align: middle;">
                    <div class="page-number"></div>
                </td>
            </tr>
        </table>
    </footer>
    <watermark>
        <img src="{{ asset('mofi/assets/images/logo/confidential_wlogo.png') }}" alt="Watermark" style="width: 100%; opacity: 0.1;">
    </watermark>
    <background_bottom>
        <img src="{{ asset('mofi/assets/images/logo/border_hasil_mcu_bawah.png') }}" alt="Background Bottom" style="width: 100%;">
    </background_bottom>
    <div class="break-before section">
    <main>
        <div style="page-break-after: always;">
            <div style="text-align: center;">
                <h3>PEMERIKSAAN KESEHATAN<br>(MEDICAL CHECKUP)</h3>
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
                    <td style="white-space: nowrap;">TANGGAL MCU / TIPE MCU</td>
                    <td>:</td>
                    <td>{{ date('d-m-Y', strtotime($data['informasi_data_diri']['tanggal_mcu'])) }} / {{ $data['informasi_data_diri']['tipe_mcu_peserta'] }}</td>
                </tr>
            </table>
            <table style="width: 100%; text-align: left; border-collapse: collapse; position: absolute; bottom: 80px;">
                <tr>
                    <td style="width: 50%; padding: 10px;">
                        <strong>ISO 9001:2015 Certified</strong><br>
                        2410010019699K001<br>
                        <strong>ISO 14001:2015 Certified</strong><br>
                        24100100196914K001
                    </td>
                </tr>
            </table>
        </div>
    </main>
    </div>

    <div class="break-before section">
    <main>
        <div style="page-break-after: always;">
            @php header_mcu($data); @endphp
            <h3 style="text-align: left; background-image: url({{ asset('mofi/assets/images/logo/gradient_bg_title.png') }}); background-size: cover; background-repeat: no-repeat; color: #fff; padding: 10px; border-bottom-right-radius: 10px; border-top-right-radius: 10px; display: inline-block;">LAPORAN HASIL MEDICAL CHECKUP</h3> 
            <h4 style="padding:0px;margin:0px;">HASIL PEMERIKSAAN</h4>
            <table style="width: 100%; font-size: 13px;">
                @if (preg_replace('/\s+/', '', strip_tags($data['quill_pemeriksaan_riwayat_medis'])) != '')
                <tr>
                    <td style="width: 1%; text-align: center;">&#8226;</td>
                    <td style="width: 29%;">RIWAYAT MEDIS</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 70%;">{!! $data['quill_pemeriksaan_riwayat_medis'] !!}</td>
                </tr>
                @endif

                @if (preg_replace('/\s+/', '', strip_tags($data['quill_pemeriksaan_fisik'])) != '')
                <tr>
                    <td style="width: 1%; text-align: center;">&#8226;</td>
                    <td style="width: 29%;">PEMERIKSAAN FISIK</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 70%;">{!! $data['quill_pemeriksaan_fisik'] !!}</td>
                </tr>
                @endif

                @if (preg_replace('/\s+/', '', strip_tags($data['quill_pemeriksaan_laboratorium'])) != '')
                <tr>
                    <td style="width: 1%; text-align: center;">&#8226;</td>
                    <td style="width: 29%;">HASIL LABORATORIUM</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 70%;">{!! $data['quill_pemeriksaan_laboratorium'] !!}</td>
                </tr>
                @endif

                @if (preg_replace('/\s+/', '', strip_tags($data['quill_pemeriksaan_rontgen_thorax'])) != '')
                <tr>
                    <td style="width: 1%; text-align: center;">&#8226;</td>
                    <td style="width: 29%;">RO THORAX</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 70%;">{!! $data['quill_pemeriksaan_rontgen_thorax'] !!}</td>
                </tr>
                @endif

                @if (preg_replace('/\s+/', '', strip_tags($data['quill_pemeriksaan_rontgen_lumbosacral'])) != '')
                <tr>
                    <td style="width: 1%; text-align: center;">&#8226;</td>
                    <td style="width: 29%;">RO LUMBOSACRAL</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 70%;">{!! $data['quill_pemeriksaan_rontgen_lumbosacral'] !!}</td>
                </tr>
                @endif

                @if (preg_replace('/\s+/', '', strip_tags($data['quill_pemeriksaan_usg_ubdomain'])) != '')
                <tr>
                    <td style="width: 1%; text-align: center;">&#8226;</td>
                    <td style="width: 29%;">USG UB DOMAIN</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 70%;">{!! $data['quill_pemeriksaan_usg_ubdomain'] !!}</td>
                </tr>
                @endif

                @if (preg_replace('/\s+/', '', strip_tags($data['quill_pemeriksaan_ekg'])) != '')
                <tr>
                    <td style="width: 1%; text-align: center;">&#8226;</td>
                    <td style="width: 29%;">EKG</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 70%;">{!! $data['quill_pemeriksaan_ekg'] !!}</td>
                </tr>
                @endif

                @if (
                    preg_replace('/\s+/', '', strip_tags($data['quill_pemeriksaan_audio_kiri'])) != '' ||
                    preg_replace('/\s+/', '', strip_tags($data['quill_pemeriksaan_audio_kanan'])) != ''
                )
                <tr>
                    <td style="width: 1%; text-align: center;">&#8226;</td>
                    <td style="width: 29%;">AUDIOMETRI</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 70%;">
                        @if (
                            strtolower(trim(strip_tags($data['quill_pemeriksaan_audio_kiri']))) == "normal" && 
                            strtolower(trim(strip_tags($data['quill_pemeriksaan_audio_kanan']))) == "normal"
                        )
                            <span>Normal</span>
                        @else
                            <span style="font-weight: bold;">Kiri</span>: {{ trim(strip_tags($data['quill_pemeriksaan_audio_kiri'])) }}&nbsp;&nbsp;
                            <span style="font-weight: bold;">Kanan</span>: {{ trim(strip_tags($data['quill_pemeriksaan_audio_kanan'])) }}
                        @endif
                    </td>
                </tr>
                @endif

                @if (
                    preg_replace('/\s+/', '', strip_tags($data['quill_pemeriksaan_spiro_restriksi'])) != '' ||
                    preg_replace('/\s+/', '', strip_tags($data['quill_pemeriksaan_spiro_obstruksi'])) != ''
                )
                <tr>
                    <td style="width: 1%; text-align: center;">&#8226;</td>
                    <td style="width: 29%;">SPIROMETRI</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 70%;">
                        @if (
                            strtolower(trim(strip_tags($data['quill_pemeriksaan_spiro_restriksi']))) == "normal" && 
                            strtolower(trim(strip_tags($data['quill_pemeriksaan_spiro_obstruksi']))) == "normal"
                        )
                            <span>Normal</span>
                        @else
                            <span style="font-weight: bold;">Restriksi</span>: {!! trim(strip_tags($data['quill_pemeriksaan_spiro_restriksi'])) !!}&nbsp;&nbsp;
                            <span style="font-weight: bold;">Obstruksi</span>: {!! trim(strip_tags($data['quill_pemeriksaan_spiro_obstruksi'])) !!}
                        @endif
                    </td>
                </tr>
                @endif

                @if (preg_replace('/\s+/', '', strip_tags($data['quill_pemeriksaan_farmingham_score'])) != '')
                <tr>
                    <td style="width: 1%; text-align: center;">&#8226;</td>
                    <td style="width: 29%;">FARMINGHAM SCORE</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 70%;">{!! trim(strip_tags($data['quill_pemeriksaan_farmingham_score'])) !!}</td>
                </tr>
                @endif

                @if (preg_replace('/\s+/', '', strip_tags($data['quill_pemeriksaan_threadmill'])) != '')
                <tr>
                    <td style="width: 1%; text-align: center;">&#8226;</td>
                    <td style="width: 29%;">THREADMILL</td>
                    <td style="width: 5%;">:</td>
                    <td style="width: 70%;">{!! trim(strip_tags($data['quill_pemeriksaan_threadmill'])) !!}</td>
                </tr>
                @endif
            </table>

            <h4 style="padding-top: 10px;padding-bottom: 0px;margin:0px;">KESIMPULAN HASIL MEDICAL CHECKUP</h4>
            <div style="display: inline-block; border: 3px solid black; padding: 10px 100px 10px 100px; font-weight: bold; font-size: 20px; text-align: left;">
                {{ strtoupper(str_replace("_", " ", $data['kesimpulan_hasil_medical_checkup'])) }}
            </div>  
            <h4 style="padding-top: 10px;padding-bottom: 0px;margin:0px;">SARAN HASIL MEDICAL CHECKUP</h4>
            <div style="font-size: 13px;">{!! $data['quill_tindakan_saran'] !!}</div>
            <div style="position: absolute;width: 100%;bottom:0%">
                <table style="width: 100%;">
                    <tr>
                       <td style="width: 50%; text-align: center; padding-left: 20px;font-size: 13px;">
                        Pindai untuk periksa keaslian dokumen<br>
                        Dokumen ini tervalidasi dan dicetak secara otomatis<br>
                        <img src="data:image/png;base64,{{ $data['qrcode'] }}">
                        </td>
                        <td style="width: 50%; text-align: center; padding-right: 20px; font-size: 13px;">
                        Mengetahui<br>Sendawar, {{ $data['tanggal_cetak'] }}<br>
                            <img src="data:image/png;base64,{{ $data['qrcode'] }}"><br>
                            <span style="font-weight: bold;"><u>dr. Muhammad Taufiq Amrullah, S.Ked</u></span><br>
                            <span style="font-weight: bold;">440.007.2/127/SIP-DINKES/XI/2023</span>
                        </td>
                    </tr>
                </table>
            </div>
            </div>
        </div>
    </main>
    </div>

    <div class="break-before section">
    <main>
        <div style="page-break-after: always;">
            @php header_mcu($data); @endphp
            <h3 style="text-align: left; background-image: url({{ asset('mofi/assets/images/logo/gradient_bg_title.png') }}); background-size: cover; background-repeat: no-repeat; color: #fff; padding: 10px; border-bottom-right-radius: 10px; border-top-right-radius: 10px; display: inline-block;">STATUS KESEHATAN</h3> 
                <table id="medical-checkup-table" style="width: 100%; border-collapse: separate; border-spacing: 3px;">
                <thead>
                    <tr style="text-align: center; background-color: #2c942a;font-weight: bold;color: #fff;font-size: 14px;">
                        <th style="width: 30%;">STATUS</th>
                        <th style="width: 20%;">KATEGORI</th>
                        <th style="width: 50%;">CATATAN</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // 1. Gabungkan status berdasarkan kategori normalisasi
                        $groupedItems = [];

                        foreach ($data['status_kesimpulan_lab'] as $status => $items) {
                            foreach ($items as $item) {
                                $raw_status = strtoupper(trim($item->status));
                                $normalized_status = (str_contains($raw_status, 'FIT TO WORK') || str_contains($raw_status, 'FIT WITH NOTE'))
                                    ? 'FIT'
                                    : $raw_status;
                                $groupedItems[$normalized_status][] = $item;
                            }
                        }

                        // 2. Normalisasi juga target kesimpulan user
                        $target_kesimpulan = strtoupper(trim($data['kesimpulan_hasil_medical_checkup']));
                        if (str_contains($target_kesimpulan, 'FIT_TO_WORK') || str_contains($target_kesimpulan, 'FIT_WITH_NOTE')) {
                            $target_kesimpulan = 'FIT';
                        }

                        // 3. Data referensi warna
                        $id_terpilih = $data['kesimpulan_tindakan']->kesimpulan_keseluruhan;
                        $id_terpilih_hex = $data['kesimpulan_tindakan']->kesimpulan_warna;
                    @endphp

                    @foreach ($groupedItems as $normalized_status => $items)
                        @php
                            $rowspan = count($items);
                            $firstRow = true;
                            $highlight_kategori = ($normalized_status == $target_kesimpulan);
                        @endphp

                        @foreach ($items as $item)
                            @php
                                $background_color = 'white';
                                $background_color_kategori = $highlight_kategori ? $id_terpilih_hex : 'white';

                                if ($item->id == $id_terpilih) {
                                    $background_color = $id_terpilih_hex;
                                }
                            @endphp
                            <tr>
                                @if ($firstRow)
                                    <td style="text-align: center; background-color: {{ $background_color_kategori }} !important;" rowspan="{{ $rowspan }}">
                                        {{ $normalized_status }}
                                    </td>
                                    @php $firstRow = false; @endphp
                                @endif
                                <td style="text-align: center; background-color: {{ $background_color }};">{{ $item->kategori }}</td>
                                <td style="text-align: left; background-color: {{ $background_color }};">{{ $item->catatan }}</td>
                            </tr>
                        @endforeach
                    @endforeach


                </tbody>
                </table>
                <div class="keterangan">
                <span style="font-weight: bold">KETERANGAN:</span>
                <table style="width: 100%; font-size: 12px; margin-bottom: 10px; text-align: center;">
                    <tr>
                       <td style="width: 20%;">
                            <div class="box sehat" style="margin: 0 auto; width: 100%;"></div>
                            <div><br>SEHAT</div>
                        </td>
                        <td style="width: 20%;">
                            <div class="box sehat-resiko-ringan" style="margin: 0 auto; width: 100%;"></div>
                            <div><br>SEHAT RESIKO RINGAN</div>
                        </td>
                        <td style="width: 30%;">
                            <div class="box resiko-sedang-tinggi" style="margin: 0 auto; width: 100%;"></div>
                            <div>RESIKO SEDANG / TINGGI DAN PERLU PENGOBATAN</div>
                        </td>
                        <td style="width: 30%;">
                            <div class="box tidak-sehat" style="margin: 0 auto; width: 100%;"></div>
                            <div>TIDAK SEHAT / PERLU PENGOBATAN DAN PERAWATAN RUTIN</div>
                        </td>
                    </tr>
                </table>

                </div>
                <span style="font-weight: bold">CATATAN:</span>
                <ol style="margin-top: 0px;font-size: 13px;">
                    <li>Kesimpulan yang dikeluarkan berdasarkan hasil temuan yang didapatkan pada pemeriksaan medical check up.</li>
                    <li>Kesimpulan hasil medical check up tidak dapat diganggu gugat.</li>
                    <li>Bila masih ada hal yang perlu dijelaskan, mohon segera menghubungi dokter. Terima kasih atas kerja samanya.</li>
                </ol>
            <div style="bottom:100px;position: absolute;width: 100%;">
                <table style="width: 100%;">
                    <tr>
                       <td style="width: 50%; text-align: left; padding-left: 20px;font-size: 13px;">
                        Tim Dokter Medical Check Up
                            <ol style="margin-top: 0px;">
                                <li>dr. Muhammad Taufiq Amrullah, S.Ked</li>
                                <li>dr. Khadijah Amir, S.Ked</li>
                                <li>dr. Devi Grania Amelia Selekede, Sp.P.</li>
                                <li>dr. Muhammad Asrul. M.Kes Sp.JP(K)</li>
                                <li>dr. Amir. Sp.Rad</li>
                            </ol>
                        </td>
                        <td style="width: 50%; text-align: center; padding-right: 20px; font-size: 13px;">
                        Mengetahui<br>Sendawar, {{ $data['tanggal_cetak'] }}<br>
                            <img src="data:image/png;base64,{{ $data['qrcode'] }}"><br>
                            <span style="font-weight: bold;"><u>dr. Muhammad Taufiq Amrullah, S.Ked</u></span><br>
                            <span style="font-weight: bold;">440.007.2/127/SIP-DINKES/XI/2023</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </main>
    </div>

    <div class="break-before section">
    <main>
        <div style="page-break-after: always;">
            @php header_mcu($data); @endphp
            <h4 class="judul_riwayat">RIWAYAT PENYAKIT TERDAHULU</h4>
            <table class="tabel_riwayat" style="width: 100%;font-size: 13px;border: 1px solid black;">
                <tr style="text-align: center; background-color: #2c942a;font-weight: bold;color: #fff;font-size: 14px;">
                    <th style="width: 30%;padding-top: 5px;padding-bottom: 5px;">PERTANYAAN</th>
                    <th style="width: 30%;padding-top: 5px;padding-bottom: 5px;">JAWABAN</th>
                    <th style="width: 40%;padding-top: 5px;padding-bottom: 5px;">KETERANGAN</th>
                </tr>
                @foreach ($data['riwayat_penyakit_terdahulu'] as $item)
                <tr>
                    <td style="padding-left: 5px;">{{ $item->nama_atribut_saat_ini }}</td>
                    <td style="text-align: center;">{{ $item->status == "1" ? "Ya" : "Tidak" }}</td>
                    <td style="text-align: center;">{{ $item->keterangan == "" ? "-" : $item->keterangan }}</td>
                </tr>
                @endforeach
            </table>
            <h4 class="judul_riwayat">RIWAYAT PENYAKIT KELUARGA</h4> 
             <table class="tabel_riwayat" style="width: 100%;font-size: 13px;border: 1px solid black;">
                <tr style="text-align: center; background-color: #2c942a;font-weight: bold;color: #fff;font-size: 14px;">
                    <th style="width: 30%;padding-top: 5px;padding-bottom: 5px;">PERTANYAAN</th>
                    <th style="width: 30%;padding-top: 5px;padding-bottom: 5px;">JAWABAN</th>
                    <th style="width: 40%;padding-top: 5px;padding-bottom: 5px;">KETERANGAN</th>
                </tr>
                @foreach ($data['riwayat_penyakit_terdahulu'] as $item)
                <tr>
                    <td style="padding-left: 5px;">{{ $item->nama_atribut_saat_ini }}</td>
                    <td style="text-align: center;">{{ $item->status == "1" ? "Ya" : "Tidak" }}</td>
                    <td style="text-align: center;">{{ $item->keterangan == "" ? "-" : $item->keterangan }}</td>
                </tr>
                @endforeach
            </table>
            <h4 class="judul_riwayat">RIWAYAT KECELAKAAN KERJA</h4>
            <div style="font-size: 13px;">{!! $data['riwayat_kecelakaan_kerja'] !!}</div>
            <h4 class="judul_riwayat">RIWAYAT KEBIASAAN</h4>
            <table class="tabel_riwayat" style="width: 100%;font-size: 13px;border: 1px solid black;">
                <thead>
                <tr style="text-align: center; background-color: #2c942a;font-weight: bold;color: #fff;font-size: 14px;">
                    <th style="padding-top: 5px;padding-bottom: 5px;">PERTANYAAN</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">JAWABAN</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">NILAI</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">SATUAN</th>
                    <th style="padding-top: 5px;padding-bottom: 5px;">KETERANGAN</th>
                </tr>
                </thead>
                <tbody style="border: 1px solid black;">
                @foreach ($data['riwayat_kebiasaan_hidup'] as $item)
                    @if ($item->jenis_kebiasaan == 1)
                        <tr>
                            <td style="padding-left: 5px;">{{ $item->nama_kebiasaan }}</td>
                            <td style="text-align: center;">{{ $item->status_kebiasaan == 0 ? 'Tidak' : 'Ya' }}</td>
                            <td style="text-align: center;">{{ $item->nilai_kebiasaan }}</td>
                            <td style="text-align: center;">{{ $item->satuan_kebiasaan }}</td>
                            <td style="text-align: center;">{{ $item->keterangan == "" ? "-" : $item->keterangan }}</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
            @if ($data['informasi_data_diri']->jenis_kelamin == 'Perempuan')
                <h3 style="text-align: left; background-color: red; color: #fff; padding: 10px; border-bottom-right-radius: 10px; border-top-right-radius: 10px; display: inline-block;margin-bottom: 5px;">KHUSUS WANITA</h3> 
                <table class="tabel_riwayat" style="width: 100%;font-size: 13px;border: 1px solid black;">
                    <thead>
                        <tr style="text-align: center; background-color: #2c942a;font-weight: bold;color: #fff;font-size: 14px;">
                            <th style="padding-top: 5px;padding-bottom: 5px;">PERTANYAAN</th>
                            <th style="padding-top: 5px;padding-bottom: 5px;">JAWABAN</th>
                            <th style="padding-top: 5px;padding-bottom: 5px;">WAKTU</th>
                            <th style="padding-top: 5px;padding-bottom: 5px;">SATUAN</th>
                            <th style="padding-top: 5px;padding-bottom: 5px;">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['riwayat_kebiasaan_hidup'] as $item)
                            @if ($item->jenis_kebiasaan == 2)
                            <tr>
                                <td>{{ $item->nama_kebiasaan }}</td>
                                <td style="text-align: center;">{{ $item->status_kebiasaan == 0 ? 'Tidak' : 'Ya' }}</td>
                                <td style="text-align: center;">{{ date('d-m-Y H:i:s', strtotime($item->waktu_kebiasaan)) }}</td>
                                <td style="text-align: center;">{{ $item->satuan_kebiasaan }}</td>
                                <td style="text-align: center;">{{ $item->keterangan == "" ? "-" : $item->keterangan }}</td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif
            <h4 class="judul_riwayat">RIWAYAT IMUNISASI</h4> 
             <table class="tabel_riwayat" style="width: 100%;font-size: 13px;border: 1px solid black;">
                <tr style="text-align: center; background-color: #2c942a;font-weight: bold;color: #fff;font-size: 14px;">
                    <th style="width: 30%;padding-top: 5px;padding-bottom: 5px;">PERTANYAAN</th>
                    <th style="width: 30%;padding-top: 5px;padding-bottom: 5px;">JAWABAN</th>
                    <th style="width: 40%;padding-top: 5px;padding-bottom: 5px;">KETERANGAN</th>
                </tr>
                @foreach ($data['riwayat_imunisasi'] as $item)
                <tr>
                    <td>{{ $item->nama_atribut_saat_ini }}</td>
                    <td style="text-align: center;">{{ $item->status == "1" ? "Ya" : "Tidak" }}</td>
                    <td style="text-align: center;">{{ $item->keterangan == "" ? "-" : $item->keterangan }}</td>
                </tr>
                @endforeach
            </table>
            <h4 class="judul_riwayat">RIWAYAT PAPARAN KERJA</h4> 
            <table class="tabel_riwayat" style="width: 100%;font-size: 13px;border: 1px solid black;">
                <thead>
                    <tr style="text-align: center; background-color: #2c942a;font-weight: bold;color: #fff;font-size: 14px;">
                        <th style="padding-top: 5px;padding-bottom: 5px;">PERTANYAAN</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">STATUS</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">JAM / HARI</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">SELAMA X TAHUN</th>
                        <th style="padding-top: 5px;padding-bottom: 5px;">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($data['riwayat_lingkungan_kerja'] as $item)
                <tr>
                    <td>{{ str_replace('≥', '>=', $item->nama_atribut_saat_ini) }}</td>
                    <td style="text-align: center;">{{ $item->status == "1" ? "Ya" : "Tidak" }}</td>
                    <td style="text-align: center;">{{ $item->nilai_jam_per_hari }}</td>
                    <td style="text-align: center;">{{ $item->nilai_selama_x_tahun }}</td>
                    <td style="text-align: center;">{{ $item->keterangan == "" ? "-" : $item->keterangan }}</td>
                </tr>
                @endforeach
                </tbody>
            </table> 
            <div style="font-weight: bold; text-align:center;margin-top:10px;margin-bottom:0px">PERNYATAAN PERSETUJUAN PEMERIKSAAN KESEHATAN</div>
            <div style="font-size: 13px;margin-top:0px">
                Melalui pengisian formulir MCU (Medical Check Up) secara elektronik maupun tertulis, dengan ini saya menyatakan persetujuan ketentuan sebagai berikut:
                <ol>
                    <li>Seluruh pernyataan yang saya jawab di atas adalah benar dan dapat dipertanggungjawabkan, apabila terdapat ketidaksesuaian dikemudian hari, saya bersedia diberi sanksi sesuai dengan ketentuan perusahaan.</li>
                    <li>Saya menyetujui bahwa hasil pemeriksaan kesehatan yang telah dilakukan dapat disimpan dalam bentuk tertulis (hardcopy) dan elektronik (softcopy) oleh perusahaan.</li>
                    <li>Saya menyetujui dan memberikan kewenangan pada staf kesehatan kerja perusahaan untuk melakukan analisa terkait hasil pemeriksaan kesehatan saya. Hal tersebut terkait kegunaan untuk dievaluasi berkaitan dengan pekerjaan saya di perusahaan ini.</li>
                    <li>Saya menyetujui dan memberikan kewenangan pada staf kesehatan kerja perusahaan untuk memberikan hasil analisa dan evaluasi pemeriksaan terhadap kesehatan saya kepada manajemen perusahaan agar dilakukan tindak lanjut berdasarkan hasil pemeriksaan kondisi fisik dan kesehatan saya.</li>
                </ol>
            </div>
            <div style="font-size: 13px;margin-top:0px">Demikian pernyataan persetujuan ini saya buat dengan sebenar-benarnya dalam keadaan sadar dan tanpa ada paksaan dari pihak manapun.</div>
             <div style="position:absolute;width: 100%;">
                <table style="width: 100%;">
                    <tr>
                       <td style="width: 50%; text-align: left; padding-left: 20px;font-size: 13px;">
                        Tim Dokter Medical Check Up
                            <ol style="margin-top: 0px;">
                                <li>dr. Muhammad Taufiq Amrullah, S.Ked</li>
                                <li>dr. Khadijah Amir, S.Ked</li>
                                <li>dr. Devi Grania Amelia Selekede, Sp.P.</li>
                                <li>dr. Muhammad Asrul. M.Kes Sp.JP(K)</li>
                                <li>dr. Amir. Sp.Rad</li>
                            </ol>
                        </td>
                        <td style="width: 50%; text-align: center; padding-right: 20px; font-size: 13px;">
                        Yang Membuat Pernyataan<br>Sendawar, {{ $data['tanggal_cetak'] }}<br>
                            <img src="data:image/png;base64,{{ $data['qrcode'] }}"><br>
                            <span style="font-weight: bold;"><u>{{ $data['informasi_data_diri']['nama_peserta'] }}</u></span><br>
                            <span style="font-weight: bold;">NIK / NRR : {{ $data['informasi_data_diri']['nomor_identitas'] }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </main>
    </div>

    @php
    if (!function_exists('renderRow')) {
        function renderRow(array $item, int $level = 0, $datadiri = null) {
            $paddingLeft = $level * 2;
            $nilai_rujukan = '';
            $nilai_rujukan_object = [];
            $is_kuantitatif = false;
            $status = 'Nilai Tidak Terdefinisi';
            $semuajeniskelaminFound = false;

            if ($item['meta_data_kuantitatif'] === '[]' && $item['meta_data_kualitatif'] === '[]') {
                $nilai_rujukan_object = "Meta Data Tidak Dikonfigurasi";
                $nilai_rujukan = $nilai_rujukan_object;
            } else {
                if ($item['meta_data_kuantitatif'] !== '[]') {
                    $nilai_rujukan_object = json_decode($item['meta_data_kuantitatif'], true); // Decode JSON as associative array
                    $is_kuantitatif = true;
                } else {
                    $nilai_rujukan_object = json_decode($item['meta_data_kualitatif'], true); // Decode JSON as associative array
                }
            }

            if (is_array($nilai_rujukan_object) && count($nilai_rujukan_object) > 0) {
                foreach ($nilai_rujukan_object as $item_nilai_rujukan) {
                    $gender = strtolower(str_replace(' ', '', explode(" - ", $item_nilai_rujukan['nama_nilai_kenormalan'])[0]));
                    $genderFromData = strtolower(str_replace(' ', '', $datadiri['jenis_kelamin']));

                    if ($item_nilai_rujukan['batas_umur'] == -1) {
                        if ($gender === "semuajeniskelamin") {
                            $semuajeniskelaminFound = true;
                        }

                        if ($is_kuantitatif) {
                            if (($gender === $genderFromData && !$semuajeniskelaminFound) || $gender === "semuajeniskelamin") {
                                $nilaiTindakan = floatval($item['nilai_tindakan']);
                                $batasBawah = floatval($item_nilai_rujukan['batas_bawah']);
                                $batasAtas = floatval($item_nilai_rujukan['batas_atas']);

                                if ($nilaiTindakan >= $batasBawah && $nilaiTindakan <= $batasAtas) {
                                    $status = "<span style='color: green;'>NORMAL</span>";
                                } else {
                                    $status = "<span style='color: red;'>ABNORMAL</span>";
                                }
                            }
                            $nilai_rujukan .= "{$item_nilai_rujukan['nama_nilai_kenormalan']} : {$item_nilai_rujukan['batas_bawah']} {$item_nilai_rujukan['antara']} {$item_nilai_rujukan['batas_atas']}<br>";
                        } else {
                            if (($gender === $genderFromData && !$semuajeniskelaminFound) || $gender === "semuajeniskelamin") {
                                $nilaiTindakan = strtolower(str_replace(' ', '', $item['nilai_tindakan']));
                                $keteranganPositif = strtolower(str_replace(' ', '', $item_nilai_rujukan['keterangan_positif']));
                                $keteranganNegatif = strtolower(str_replace(' ', '', $item_nilai_rujukan['keterangan_negatif']));

                                if ($nilaiTindakan === $keteranganPositif) {
                                    $status = "<span style='color: green;'>NORMAL</span>";
                                } else if ($nilaiTindakan === $keteranganNegatif) {
                                    $status = "<span style='color: red;'>ABNORMAL</span>";
                                } else {
                                    $status = "<span style='color: orange;'>TIDAK TERDEFINISI</span>";
                                }
                            }
                            $nilai_rujukan .= "{$item_nilai_rujukan['nama_nilai_kenormalan']} : (+) <strong>{$item_nilai_rujukan['keterangan_positif']}</strong>, (-) <strong>{$item_nilai_rujukan['keterangan_negatif']}</strong><br>";
                        }
                    }
                }
            }

            $row = "
                <tr>
                    <td style='padding-left: {$paddingLeft}px;'><span style='font-family: DejaVu Sans;'>➤</span>{$item['nama_item']}</td>
                    <td style='text-align: center;'>" . (isset($item['nilai_tindakan']) ? $item['nilai_tindakan'] : 'Nilai Tidak Terdefinisi') . "</td>
                    <td>{$nilai_rujukan}</td>
                    <td style='text-align: center;'>" . (isset($item['satuan']) ? $item['satuan'] : '') . "</td>
                    <td>{$status}</td>
                </tr>
            ";

            // Rekursif panggil renderRow untuk subkategori dan sub-item
            if (isset($item['subkategori']) && is_array($item['subkategori']) && count($item['subkategori']) > 0) {
                foreach ($item['subkategori'] as $subItem) {
                    $row .= renderRow($subItem, $level + 1, $datadiri);
                }
            }

            if (isset($item['sub']) && is_array($item['sub']) && count($item['sub']) > 0) {
                foreach ($item['sub'] as $subItem) {
                    $row .= renderRow($subItem, $level + 1, $datadiri);
                }
            }

            return $row;
        }
    }
    if (!function_exists('hasValidItems')) {
        function hasValidItems(array $kategori): bool {
            if (count($kategori['items']) > 0) return true;
            foreach ($kategori['subkategori'] as $subkategori) {
                if (count($subkategori['items']) > 0 || hasValidItems($subkategori)) {
                    return true;
                }
            }
            return false;
        }
    }
    if (!function_exists('renderKategori')) {
        function renderKategori(array $kategori, int $depth, $datadiri = null) {
            $html = '';
            if (hasValidItems($kategori)) {
                $paddingLeft = $depth * 4;
                $bgColor = '';
                $textColor = '';
                if ($depth == 1) {
                    $paddingLeft = 0;
                    $bgColor = 'green';
                    $textColor = 'white';
                }
                $prefix = ($depth > 1 && (count($kategori['items']) > 0 && count($kategori['subkategori']) > 0)) ? '➤' : '';
                $html .= "
                    <tr style='margin-left: 100px; margin-right: 100px;'>
                        <td colspan='5' style='padding-left: {$paddingLeft}px; background-color: {$bgColor}; color: {$textColor};'>{$kategori['nama_kategori']}</td>
                    </tr>
                ";

                if (isset($kategori['items']) && count($kategori['items']) > 0) {
                    foreach ($kategori['items'] as $item) {
                        $html .= renderRow($item, $depth, $datadiri);
                    }
                }

                if (isset($kategori['subkategori']) && count($kategori['subkategori']) > 0) {
                    foreach ($kategori['subkategori'] as $subkategori) {
                        $html .= renderKategori($subkategori, $depth + 1, $datadiri);
                    }
                }
            }
            return $html;
        }
    }
    @endphp
    <div class="break-before section">
    <main>
        <div style="page-break-after: always;">
            @php header_mcu($data); @endphp
            <h3 style="text-align: left; background-image: url({{ asset('mofi/assets/images/logo/gradient_bg_title.png') }}); background-size: cover; background-repeat: no-repeat; color: #fff; padding: 10px; border-bottom-right-radius: 10px; border-top-right-radius: 10px; display: inline-block;">PEMERIKSAAN KONDISI FISIK</h3><br>
            <h4 class="judul_kondisi_fisik">TINGKAT KESADARAN</h4>
            <table style="width: 100%;font-size: 14px;">
                <tr>
                    <td style="padding-left:5px;padding-top:10px;padding-bottom:10px;border: 1px solid black; width: 33%;">Keadaan Umum : {{ ucfirst($data['tingkat_kesadaran']->nama_atribut_tingkat_kesadaran) }}</td>
                    <td style="padding-left:5px;padding-top:10px;padding-bottom:10px;border: 1px solid black; width: 33%;">Status Kesanaran : {{ ucfirst($data['tingkat_kesadaran']->nama_atribut_status_tingkat_kesadaran) }}</td>
                    <td style="padding-left:5px;padding-top:10px;padding-bottom:10px;border: 1px solid black; width: 34%;">Keluahan : {{ $data['tingkat_kesadaran']->keluhan == "" ? "-" : $data['tingkat_kesadaran']->keluhan }}</td>
                </tr>
                <tr>
                    <td colspan="3">Keterangan : {!! $data['tingkat_kesadaran']->keterangan_status_tingkat_kesadaran !!}</td>
                </tr>
            </table>
            <h4 class="judul_kondisi_fisik" style="margin-top: 10px;">TANDA VITAL DAN GIZI</h4>
            @php
                $items = collect($data['tanda_vital'])->values();

                // Hitung BMI
                $BB = 0;
                $TB = 0;
                foreach ($items as $item) {
                    $name = strtolower(str_replace(' ', '', $item->nama_atribut_saat_ini));
                    if ($name === 'beratbadan') {
                        $BB = $item->nilai_tanda_vital;
                    } elseif ($name === 'tinggibadan') {
                        $TB = $item->nilai_tanda_vital / 100;
                    }
                }

                $BMI = 0;
                $status_gizi = "-";
                $color = "black";
                if ($TB > 0) {
                    $BMI = $BB / ($TB * $TB);
                    $BMI_formatted = number_format(ceil($BMI * 100) / 100, 2);
                    if ($BMI < 18.5) {
                        $status_gizi = "KEKURANGAN BERAT BADAN";
                        $color = "orange";
                    } elseif ($BMI >= 18.5 && $BMI <= 24.9) {
                        $status_gizi = "NORMAL";
                        $color = "green";
                    } elseif ($BMI >= 25 && $BMI <= 29.9) {
                        $status_gizi = "KELEBIHAN BERAT BADAN";
                        $color = "red";
                    } else {
                        $status_gizi = "OBESITAS";
                        $color = "darkred";
                    }

                    // Tambahkan item BMI dan Status Gizi
                    $items->push((object)[
                        'nama_atribut_saat_ini' => 'BMI',
                        'nilai_tanda_vital' => $BMI_formatted,
                        'satuan_tanda_vital' => 'IMT',
                    ]);

                    $items->push((object)[
                        'nama_atribut_saat_ini' => 'Status Gizi',
                        'nilai_tanda_vital' => '',
                        'satuan_tanda_vital' => '',
                        'keterangan' => "<span style='color: {$color}; font-weight:bold;'>{$status_gizi}</span>",
                    ]);
                }

                $total = $items->count();
                $columns = 3;
                $rows = ceil($total / $columns);
            @endphp
            <table style="width: 100%; font-size: 12px;">
                <tr>
                    @for ($col = 0; $col < $columns; $col++)
                        <td valign="top">
                            <table style="width: 100%;">
                                @for ($row = 0; $row < $rows; $row++)
                                @php
                                    $index = $row + ($col * $rows);
                                @endphp
                                @if ($index < $total)
                                    @php
                                        $item = $items[$index];
                                        $value = $item->nilai_tanda_vital ?? '';
                                        $satuan = $item->satuan_tanda_vital ?? '';
                                        $label = $item->nama_atribut_saat_ini;
                                    @endphp
                                    <tr>
                                        <td style="padding-left:5px;padding-top:2px;padding-bottom:2px;border: 1px solid black;">
                                            {!! $label !!} : {!! $value !!} {!! $satuan !!}
                                            @if (isset($item->keterangan))
                                                {!! $item->keterangan !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endfor
                        </table>
                    </td>
                @endfor
                </tr>
            </table>
            <h4 class="judul_kondisi_fisik">PENGLIHATAN</h4>
            <table class="lapang_pandang_table" style="width: 100%;font-size: 13px;border: 1px solid black;">
                <tr style="text-align: center; background-color: #2c942a;font-weight: bold;color: #fff;font-size: 13px;">
                    <th colspan="5">VISUS</th>
                    <th rowspan="3" style="vertical-align: middle;text-align: center;">Tes Buta Warna</th>
                </tr>
                <tr style="text-align: center; background-color: #2c942a;font-weight: bold;color: #fff;font-size: 13px;">
                    <th rowspan="2" style="text-align: center;vertical-align: middle;">Status</th>
                    <th colspan="2">Tanpa Kacamata</th>
                    <th colspan="2">Dengan Kacamata</th>
                </tr>
                <tr style="text-align: center; background-color: #2c942a;font-weight: bold;color: #fff;font-size: 13px;">
                    <th>OS</th>
                    <th>OD</th>
                    <th>OS</th>
                    <th>OD</th>
                </tr>
                <tr style="text-align: center;">
                    <th style="text-align: center;">Jauh</th>
                    <td>{{ $data['penglihatan'][0]->visus_os_tanpa_kacamata_jauh }}</td>
                    <td>{{ $data['penglihatan'][0]->visus_od_tanpa_kacamata_jauh }}</td>
                    <td>{{ $data['penglihatan'][0]->visus_os_kacamata_jauh }}</td>
                    <td>{{ $data['penglihatan'][0]->visus_od_kacamata_jauh }}</td>
                    <td rowspan="2" style="vertical-align: middle;text-align: center;">
                        {{ strtoupper(str_replace('_', ' ', $data['penglihatan'][0]->buta_warna)) }}
                    </td>
                </tr>
                <tr style="text-align: center;">
                    <th style="text-align: center;">Dekat</th>
                    <td>{{ $data['penglihatan'][0]->visus_os_tanpa_kacamata_dekat }}</td>
                    <td>{{ $data['penglihatan'][0]->visus_od_tanpa_kacamata_dekat }}</td>
                    <td>{{ $data['penglihatan'][0]->visus_os_kacamata_dekat }}</td>
                    <td>{{ $data['penglihatan'][0]->visus_od_kacamata_dekat }}</td>
                </tr>
            </table>
            <table class="lapang_pandang_table" style="width: 100%;font-size: 13px;border: 1px solid black;">
                <tr style="text-align: center; background-color: #2c942a;font-weight: bold;color: #fff;font-size: 13px;">
                    <th rowspan="2" style="text-align: center;vertical-align: middle;">Posisi Mata</th>
                    <th colspan="7" class="sub-header" style="text-align: center;">LAPANG PANDANG</th>
                </tr>
                <tr style="text-align: center; background-color: #2c942a;font-weight: bold;color: #fff;font-size: 13px;">
                    <th style="max-width: 100px;width: 100px;">Superior</th>
                    <th style="max-width: 100px;width: 100px;">Inferior</th>
                    <th style="max-width: 100px;width: 100px;">Temporal</th>
                    <th style="max-width: 100px;width: 100px;">Nasal</th>
                    <th colspan="3">Keterangan</th>
                </tr>
                <tr>
                    <td>Mata Kiri</td>
                    <td style="text-align: center;">{{ $data['penglihatan'][0]->lapang_pandang_superior_os }}</td>
                    <td style="text-align: center;">{{ $data['penglihatan'][0]->lapang_pandang_inferior_os }}</td>
                    <td style="text-align: center;">{{ $data['penglihatan'][0]->lapang_pandang_temporal_os }}</td>
                    <td style="text-align: center;">{{ $data['penglihatan'][0]->lapang_pandang_nasal_os }}</td>
                    <td colspan="3" class="normal">{{ $data['penglihatan'][0]->lapang_pandang_keterangan_os }}</td>
                </tr>
                <tr>
                    <td>Mata Kanan</td>
                    <td style="text-align: center;">{{ $data['penglihatan'][0]->lapang_pandang_superior_od }}</td>
                    <td style="text-align: center;">{{ $data['penglihatan'][0]->lapang_pandang_inferior_od }}</td>
                    <td style="text-align: center;">{{ $data['penglihatan'][0]->lapang_pandang_temporal_od }}</td>
                    <td style="text-align: center;">{{ $data['penglihatan'][0]->lapang_pandang_nasal_od }}</td>
                    <td colspan="3" class="normal">{{ $data['penglihatan'][0]->lapang_pandang_keterangan_od }}</td>
                </tr>
            </table>
            <h4 class="judul_kondisi_fisik" style="margin-top: 10px;">KONDISI FISIK</h4>
            @php
                $groupedData = [];
                foreach ($data['kondisi_fisik'] as $item) {
                    if (!isset($groupedData[$item->kategori])) {
                        $groupedData[$item->kategori] = [];
                    }
                    $groupedData[$item->kategori][] = $item;
                }
            @endphp

            <table style="width: 100%; font-size: 13px; border-spacing: 0;">
                <thead>
                    <tr style="text-align: center; font-weight: bold; color: #fff;">
                        <th style="background-color: green; width: 200px; border: 1px solid black; padding-top: 10px;">PEMERIKSAAN</th>
                        <th style="width: 2px;"></th>
                        <th style="background-color: green; width: 180px; border: 1px solid black; padding-left: 5px; padding-top: 10px;">JENIS PEMERIKSAAN</th>
                        <th style="width: 2px;"></th>
                        <th style="background-color: green; width: 50px; border: 1px solid black; padding-top: 10px;">AB</th>
                        <th style="width: 2px;"></th>
                        <th style="background-color: green; width: 50px; border: 1px solid black; padding-top: 10px;">N</th>
                        <th style="width: 2px;"></th>
                        <th style="background-color: green; border: 1px solid black; padding-top: 10px;">KETERANGAN</th>
                    </tr>
                </thead>
            <tbody>
                @php
                    $rowCount = 0;
                @endphp

                @foreach ($groupedData as $kategori => $items)

                    {{-- Spacer antar kategori --}}
                    <tr><td colspan="9" style="height: 1px;"></td></tr>

                    @foreach ($items as $index => $item)
                        @php
                            $rowCount++;
                        @endphp

                        <tr>
                            {{-- Kolom PEMERIKSAAN --}}
                            @if ($loop->first)
                                <td style="text-align: center; font-weight: bold;
                                    border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; border-bottom: none;
                                    padding: 5px;">
                                    {{ strtoupper(str_replace('_', ' ', $kategori)) }}
                                </td>
                            @elseif ($loop->last)
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-top: none;">
                                    &nbsp;
                                </td>
                            @else
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-top: none; border-bottom: none;">
                                    &nbsp;
                                </td>
                            @endif
                            <th style="width: 2px;"></th>
                            {{-- JENIS PEMERIKSAAN --}}
                            <td style="
                                padding: 5px 10px;
                                border-left: 1px solid black;
                                border-right: 1px solid black;
                                @if ($loop->first)
                                    border-top: 1px solid black;
                                    border-bottom: none;
                                @elseif ($loop->last)
                                    border-top: none;
                                    border-bottom: 1px solid black;
                                @else
                                    border-top: none;
                                    border-bottom: none;
                                @endif
                            ">
                                &#8226; {{ $item->jenis_atribut }}
                            </td>
                            <th style="width: 2px;"></th>
                            {{-- Kolom AB --}}
                            <td style="text-align: center; padding: 4px;">
                                <input type="checkbox"
                                    {{ $item->status_atribut === 'abnormal' ? 'checked' : '' }}
                                    disabled
                                    style="transform: scale(1.3); transform-origin: center; background-color: transparent;">
                            </td>
                            <th style="width: 2px;"></th>
                            {{-- Kolom N --}}
                            <td style="text-align: center; padding: 4px;">
                                <input type="checkbox"
                                    {{ $item->status_atribut === 'normal' ? 'checked' : '' }}
                                    disabled
                                    style="transform: scale(1.3); transform-origin: center; background-color: transparent;">
                            </td>
                            <th style="width: 2px;"></th>
                            {{-- Keterangan --}}
                            <td style="
                                text-align: center;
                                padding: 5px 10px;
                                border-left: 1px solid black;
                                border-right: 1px solid black;
                                @if ($loop->first)
                                    border-top: 1px solid black;
                                    border-bottom: none;
                                @elseif ($loop->last)
                                    border-top: none;
                                    border-bottom: 1px solid black;
                                @else
                                    border-top: none;
                                    border-bottom: none;
                                @endif
                            ">
                                @if ($item->nama_atribut == 'Neurologis')
                                    {{ $item->keterangan_atribut ?? 'Negatif' }}
                                @else
                                    {{ $item->keterangan_atribut ?? 'Normal' }}
                                @endif
                            </td>
                        </tr>
                        @if (in_array($rowCount, [13,48]))
                            <tr><th colspan="9" style="height: 0;border-bottom: 2px solid black;padding: 0;"></th></tr>
                        @endif

                    @endforeach
                @endforeach
            </tbody>
            <!--<tr style="text-align: center; font-weight: bold; color: #fff;">
                <th style="background-color: green; border: 1px solid black;">PEMERIKSAAN</th>
                <th style="width: 2px;"></th>
                <th style="background-color: green; border: 1px solid black;">JENIS PEMERIKSAAN</th>
                <th style="width: 2px;"></th>
                <th style="background-color: green; border: 1px solid black;">AB</th>
                <th style="width: 2px;"></th>
                <th style="background-color: green; border: 1px solid black;">N</th>
                <th style="width: 2px;"></th>
                <th style="background-color: green; border: 1px solid black;">KETERANGAN</th>
            </tr>-->
            <tr><th colspan="9" style="height: 0;border-bottom: 2px solid black;padding: 0;"></th></tr>
            </table>
            <div style="position: absolute;width: 100%;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%; text-align: center; padding-left: 20px;font-size: 13px;">
                    Pindai untuk periksa keaslian dokumen<br>
                    Dokumen ini tervalidasi dan dicetak secara otomatis<br>
                    <img src="data:image/png;base64,{{ $data['qrcode'] }}">
                    </td>
                    <td style="width: 50%; text-align: center; padding-right: 20px; font-size: 13px;">
                    Mengetahui<br>Sendawar, {{ $data['tanggal_cetak'] }}<br>
                        <img src="data:image/png;base64,{{ $data['qrcode'] }}"><br>
                        <span style="font-weight: bold;"><u>dr. Muhammad Taufiq Amrullah, S.Ked</u></span><br>
                        <span style="font-weight: bold;">440.007.2/127/SIP-DINKES/XI/2023</span>
                    </td>
                </tr>
            </table>
            </div>
        </div>
    </main>
    </div>
    <div class="break-before section">
    <main>
    @if ($data['ada_lampiran_laboratorium_pdf'] != 'NOLAB')
        <div style="page-break-after: always;">
        @if ($data['ada_lampiran_laboratorium_pdf'] == 0)
            @php header_mcu($data); @endphp
            <h3 style="text-align: left; background-color: orange; color: #fff; padding: 10px; border-bottom-right-radius: 10px; border-top-right-radius: 10px; display: inline-block;">HASIL LABORATORIUM</h3>
            <table style="width: 100%;font-size: 13px;border: 1px solid black;">
                <thead>
                    <tr style="text-align: center; background-color: #2c942a;font-weight: bold;color: #fff;font-size: 15px;">
                        <th>PARAMETER</th>
                        <th>HASIL</th>
                        <th>NILAI RUJUKAN</th>
                        <th>SATUAN</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['laboratorium'] as $kategori)
                        {!! renderKategori($kategori, 1, $data['informasi_data_diri']) !!}
                    @endforeach
                </tbody>
            </table>
            <div style="position: absolute;width: 100%;">
                <table style="width: 100%;">
                    <tr>
                    <td style="width: 50%; text-align: center; padding-left: 20px;font-size: 13px;">
                        Pindai untuk periksa keaslian dokumen<br>
                        Dokumen ini tervalidasi dan dicetak secara otomatis<br>
                        <img src="data:image/png;base64,{{ $data['qrcode'] }}">
                        </td>
                        <td style="width: 50%; text-align: center; padding-right: 20px; font-size: 13px;">
                        Mengetahui<br>Sendawar, {{ $data['tanggal_cetak'] }}<br>
                            <img src="data:image/png;base64,{{ $data['qrcode'] }}"><br>
                            <span style="font-weight: bold;"><u>dr. Muhammad Taufiq Amrullah, S.Ked</u></span><br>
                            <span style="font-weight: bold;">440.007.2/127/SIP-DINKES/XI/2023</span>
                        </td>
                    </tr>
                </table>
            </div>
            </div>
        @else
            @foreach ($data['lampiran_berkas_pdf'] as $item)
                @if ($item->height > $item->width)
                    <div style="text-align: center;">
                        <img src="{{ $item->data_foto }}" style="width: auto;height:100%;">
                    </div>
                @else
                    <div style="text-align: center;">
                        <img src="{{ $item->data_foto }}" style="width: 100%;">
                    </div>
                @endif
            @endforeach
        @endif
    @endif
    </main>
    </div>

    <div class="break-before section">
    <main>
        @forelse ($data['all_citra_data']->groupBy('jenis_poli') as $jenis_poli => $dataPoli)
            <div style="page-break-after: always;">
                @php header_mcu($data); @endphp
                <h5 style="text-align: left;
                    background-image: url({{ asset('mofi/assets/images/logo/gradient_bg_title.png') }});
                    background-size: cover;
                    background-repeat: no-repeat;
                    color: #fff;
                    padding: 10px;
                    border-bottom-right-radius: 10px;
                    border-top-right-radius: 10px;
                    display: inline-block;">
                    HASIL {{ strtoupper(str_replace('_', ' ', $jenis_poli)) }}
                </h5>
                @foreach ($dataPoli as $item)
                    <div style="text-align: center;">
                        <img src="{{ $item->data_foto }}"
                             style="{{ $item->height > $item->width ? 'width:auto;height:100%;' : 'width:100%;' }}">
                    </div>
                @endforeach
                @php $firstItem = $dataPoli->first(); @endphp
                <div style="page-break-before: always; text-align: center; font-weight: bold; font-size: 20px;">
                    @php header_mcu($data); @endphp
                    INTERPRETASI HASIL {{ strtoupper(str_replace('_', ' ', $jenis_poli)) }}
                </div>
                <table style="width: 100%;">
                    <tr>
                        <td colspan="2" style="padding-left:20px;font-size:14px;">
                            {!! $firstItem->kesimpulan_citra_spirometri !!}
                        </td>
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
                    <tr>
                        <td style="padding-left:20px;font-size:14px;">Catatan Kaki</td>
                        <td style="font-size:14px;">{{ $firstItem->catatan_kaki ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left:20px;font-size:14px;">Kesimpulan</td>
                        <td style="font-size:14px;">{{ $firstItem->kesimpulan ?? '-' }}</td>
                    </tr>
                </table>
                <div style="position:absolute;width:100%;">
                    <table style="width:100%;">
                        <tr>
                            <td style="width:50%;text-align:center;font-size:13px;">
                                Petugas {{ ucwords(str_replace('_', ' ', $jenis_poli)) }}<br>
                                Sendawar, {{ $data['tanggal_cetak'] }}<br>
                                <img src="data:image/png;base64,{{ $data['qrcode'] }}"><br>
                                <b><u>{{ $firstItem->nama_petugas }}</u></b><br>
                                <b>{{ $firstItem->departemen_petugas }}</b>
                            </td>
                            <td style="width:50%;text-align:center;font-size:13px;">
                                Mengetahui<br>
                                Sendawar, {{ $data['tanggal_cetak'] }}<br>
                                <img src="data:image/png;base64,{{ $data['qrcode'] }}"><br>
                                <b><u>{{ $firstItem->nama_pegawai }}</u></b><br>
                                <b>{{ $firstItem->departemen }}</b>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        @empty
        @endforelse
    </main>
    </div>
    <div class="break-before section" style="margin:0;padding:0;">
    <main>
        <div style="page-break-after: auto;"></div>
    </main>
    </div>
</body>
</html>