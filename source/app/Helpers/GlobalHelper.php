<?php

namespace App\Helpers;

class GlobalHelper
{
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