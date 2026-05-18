<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Cetak Kuitansi Perusahaan {{ $data['nama_perusahaan'] }}</title>
<style>
@page { 
    margin:0;
}
.header-table, .info-table {
    width: 100%;
    border-collapse: collapse;
}
.header-table td {
    vertical-align: top;
}
.info-table td {
    padding: 4px 6px;
}
table td, table th {
    padding: 2px 5px;
}
</style>
</head>
<body>
@php
    function renderHeader($title = 'INVOICE') {
        return '
        <div class="header">
            <img src="'.asset('mofi/assets/images/logo/border_hasil_mcu_atas.png').'" alt="Border Hasil MCU" style="position: absolute;top: 0;right: 0;width: 100%;z-index: -1;opacity: 0.6;">
            <table style="width: 100%;padding-right: 25px;">
                <tr>
                    <td style="width:30%; vertical-align: center;">
                        <img src="'.asset('mofi/assets/images/logo/Logo_AMC_Full.png').'" alt="Logo AMC" style="width: 100%;padding-top: 20px;">
                    </td>
                    <td style="width:70%; text-align: right;">
                        <p>
                            <span style="font-size: 25px; font-weight: bold;">KLINIK ARTHA MEDICAL CENTRE</span><br>
                            <span style="font-size: 15px;">Alamat: Jl. Sendawar Raya RT 029 Kel. Melak Ulu Kec. Melak, Kutai Barat 75765</span><br>
                            <span style="font-size: 15px;">E-Mail: amc.clinic.yhs@gmail.com | website: arthamedicalcentre.com</span><br>
                            <span style="font-size: 15px;">Contact Person: 0823-4402-9902</span><br>
                            <span style="font-size: 20px;"><strong>'.$title.'</strong></span>
                        </p>
                    </td>
                </tr>
            </table>
            <hr style="border: none; border-top: 4px double #000; height: 0; margin: 0px 0;">
        </div>
        ';
    }
@endphp

    <main>
        {!! renderHeader() !!}
        <table class="header-table" style="width: 80%; margin: 10px auto;">
            <tr>
                <td>
                    Kepada Yth :<br>
                    {{ $data['nama_perusahaan'] }}<br>
                    Cq. FINANCE DEPARTMENT<br>
                    Di Tempat
                </td>
                <td style="text-align: right;">
                    Tanggal : {{ $data['tanggal_cetak'] }}<br>
                    Nomor : {{ $data['nomor_surat'] }}
                </td>
            </tr>
        </table>
        <table style="width: 80%; margin: 10px auto;border-collapse: collapse;" cellpadding="0" cellspacing="0">
            <thead>
            <tr style="background-color: #C65911;color: white;text-align: center;">
                <td style="border: 1px solid #000;">NO</td>
                <td style="border: 1px solid #000;">JENIS PEMERIKSAAN</td>
                <td style="border: 1px solid #000;">QTY</td>
                <td style="border: 1px solid #000;">HARGA</td>
                <td style="border: 1px solid #000;">JUMLAH</td>
            </tr>
            </thead>
           <tbody>
                @php
                    $grouped = collect($data['inv_resume_mcu_peserta'])->groupBy(function ($item) {
                        return $item->jenis_transaksi_pendaftaran . '|' . $item->total_transaksi;
                    });
                    $subTotalQty = 0;
                    $grandTotal = 0;
                @endphp

                @foreach ($grouped as $group)
                    @php
                        $first = $group->first();
                        $qty = $group->count();
                        $harga = $first->total_transaksi;
                        $jumlah = $qty * $harga;
                        $subTotalQty += $qty;
                        $grandTotal += $jumlah;
                    @endphp
                    <tr style="font-weight: bold;">
                        <td style="border:1px solid #000; text-align:center;">{{ $loop->iteration }}</td>
                        <td style="border:1px solid #000;">{{ str_replace('_', ' ', $first->jenis_transaksi_pendaftaran) }}</td>
                        <td style="border:1px solid #000; text-align:center;">{{ number_format($qty, 0, '.', '.') }}</td>
                        <td style="border:1px solid #000; text-align:right;">
                            <span style="float:left;">Rp</span>
                            <span style="float:right;">
                                {{ number_format($harga, 0, '.', '.') }}
                            </span>
                            <div style="clear: both;"></div>
                        </td>
                        <td style="border:1px solid #000; text-align:right;">
                            <span style="float:left;">Rp</span>
                            <span style="float:right;">
                                {{ number_format($jumlah, 0, '.', '.') }}
                            </span>
                            <div style="clear: both;"></div>
                        </td>
                    </tr>
                @endforeach
                {{-- SUB TOTAL --}}
                <tr style="font-weight:bold; background:#F8CBAD;">
                    <td colspan="2" style="border:1px solid #000; text-align:right;">
                        TOTAL
                    </td>
                    <td style="border:1px solid #000; text-align:center;">
                        {{ number_format($subTotalQty, 0, '.', '.') }}
                    </td>
                    <td style="border:1px solid #000;"></td>
                    <td style="border:1px solid #000; text-align:right;">
                        <span style="float: left;">Rp</span>
                        <span style="float: right;">{{ number_format($grandTotal, 0, '.', '.') }}</span>
                        <div style="clear: both;"></div>
                    </td>
                </tr>
                <tr style="height:10px;"><td colspan="5" style="border:none;"></td></tr>
                <tr style="font-weight:bold; background:#F8CBAD;">
                    <td colspan="5" style="border:1px solid #000; text-align:center;">
                        <span style="text-align: center;"><i>Terbilang : {{ ucwords(\App\Helpers\GlobalHelper::terbilang($grandTotal))." Rupiah" }}</i></span>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="margin-top: 10px;padding-left: 80px;padding-right: 80px">
            Pembayaran dapat dilakukan secara transfer pada :<br>
            Bank Mandiri 14800-8289-2988, Atas nama PT. ARTHA ROYAL NUSANTARA<br>
            Mohon bukti di email ke : <span style="font-weight: bold;color:blue"><u>amc.clinic.acc@gmail.com</u></span> CC <span style="font-weight: bold;color:blue"><u>amc.clinic.yhs@gmail.com</u></span>
        </div>
        <table style="width: 80%; margin: 10px auto;border-collapse: collapse;" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 50%; text-align: center; padding-left: 20px;font-size: 13px;">
                {{-- Pindai untuk periksa keaslian kuitansi<br>
                Kuitansi ini tervalidasi dan dicetak secara otomatis<br>
                <img src="data:image/png;base64,{{ $data['qrcode_no_nota'] }}"> --}}
                </td>
                <td style="width: 50%; text-align: center; padding-right: 20px; font-size: 13px;">
                Mengetahui<br>Sendawar, {{ $data['tanggal_cetak'] }}<br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <span style="font-weight: bold;"><u>{{ $data['atas_nama_nota'] }}</u></span><br>
                    <span style="font-weight: bold;">{{ $data['nip'] }}</span>
                </td>
            </tr>
        </table>
    </main>
    <!-- <div style="page-break-after: always;"></div> -->
    <!-- <main>
        <div style="width: 80%; max-width: 800px; margin: 10% auto; background-color: white; border: 3px solid #000; font-family: Arial, sans-serif;">
            <table style="width: 100%; border-bottom: 3px solid #000; border-collapse: collapse;">
                <tr>
                    <td style="width: 30%; text-align: center; vertical-align: middle;">
                        <img src="{{ asset('mofi/assets/images/logo/Logo_AMC_Full.png') }}" alt="Cover AMC" style="width: 100%;">
                    </td>
                    <td style="width: 70%; padding: 20px; text-align: center; vertical-align: middle;">
                        <span style="font-size: 24px; font-weight: bold; margin-bottom: 8px;">KLINIK ARTHA MEDICAL CENTRE</span><br>
                        <span style="font-size: 12px; margin-bottom: 4px;">JL Sendawar Raya RT. 029 Kelurahan Melak Ulu</span><br>
                        <span style="font-size: 12px; margin-bottom: 4px;">Kec. Melak - Kutai Barat , Telp. 0813 48636 100</span><br>
                        <span style="font-size: 12px; color: #0066cc;">amc.clinic.acc@gmail.com</span>
                    </td>
                </tr>
            </table>
            <div style="text-align: center; font-size: 20px; font-weight: bold; padding: 20px;">
                KWITANSI PEMBAYARAN / BUKTI PEMBAYARAN
            </div>
            <div style="padding: 30px 40px;">
                <table style="width: 100%; margin-bottom: 25px; border-collapse: collapse;">
                    <tr>
                        <td style="width: 120px; font-size: 14px; font-style: italic; vertical-align: middle;">No Kwitansi</td>
                        <td style="width: 20px; text-align: center; vertical-align: middle;">:</td>
                        <td style="vertical-align: middle;">
                            @php
                            $parts = explode('/', $data['nomor_surat']);
                            @endphp
                            <div style="border: 2px solid #000; padding: 10px 20px; font-size: 14px; font-weight: bold; background-color: #f9f9f9;">{{ $parts[0] }}</div>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; margin-bottom: 25px; border-collapse: collapse;">
                    <tr>
                        <td style="width: 120px; font-size: 14px; font-style: italic; vertical-align: middle;">Sudah Terima Dari</td>
                        <td style="width: 20px; text-align: center; vertical-align: middle;">:</td>
                        <td style="vertical-align: middle;">
                            <div style="border: 2px solid #000; padding: 10px 20px; font-size: 14px; font-weight: bold; background-color: #f9f9f9;">{{ $data['nama_perusahaan'] }}</div>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; margin-bottom: 25px; border-collapse: collapse;">
                    <tr>
                        <td style="width: 120px; font-size: 14px; font-style: italic; vertical-align: middle;">Banyaknya Uang</td>
                        <td style="width: 20px; text-align: center; vertical-align: middle;">:</td>
                        <td style="vertical-align: middle;">
                            <div style="border: 2px solid #000; padding: 10px 20px; font-size: 14px; font-weight: bold; background-color: #f9f9f9;">{{ ucwords(\App\Helpers\GlobalHelper::terbilang($grandTotal))." Rupiah" }}</div>
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; margin-bottom: 25px; border-collapse: collapse;">
                    <tr>
                        <td style="width: 120px; font-size: 14px; font-style: italic; vertical-align: middle;">Untuk Pembayaran</td>
                        <td style="width: 20px; text-align: center; vertical-align: middle;">:</td>
                        <td style="vertical-align: middle; font-size: 14px; font-style: italic; padding: 10px 20px;">
                            @foreach (collect($data['inv_resume_mcu_peserta'])->unique('jenis_transaksi_pendaftaran') as $item)
                                @php
                                    $text = $item->jenis_transaksi_pendaftaran == "MCU" ? "Medical Check Up" : $item->jenis_transaksi_pendaftaran;
                                    $cleanText = str_replace('_', ' ', $text);
                                @endphp
                                {{ $cleanText }}@if (!$loop->last), @endif
                            @endforeach
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; margin-top: 40px; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; vertical-align: center;">
                            <table style="border-collapse: collapse;">
                                <tr>
                                    <td style="width: 120px; font-size: 14px; font-style: italic; vertical-align: middle;">Jumlah </td>
                                    <td style="width: 20px; text-align: center; vertical-align: middle;">:</td>
                                    <td style="vertical-align: middle;">
                                        <div style="border: 2px solid #000; padding: 8px 20px; font-size: 16px; font-weight: bold; white-space: nowrap;">{{number_format($grandTotal, 0, ',', '.')}}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 50%; text-align: center; vertical-align: center;">
                            <span style="font-size: 14px; margin-bottom: 10px;">Sendawar, {{ $data['tanggal_cetak'] }}</span>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <span style="font-size: 14px;font-weight: bold;"><u>{{ $data['atas_nama_nota'] }}</u></span><br>
                            <span style="font-size: 14px;font-weight: bold;">{{ $data['nip'] }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </main> -->
@php
    $groupedData = collect($data['inv_resume_mcu_peserta'])->groupBy('jenis_transaksi_pendaftaran');
@endphp

@foreach ($groupedData as $jenisTransaksi => $pesertaList)
    <div style="page-break-after: always;"></div>
    <main>
        {!! renderHeader() !!}
        <div style="text-align:center;padding-top:10px;padding-bottom:10px;font-weight:bold">
            DAFTAR NAMA<br>
            KARYAWAN {{ $data['nama_perusahaan'] }}<br>
            @php
                // Ubah _ jadi spasi dan handle MCU
                $title = $jenisTransaksi == "MCU" ? "MEDICAL CHECK UP" : strtoupper($jenisTransaksi);
                $cleanTitle = str_replace('_', ' ', $title);
            @endphp
            {{ $cleanTitle }}
        </div>

        <table style="width: 95%; margin: 10px auto;border-collapse: collapse;" cellpadding="0" cellspacing="0">
            <thead>
                <tr style="background-color: #C0C0C0;color: black;text-align: center;">
                    <td style="border: 1px solid #000;font-weight:bold; width: 30px;">NO</td>
                    <td style="border: 1px solid #000;font-weight:bold">TANGGAL</td>
                    <td style="border: 1px solid #000;font-weight:bold">NAMA</td>
                    <td style="border: 1px solid #000;font-weight:bold">JABATAN</td>
                    <td style="border: 1px solid #000;font-weight:bold">HARGA</td>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                
                {{-- Langkah 2: Loop data yang sudah difilter sesuai grupnya saja --}}
                @foreach ($pesertaList as $item)
                    @php
                        $qty = $item->total_tindakan == 0 ? 1 : $item->total_tindakan;
                        $jumlah = $qty * $item->total_transaksi;
                        $grandTotal += $jumlah;
                    @endphp
                    <tr style="font-weight: bold;font-size:12px;">
                        <td style="border:1px solid #000; text-align:center;">{{ $loop->iteration }}</td>
                        <td style="border:1px solid #000; padding-left: 5px;">
                            {{ date('d-m-Y H:i:s', strtotime($item->tanggal_transaksi)) }}
                        </td>
                        <td style="border:1px solid #000; padding-left: 5px;">
                            {{ strtoupper($item->nama_peserta) }}
                        </td>
                        <td style="border:1px solid #000; padding-left: 5px;">
                            {{ strtoupper($item->nama_departemen) }}
                        </td>
                        <td style="border:1px solid #000; text-align:right; padding-right: 5px;">
                            <span style="float: left;">Rp</span>
                            <span style="float: right;">{{ number_format($item->total_transaksi, 0, ',', '.') }}</span>
                            <div style="clear: both;"></div>
                        </td>
                    </tr>
                @endforeach
                <tr style="font-weight:bold; background:#C0C0C0; font-size:12px;">
                    <td colspan="4" style="border:1px solid #000; text-align:right; padding-right: 5px;">TOTAL</td>
                    <td style="border:1px solid #000; padding: 5px; background:#C0C0C0;">
                        <span style="float: left;">Rp</span>
                        <span style="float: right;">{{ number_format($grandTotal, 0, ',', '.') }}</span>
                        <div style="clear: both;"></div>
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="width: 95%; margin: 10px auto;border-collapse: collapse;" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 35%; text-align: center; padding-left: 20px;font-size: 13px;">
                Disajikan Oleh<br>
                KLINIK ARTHA MEDICA CENTRE<br>
                <img src="data:image/png;base64,{{ $data['qrcode_no_nota'] }}">
                <br><u>RIZKY</u><br>ADMIN
                </td>
                <td style="width: 40%; text-align: center; padding-right: 20px; font-size: 13px;">
                Dicek Oleh<br>{{ $data['nama_perusahaan'] }}<br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <span style="font-weight: bold;"><u>{!! str_repeat('&nbsp;', 50) !!}</u></span><br>
                </td>
                <td style="width: 40%; text-align: center; padding-right: 20px; font-size: 13px;">
                Diterima Oleh<br>{{ $data['nama_perusahaan'] }}<br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <span style="font-weight: bold;"><u>{!! str_repeat('&nbsp;', 50) !!}</u></span><br>
                </td>
            </tr>
        </table>
    </main>
@endforeach
</body></html>