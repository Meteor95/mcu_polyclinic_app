<?php

namespace App\Services;

use Illuminate\Support\Facades\{DB, Hash, Storage};
use Carbon\Carbon;
use App\Models\Poliklinik\{Poliklinik, UnggahanCitra};
use App\Helpers\ResponseHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Exception;

class PoliklinikServices
{

    public function handleTransactionPoliklinik($data, $hasFile, $jenis_poli)
    {
        $namafolder = "";
        $dataToInsert = [];
        return DB::transaction(function () use ($data, $hasFile, $jenis_poli) {
            $model = new Poliklinik();
            if ($jenis_poli == 'spirometri') {
                $model->setTableName('mcu_poli_spirometri');
                $namafolder = 'spirometri';
            }
            $data = [
                'user_id' => $data['user_id'],
                'transaksi_id' => $data['transaksi_id'],
                'judul_laporan' => $data['judul_laporan'],
                'kesimpulan' => $data['kesimpulan'],
                'detail_kesimpulan' => $data['detail_kesimpulan'],
                'catatan_kaki' => $data['catatan_kaki'],
            ];
            $unggahan_poliklinik = $model->create($data);
            $directory = storage_path('app/public/mcu/poliklinik/'.$namafolder);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            if ($hasFile) {
                foreach ($hasFile as $file) {
                    $uuid = (string) Str::uuid();
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $sanitizedName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', strtolower($originalName));
                    $timestamp = round(microtime(true) * 1000);
                    $filename = "{$uuid}_{$sanitizedName}_{$timestamp}.png";
                    $filePath = $directory . '/' . $filename;
                    $imageSize = getimagesize($file->getPathname());
                    if (!$imageSize || $imageSize['mime'] !== 'image/png') {
                        throw new Exception('File bukan PNG atau tidak valid.');
                    }
                    $image = imagecreatefrompng($file->getPathname());
                    if (!$image) {
                        throw new Exception('Gagal memproses file PNG.');
                    }
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }
                    imagepng($image, $filePath, 8);
                    imagedestroy($image);
                    $dataToInsert[] = [
                        'id_trx_poli' => $unggahan_poliklinik->id,
                        'jenis_poli' => $jenis_poli,
                        'nama_file_asli' => $file->getClientOriginalName(),
                        'nama_file' => $filename,
                        'meta_citra' => json_encode([
                            'hash_file' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                            'mime' => $file->getMimeType(),
                            'owner' => env('APP_NAME'),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                if (!empty($dataToInsert)) {
                    UnggahanCitra::insert($dataToInsert);
                }
            }
        });
    }
}

