<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Cetak Kuitansi Perusahaan {{ $data['nama_perusahaan'] }}</title>
<style>
@page { 
    margin:0;
}
</style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('mofi/assets/images/logo/border_hasil_mcu_atas.png') }}" alt="Border Hasil MCU" style="position: absolute;top: 0;right: 0;width: 100%;z-index: -1;opacity: 0.6;">
        <table style="width: 100%;padding-right: 25px;">
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
    <main>
        <h3 style="text-align: center;">KUITANSI / BUKTI PEMBAYARAN</h3>
        <table cellpadding="0" cellspacing="0" style="width: 80%; margin: 10px auto;font-weight: bold;">
            <tr>
                <td style="white-space: nowrap;">Nama Perusahaan</td>
                <td>:</td>
                <td>{{ $data['nama_perusahaan'] }}</td>
            </tr>
            <tr>
                <td style="white-space: nowrap;">Tagihan Untuk</td>
                <td>:</td>
                <td>{{ $data['jumlah_peserta'] }} Orang Peserta</td>
            </tr>
            <tr>
                <td style="white-space: nowrap;">Keterangan</td>
                <td>:</td>
                <td>{{ $data['keterangan'] }}</td>
            </tr>
            <tr>
                <td style="white-space: nowrap;">Total Pembayaran</td>
                <td>:</td>
                <td>{{ $data['total_pembayaran'] }}</td>
            </tr>
            <tr>
                <td colspan="3" style="border-top: 2px solid #000;border-left: 2px solid #000;border-right: 2px solid #000; white-space: nowrap; text-align: center;">Terbilang</td>
            </tr>
            <tr>
                <td colspan="3" style="border-bottom: 2px solid #000;border-left: 2px solid #000;border-right: 2px solid #000; white-space: nowrap; text-align: center;">{{$data['terbilang']}}</td>
            </tr>
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
        <hr style="border: 2px solid #000;">
    </main>
</body></html>