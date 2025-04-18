<?php

namespace App\Services;

use Illuminate\Support\Facades\{DB, Hash, Storage};
use Carbon\Carbon;
use App\Models\Poliklinik\{Poliklinik, UnggahanCitra};
use App\Helpers\ResponseHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Spatie\PdfToImage\Pdf as KonversiPDFtoImage;
use Exception;

class PoliklinikServices
{

    public function handleTransactionPoliklinik($data, $hasFile, $jenis_poli, $userIdLogin, $hasFilePdf)
    {
        $namafolder = "";$updatedId = "";
        $dataToInsert = [];
        return DB::transaction(function () use ($data, $hasFile, $jenis_poli, $userIdLogin, $hasFilePdf) {
            $model = new Poliklinik();
            if ($jenis_poli == 'spirometri') {
                $model->setTableName('mcu_poli_spirometri');
                $namafolder = 'spirometri';
            }elseif($jenis_poli == 'ekg'){
                $model->setTableName('mcu_poli_ekg');
                $namafolder = 'ekg';
            }elseif($jenis_poli == 'threadmill'){
                $model->setTableName('mcu_poli_threadmill');
                $namafolder = 'threadmill';
            }elseif($jenis_poli == 'rontgen_thorax'){
                $model->setTableName('mcu_poli_rontgen_thorax');
                $namafolder = 'rontgen_thorax';
            }elseif($jenis_poli == 'rontgen_lumbosacral'){
                $model->setTableName('mcu_poli_rontgen_lumbosacral');
                $namafolder = 'rontgen_lumbosacral';
            }elseif($jenis_poli == 'farmingham_score'){
                $model->setTableName('mcu_poli_farmingham_score');
                $namafolder = 'farmingham_score';
            }elseif($jenis_poli == 'audiometri'){
                $model->setTableName('mcu_poli_audiometri');
                $namafolder = 'audiometri';
            }elseif($jenis_poli == 'usg_ubdomain'){
                $model->setTableName('mcu_poli_usg_ubdomain');
                $namafolder = 'usg_ubdomain';
            }
            $isedit = filter_var($data['isedit'], FILTER_VALIDATE_BOOLEAN);
            $data = [
                'pegawai_id' => $data['pegawai_id'],
                'user_id' => $data['user_id'],
                'transaksi_id' => $data['transaksi_id'],
                'judul_laporan' => $data['judul_laporan'],
                'id_kesimpulan' => $data['id_kesimpulan'],
                'kesimpulan' => $data['kesimpulan'],
                'detail_kesimpulan' => $data['detail_kesimpulan'],
                'catatan_kaki' => $data['catatan_kaki'],
                'petugas_id' => $userIdLogin,
            ];
            if ($isedit){
                $unggahan_poliklinik = $model->where('user_id', $data['user_id'])->where('transaksi_id', $data['transaksi_id'])->first();
                $unggahan_poliklinik->update($data);
                $updatedId = $unggahan_poliklinik->id;
            }else{
                $unggahan_poliklinik = $model->create($data);
            }
            $directory = storage_path('app/public/mcu/poliklinik/'.$namafolder);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            if (!empty($hasFilePdf)) {
                $konversipdf = new KonversiPDFtoImage($hasFilePdf);
                $numberOfPages = $konversipdf->pageCount();
                $originalName = pathinfo($hasFilePdf->getClientOriginalName(), PATHINFO_FILENAME);
                $sanitizedName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', strtolower($originalName));
                $timestamp = round(microtime(true) * 1000);
            
                for ($i = 1; $i <= $numberOfPages; $i++) {
                    $uuid = (string) Str::uuid();
                    $filename = "{$i}_{$uuid}_{$sanitizedName}_{$timestamp}.jpg";
                    $konversipdf->selectPages($i)->save("{$directory}/{$filename}");
                    $filePath = "{$directory}/{$filename}";
                    $dataToInsert[] = [
                        'id_trx_poli' => $unggahan_poliklinik->id,
                        'jenis_poli' => "poli_{$jenis_poli}",
                        'nama_file_asli' => "{$i}_{$hasFilePdf->getClientOriginalName()}",
                        'nama_file' => $filename,
                        'meta_citra' => json_encode([
                            'hash_file' => md5_file($filePath),
                            'size' => filesize($filePath),
                            'mime' => mime_content_type($filePath),
                            'owner' => env('APP_NAME'),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]),
                        'width' => getimagesize($filePath)[0],
                        'height' => getimagesize($filePath)[1],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            if (!empty($hasFile)) {
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
                        throw new Exception('Gagal memproses file PNG atau JPEG.');
                    }
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }
                    imagealphablending($image, false);
                    imagesavealpha($image, true);
                    imagepng($image, $filePath, 8);
                    imagedestroy($image);
                    $dataToInsert[] = [
                        'id_trx_poli' => $unggahan_poliklinik->id,
                        'jenis_poli' => "poli_".$jenis_poli,
                        'nama_file_asli' => $file->getClientOriginalName(),
                        'nama_file' => $filename,
                        'meta_citra' => json_encode([
                            'hash_file' => md5($filename),
                            'size' => $file->getSize(),
                            'mime' => $file->getMimeType(),
                            'owner' => env('APP_NAME'),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]),
                        'width' => $imageSize[0],
                        'height' => $imageSize[1],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            if (!empty($dataToInsert)) {
                UnggahanCitra::insert($dataToInsert);
            }
        });
    }

    public function hapusCitraUnggahanPoliklinik($data)
    {
        return DB::transaction(function () use ($data) {
            $model = new Poliklinik();
            if ($data['jenis_poli'] == 'spirometri') {
                $model->setTableName('mcu_poli_spirometri');
                $namafolder = 'spirometri';
            }elseif($data['jenis_poli'] == 'ekg'){
                $model->setTableName('mcu_poli_ekg');
                $namafolder = 'ekg';
            }elseif($data['jenis_poli'] == 'threadmill'){
                $model->setTableName('mcu_poli_threadmill');
                $namafolder = 'threadmill';
            }elseif($data['jenis_poli'] == 'rontgen_thorax'){
                $model->setTableName('mcu_poli_rontgen_thorax');
                $namafolder = 'rontgen_thorax';
            }elseif($data['jenis_poli'] == 'rontgen_lumbosacral'){
                $model->setTableName('mcu_poli_rontgen_lumbosacral');
                $namafolder = 'rontgen_lumbosacral';
            }elseif($data['jenis_poli'] == 'farmingham_score'){
                $model->setTableName('mcu_poli_farmingham_score');
                $namafolder = 'farmingham_score';
            }elseif($data['jenis_poli'] == 'audiometri'){
                $model->setTableName('mcu_poli_audiometri');
                $namafolder = 'audiometri';
            }elseif($data['jenis_poli'] == 'usg_ubdomain'){
                $model->setTableName('mcu_poli_usg_ubdomain');
                $namafolder = 'usg_ubdomain';
            }
            if ($data['jenis_poli'] !== "farmingham_score") {
                $model->where('id', $data['id_trx_poli'])->delete();
                $unggahan_citra = UnggahanCitra::where('id_trx_poli', $data['id_trx_poli'])->get();
                foreach ($unggahan_citra as $item) {
                    Storage::disk('public')->delete('mcu/poliklinik/' . $namafolder . '/' . $item->nama_file);
                }
                UnggahanCitra::where('id_trx_poli', $data['id_trx_poli'])->where('jenis_poli', 'poli_'.$data['jenis_poli'])->delete();
            }else{
                $model->where('transaksi_id', $data['id_trx_poli'])->delete();
            }
        });
    }
}

