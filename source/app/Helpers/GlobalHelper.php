<?php

namespace App\Helpers;

class GlobalHelper
{
    // Fungsi untuk mengubah angka menjadi terbilang
    public static function pembilang_nominal($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";

        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = self::pembilang_nominal($nilai - 10) . " belas";
        } else if ($nilai < 100) {
            $temp = self::pembilang_nominal(floor($nilai / 10)) . " puluh" . self::pembilang_nominal($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . self::pembilang_nominal($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = self::pembilang_nominal(floor($nilai / 100)) . " ratus" . self::pembilang_nominal($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . self::pembilang_nominal($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = self::pembilang_nominal(floor($nilai / 1000)) . " ribu" . self::pembilang_nominal($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = self::pembilang_nominal(floor($nilai / 1000000)) . " juta" . self::pembilang_nominal($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = self::pembilang_nominal(floor($nilai / 1000000000)) . " milyar" . self::pembilang_nominal(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = self::pembilang_nominal(floor($nilai / 1000000000000)) . " trilyun" . self::pembilang_nominal(fmod($nilai, 1000000000000));
        }
        return $temp;
    }

    // Fungsi untuk menambahkan kata "minus" jika nilai negatif
    public static function terbilang($nilai) {
        if ($nilai < 0) {
            $hasil = "minus " . trim(self::pembilang_nominal($nilai));
        } else {
            $hasil = trim(self::pembilang_nominal($nilai));
        }
        return $hasil;
    }
    public static function convertStringToAsterisksmod2($kata) {
        $result = "";
        for ($i = 0; $i < strlen($kata); $i++) {
            $result .= ($i % 2 == 0) ? '*' : $kata[$i];
        }
        return $result;
    }

    public static function getNamaBulanIndonesia($bulan) {
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
    $bulan = (int)$bulan;
    if ($bulan >= 1 && $bulan <= 12) {
            return $namaBulan[$bulan];
        } else {
            return "Bulan tidak valid";
        }
    }
    public static function pembulatanDuaDigit($angka) {
        $angka = (float) $angka;
        if ($angka > 0 && $angka < 0.01) {
          return number_format(0.01, 2);
        } else {
          return number_format(ceil($angka * 100) / 100, 2);
        }
    }
}