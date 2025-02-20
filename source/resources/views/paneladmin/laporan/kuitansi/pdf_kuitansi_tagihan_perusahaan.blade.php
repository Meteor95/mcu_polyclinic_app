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
        
    </main>
</body></html>