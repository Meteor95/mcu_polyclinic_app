<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Cetak Kuitansi Tagihan Perusahaan {{ $data['nama_perusahaan'] }}</title>
<style>
@page { 
    margin:0;
}
</style>
</head>
<body>
@php
    function renderHeader($title = 'TAGIHAN') {
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
                            <span style="font-size: 15px;">Contact Person: 0812-3456-7890 | 0812-3456-7890</span><br>
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
@php $grandTotal = 0; $groupedData = collect($data['inv_resume_mcu_peserta'])->groupBy('jenis_transaksi_pendaftaran');@endphp
@foreach ($groupedData as $jenisTransaksi => $pesertaList)
    @foreach ($pesertaList as $item)
    @php
        $qty = $item->total_tindakan == 0 ? 1 : $item->total_tindakan;
        $jumlah = $qty * $item->total_transaksi;
        $grandTotal += $jumlah;
    @endphp
    @endforeach
@endforeach
    <main>
        <div style="width: 80%; max-width: 800px; margin: 10% auto; background-color: white; border: 3px solid #000; font-family: Arial, sans-serif;">
        <!-- Header -->
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
            
            <!-- Title -->
            <div style="text-align: center; font-size: 20px; font-weight: bold; padding: 20px;">
                KWITANSI PEMBAYARAN / BUKTI PEMBAYARAN
            </div>
            
            <!-- Content -->
            <div style="padding: 30px 40px;">
                <!-- No Kwitansi -->
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
                
                <!-- Sudah terima dari -->
                <table style="width: 100%; margin-bottom: 25px; border-collapse: collapse;">
                    <tr>
                        <td style="width: 120px; font-size: 14px; font-style: italic; vertical-align: middle;">Sudah Terima Dari</td>
                        <td style="width: 20px; text-align: center; vertical-align: middle;">:</td>
                        <td style="vertical-align: middle;">
                            <div style="border: 2px solid #000; padding: 10px 20px; font-size: 14px; font-weight: bold; background-color: #f9f9f9;">{{ $data['nama_perusahaan'] }}</div>
                        </td>
                    </tr>
                </table>
                
                <!-- Banyaknya uang -->
                <table style="width: 100%; margin-bottom: 25px; border-collapse: collapse;">
                    <tr>
                        <td style="width: 120px; font-size: 14px; font-style: italic; vertical-align: middle;">Banyaknya Uang</td>
                        <td style="width: 20px; text-align: center; vertical-align: middle;">:</td>
                        <td style="vertical-align: middle;">
                            <div style="border: 2px solid #000; padding: 10px 20px; font-size: 14px; font-weight: bold; background-color: #f9f9f9;">{{ ucwords(\App\Helpers\GlobalHelper::terbilang($grandTotal))." Rupiah" }}</div>
                        </td>
                    </tr>
                </table>
                
                <!-- Untuk Pembayaran -->
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
                
                <!-- Footer -->
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
                            <span style="font-size: 14px;font-weight: bold;"><u>{{ $data['nama_direktur_keuangan'] }}</u></span><br>
                            <span style="font-size: 14px;font-weight: bold;">Direktur Keuangan</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </main>
    <div style="page-break-after: always;"></div>
    <main style="margin-left: 25px; margin-right: 25px;">
        {!! renderHeader("DETAIL TINDAKAN") !!}
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    Kepada Yth.<br>
                    {{ $data['nama_perusahaan'] }}<br>
                    Cq. BAGIAN KEUANGAN<br>
                    Di Tempat
                </td>
                <td style="width: 40%; vertical-align: top;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="text-align: left;">Tanggal Cetak:</td>
                            <td style="text-align: right;">{{ $data['tanggal_cetak'] }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Nomor Transaksi :</td>
                            <td style="text-align: right;">{{ $parts[0] }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table style="font-size:14px;width: 100%; border-collapse: collapse;" cellpadding="0" cellspacing="0">
            <thead>
            <tr style="background-color: #C0C0C0;color: black;text-align: center;">
                <td style="border: 2px solid #000;">NAMA TINDAKAN</td>
                <td style="border: 2px solid #000;">QTY</td>
                <td style="border: 2px solid #000;">HARGA</td>
                <td style="border: 2px solid #000;">TOTAL</td>
            </tr>
            </thead>
            <tbody>
            @php
                $currentJenis = null;
                $subtotal = 0;
                $totalApotek = 0;
                $grandTotal = 0;
            @endphp
            @foreach ($data['detail_tagihan'] as $item)
                @if ($currentJenis != $item['jenis_layanan'])
                    @if ($currentJenis != null)
                        @php
                            $subTotalJenis = $subtotal + $totalApotek;
                            $grandTotal += $subTotalJenis;
                        @endphp
                        <tr style="font-weight: bold;">
                            <td colspan="3" style="text-align: right; border: 2px solid #000; padding-right: 5px;">NOMINAL TINDAKAN</td>
                            <td style="text-align: right; border: 2px solid #000; padding-right: 5px;">{{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr style="font-weight: bold;">
                            <td colspan="3" style="text-align: right; border: 2px solid #000; padding-right: 5px;">NOMINAL APOTEK</td>
                            <td style="text-align: right; border: 2px solid #000; padding-right: 5px;">{{ number_format($totalApotek, 0, ',', '.') }}</td>
                        </tr>
                        <tr style="font-weight: bold;">
                            <td colspan="3" style="text-align: right; border: 2px solid #000; padding-right: 5px;">SUBTOTAL</td>
                            <td style="text-align: right; border: 2px solid #000; padding-right: 5px;">{{ number_format($subTotalJenis, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    @php 
                        $currentJenis = $item['jenis_layanan'];
                        $subtotal = 0;
                        $totalApotek = $item['nominal_apotek'];
                    @endphp
                    <tr style="background-color: #C0C0C0;">
                        <td colspan="4" style="text-align: left; font-weight: bold; border: 2px solid #000; padding-left: 5px;">
                            {{ strtoupper(str_replace('_', ' ', $item['jenis_layanan'])) }} 
                        </td>
                    </tr>
                @else
                    @php
                        $totalApotek = $item['nominal_apotek'];
                    @endphp
                @endif
                @php
                    $total = $item['harga_setelah_diskon'] * $item['jumlah_qty'];
                    $subtotal += $total;
                    if ($subtotal == 0) {
                        $subtotal = $item['total_transaksi'];
                    }else{
                        $subtotal = $subtotal;
                    }
                @endphp
                <tr>
                    <td style="border: 2px solid #000;padding-left: 5px">{{ str_replace('_', ' ', $item['nama_item']) }}</td>
                    <td style="text-align: center;border: 2px solid #000;">{{ $item['jumlah_qty'] }}</td>
                    <td style="text-align: right;border: 2px solid #000;padding-right: 5px">{{ number_format($item['harga_setelah_diskon'], 0, ',', '.') }}</td>
                    <td style="text-align: right;border: 2px solid #000;padding-right: 5px">{{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            @php
                $subTotalJenis = $subtotal + $totalApotek;
                $grandTotal += $subTotalJenis;
            @endphp
            <tr style="font-weight: bold;">
                <td colspan="3" style="text-align: right; border: 2px solid #000; padding-right: 5px;">NOMINAL TINDAKAN</td>
                <td style="text-align: right; border: 2px solid #000; padding-right: 5px;">{{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr style="font-weight: bold;">
                <td colspan="3" style="text-align: right; border: 2px solid #000; padding-right: 5px;">NOMINAL APOTEK</td>
                <td style="text-align: right; border: 2px solid #000; padding-right: 5px;">{{ number_format($totalApotek, 0, ',', '.') }}</td>
            </tr>
            <tr style="font-weight: bold;">
                <td colspan="3" style="text-align: right; border: 2px solid #000; padding-right: 5px;">SUBTOTAL</td>
                <td style="text-align: right; border: 2px solid #000; padding-right: 5px;">{{ number_format($subTotalJenis, 0, ',', '.') }}</td>
            </tr>
            <tr style="font-weight: bold; background-color: #C0C0C0; color: black;">
                <td colspan="3" style="text-align: right; border: 2px solid #000; padding-right: 5px;">GRAND TOTAL</td>
                <td style="text-align: right; border: 2px solid #000; padding-right: 5px;">{{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
            </tbody>
        </table>


        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; text-align: center; padding-left: 20px;font-size: 13px;">
                Pindai untuk periksa keaslian kuitansi<br>
                Kuitansi ini tervalidasi dan dicetak secara otomatis<br>
                <img src="data:image/png;base64,{{ $data['qrcode_no_nota'] }}">
                </td>
                <td style="width: 50%; text-align: center; padding-right: 20px; font-size: 13px;">
                Mengetahui<br>Sendawar, {{ $data['tanggal_cetak'] }}<br>
                    <img src="data:image/png;base64,{{ $data['qrcode_dokter'] }}"><br>
                    <span style="font-size: 14px;font-weight: bold;"><u>{{ $data['nama_direktur_keuangan'] }}</u></span><br>
                    <span style="font-size: 14px;font-weight: bold;">Direktur Keuangan</span>
                </td>
            </tr>
        </table>
    </main>
</body></html>