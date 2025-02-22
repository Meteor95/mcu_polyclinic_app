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
    <div class="header">
        <img src="{{ asset('mofi/assets/images/logo/border_hasil_mcu_atas.png') }}" alt="Border Hasil MCU" style="position: absolute;top: 0;right: 0;width: 100%;z-index: -1;opacity: 0.6;">
        <table style="width: 100%;">
            <tr>
                <td style="width:30%; vertical-align: center;">
                    <img src="{{ asset('mofi/assets/images/logo/Logo_AMC_Full.png') }}" alt="Logo AMC" style="width: 100%;padding-top: 20px;">
                </td>
                <td style="width:70%; text-align: right;">
                    <p>
                        <span style="font-size: 25px; font-weight: bold;">KLINIK ARTHA MEDICAL CENTRE</span><br>
                        <span style="font-size: 15px;">Alamat: Jl. Sendawar Raya RT 029 Kel. Melak Ulu Kec. Melak, Kutai Barat 75765</span><br>
                        <span style="font-size: 15px;">E-Mail: amc.clinic.yhs@gmail.com | website: arthamedicalcentre.com</span><br>
                        <span style="font-size: 15px;">Contact Person: 0812-3456-7890 | 0812-3456-7890</span>
                    </p>
                </td>
            </tr>
        </table>
        <hr style="border: 2px solid #000;">
    </div>
    <main style="margin-left: 25px; margin-right: 25px;">
        <h1 style="text-align: center;margin-top: 0">TAGIHAN</h1>
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    Kepada Yth.<br>
                    {{ $data['nama_perusahaan'] }}<br>
                    Cq. BAGIAN KEUANGAN<br>
                    Di Tempat
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="text-align: left;">Tanggal :</td>
                            <td style="text-align: right;">{{ $data['tanggal_cetak'] }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Nomor Transaksi :</td>
                            <td style="text-align: right;">{{ $data['no_transaksi_combine'] }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table style="width: 100%; border-collapse: collapse;" cellpadding="0" cellspacing="0">
            <thead>
            <tr style="background-color: orange;color: white;text-align: center;">
                <td style="border: 2px solid #000;">NAMA ITEM</td>
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
                    <tr style="background-color: #f0f0f0;">
                        <td colspan="4" style="text-align: left; font-weight: bold; border: 2px solid #000; padding-left: 5px;">
                            {{ strtoupper(str_replace('_', ' ', $item['jenis_layanan'])) }} 
                            @if($item['apakah_paket'] > 0) 
                                (PAKET : {{ $item['nama_paket_mcu'] }})
                            @endif
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
                    <td style="border: 2px solid #000;padding-left: 5px">{{ $item['nama_item'] }}</td>
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
            <tr style="font-weight: bold; background-color: orange; color: white;">
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
                    <span style="font-weight: bold;"><u>{{ $data['atas_nama_nota'] }}</u></span><br>
                    <span style="font-weight: bold;">{{ $data['nip'] }}</span>
                </td>
            </tr>
        </table>
    </main>
</body></html>