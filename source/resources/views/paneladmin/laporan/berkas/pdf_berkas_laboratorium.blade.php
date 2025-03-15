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
    background-color: red; 
    color: #fff;
    padding: 10px;
    border-bottom-right-radius: 10px; 
    border-top-right-radius: 10px; 
    display: inline-block;
    margin-bottom: 5px;
}
.judul_kondisi_fisik {
    margin-top: 0px;
    text-align: left; 
    background-color: red; 
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
    pointer-events: none;
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
  width: 100px;
  height: 20px;
  margin-right: 8px;
  vertical-align: middle;
}
.sehat-resiko-ringan {
  background-color: yellow;
}
.sehat {
  background-color: lightgreen;
}
.resiko-sedang-tinggi {
  background-color: orange;
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
                        <img src="{{ asset('mofi/assets/images/logo/ISO91001400.png') }}" alt="Logo ISO" style="height: 50px;">
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
                    <td>{{ date('d-m-Y', strtotime($data['informasi_data_diri']['tanggal_mcu'])) }} / {{ $data['informasi_data_diri']['jenis_transaksi_pendaftaran'] }}</td>
                </tr>
            </table>
        </div>
    </main>
    </div>
    @php
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
                    <td colspan='6' style='padding-left: {$paddingLeft}px; background-color: {$bgColor}; color: {$textColor};'>{$kategori['nama_kategori']}</td>
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
                                $status = "<span style='color: green;'>N</span>";
                            } else {
                                $status = "<span style='color: red;'>AB</span>";
                            }
                        }
                        $nilai_rujukan .= "{$item_nilai_rujukan['nama_nilai_kenormalan']} : {$item_nilai_rujukan['batas_bawah']} {$item_nilai_rujukan['antara']} {$item_nilai_rujukan['batas_atas']}<br>";
                    } else {
                        if (($gender === $genderFromData && !$semuajeniskelaminFound) || $gender === "semuajeniskelamin") {
                            $nilaiTindakan = strtolower(str_replace(' ', '', $item['nilai_tindakan']));
                            $keteranganPositif = strtolower(str_replace(' ', '', $item_nilai_rujukan['keterangan_positif']));
                            $keteranganNegatif = strtolower(str_replace(' ', '', $item_nilai_rujukan['keterangan_negatif']));

                            if ($nilaiTindakan === $keteranganPositif) {
                                $status = "<span style='color: green;'>N</span>";
                            } else if ($nilaiTindakan === $keteranganNegatif) {
                                $status = "<span style='color: red;'>AB</span>";
                            } else {
                                $status = "<span style='color: orange;'>TT</span>";
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
                <td style='text-align: left;'>" . (isset($item['metode_tindakan']) ? $item['metode_tindakan'] : '') . "</td>
                <td style='text-align: center;'>{$status}</td>
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
    @endphp
    <div class="break-before section">
    <main>
        <div style="page-break-after: always;">
        @php header_mcu($data); @endphp
        <h3 style="text-align: left; background-color: orange; color: #fff; padding: 10px; border-bottom-right-radius: 10px; border-top-right-radius: 10px; display: inline-block;">HASIL LABORATORIUM</h3>
        <table style="width: 100%;font-size: 13px;border: 1px solid black;">
            <thead>
                <tr style="text-align: center; background-color: green;font-weight: bold;color: #fff;font-size: 15px;">
                    <th>PARAMETER</th>
                    <th>HASIL</th>
                    <th>NILAI RUJUKAN</th>
                    <th>SATUAN</th>
                    <th>METODE</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['laboratorium'] as $kategori): ?>
                    <?php echo renderKategori($kategori, 1, $data['informasi_data_diri']); ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <span style="font-size: 13px;font-weight: bold;">KETERANGAN</span>
        <ul style="margin-top: 0px;font-size: 13px;">
            <li>N = NORMAL</li>
            <li>AB = ABNORMAL</li>
            <li>TT = TIDAK TERDEFINISI</li>
        </ul>
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

    <div class="break-before section" style="margin:0;padding:0;">
    <main>
        <div style="page-break-after: auto;"></div>
    </main>
    </div>
</body></html>