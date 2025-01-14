<?php

namespace App\Models\Laboratorium;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TemplateLab extends Model
{
    protected $table = 'lab_template_tindakan';
    protected $fillable = [
        'used_paket_mcu',
        'id_paket_mcu',
        'nama_template',
        'meta_data_template',
    ];
    public static function listTemplateTabel($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        $query = DB::table('lab_template_tindakan')
            ->leftJoin('paket_mcu', 'lab_template_tindakan.id_paket_mcu', '=', 'paket_mcu.id')
            ->select('lab_template_tindakan.*','lab_template_tindakan.id as id_template_tindakan', 'paket_mcu.nama_paket', 'paket_mcu.harga_paket');
        if (!empty($parameterpencarian)) {
            $query->where('lab_template_tindakan.nama_template', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $jumlahdata = $query->count();
        if ($perHalaman > 0) {
            $result = $query->take($perHalaman)
                ->skip($offset)
                ->orderBy('lab_template_tindakan.nama_template', 'ASC')
                ->get();
        } else {
            $result = $query->orderBy('lab_template_tindakan.nama_template', 'ASC')
                ->get();
        }
        $kodeItems = [];
        foreach ($result as $item) {
            if (!empty($item->meta_data_template)) {
                $metaDataTemplate = json_decode($item->meta_data_template, true);
                if (is_array($metaDataTemplate)) {
                    foreach ($metaDataTemplate as $meta) {
                        if (isset($meta['kode_item'])) {
                            $kodeItems[] = $meta['kode_item'];
                        }
                    }
                }
            }
        }
        $template_tindakan = [];
        if (!empty($kodeItems)) {
            $template_tindakan = Tarif::whereIn('kode_item', $kodeItems)
                ->get();
            $tarifData = $template_tindakan->keyBy('kode_item');
        }
        foreach ($result as &$item) {
            if (!empty($item->meta_data_template)) {
                $metaDataTemplate = json_decode($item->meta_data_template, true);
                if (is_array($metaDataTemplate)) {
                    foreach ($metaDataTemplate as &$meta) {
                        if (isset($meta['kode_item']) && isset($tarifData[$meta['kode_item']])) {
                            $meta['tarif'] = $tarifData[$meta['kode_item']];
                        }
                    }
                }
                $item->meta_data_template = $metaDataTemplate;
            }
        }
        return [
            'data' => $result,
            'total' => $jumlahdata,
        ];
    }    
}

