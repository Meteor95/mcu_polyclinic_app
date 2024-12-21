<?php

namespace App\Models\PemeriksaanFisik\KondisiFisik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class KondisiFisik extends Model
{
    protected $table;
    protected $fillable = [
        'user_id',
        'transaksi_id',
        'id_atribut',
        'nama_atribut',
        'kategori_atribut',
        'jenis_atribut',
        'status_atribut',
        'keterangan_atribut'
    ];
    public function setTableName($table)
    {
        $this->table = $table;
    }
    public function listKondisiFisik($request, $perHalaman, $offset)
    {
        $lokasiFisik = $this->getLokasiFisikTable($request->lokasi_fisik);
        if (!$lokasiFisik) {
            throw new \Exception("Lokasi fisik tidak valid.");
        }
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = $this->buildKondisiFisikQuery($lokasiFisik, $request->parameter_pencarian);
        $jumlahData = $query->groupBy($lokasiFisik . '.user_id', $lokasiFisik . '.transaksi_id')->count();
        $result = $query->orderBy($lokasiFisik . '.created_at', 'DESC')
            ->take($perHalaman)
            ->skip($offset)
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahData,
        ];
    }
    private function getLokasiFisikTable($lokasiFisik)
    {
        $lokasiFisik = strtolower($lokasiFisik);

        $lokasiFisikTables = [
            'kepala' => 'mcu_pf_kepala',
            'telinga' => 'mcu_pf_telinga',
            'mata' => 'mcu_pf_mata',
            'tenggorokan' => 'mcu_pf_tenggorokan',
            'mulut' => 'mcu_pf_mulut',
            'gigi' => 'mcu_pf_gigi',
            'leher' => 'mcu_pf_leher',
            'thorax' => 'mcu_pf_thorax',
            'abdomen_urogenital' => 'mcu_pf_abdomen_urogenital',
            'anorectal_genital' => 'mcu_pf_anorectal_genital',
            'ekstremitas' => 'mcu_pf_ekstremitas',
            'neurologis' => 'mcu_pf_neurologis',
        ];

        return $lokasiFisikTables[$lokasiFisik] ?? null;
    }
    private function buildKondisiFisikQuery($lokasiFisik, $parameterPencarian)
    {
        $tablePrefix = config('database.connections.mysql.prefix');

        $query = DB::table($lokasiFisik)
            ->join('users_member', 'users_member.id', '=', $lokasiFisik . '.user_id')
            ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', $lokasiFisik . '.transaksi_id')
            ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
            ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
            ->select(
                'company.*',
                'departemen_peserta.*',
                $lokasiFisik . '.*',
                $lokasiFisik . '.id as id_' . $lokasiFisik,
                'users_member.*',
                'users_member.nama_peserta',
                'mcu_transaksi_peserta.*'
            )
            ->selectRaw(
                'DATE_FORMAT(' . $tablePrefix . $lokasiFisik . '.created_at, "%d-%m-%Y %H:%i:%s") as tanggal_transaksi, ' .
                'TIMESTAMPDIFF(YEAR, ' . $tablePrefix . 'users_member.tanggal_lahir, CURDATE()) AS umur'
            );

        if (!empty($parameterPencarian)) {
            $query->where(function ($query) use ($parameterPencarian) {
                $query->where('users_member.nama_peserta', 'LIKE', '%' . $parameterPencarian . '%')
                    ->orWhere('mcu_transaksi_peserta.no_transaksi', 'LIKE', '%' . $parameterPencarian . '%');
            });
        }
        return $query->groupBy($lokasiFisik . '.user_id', $lokasiFisik . '.transaksi_id');
    }
}
