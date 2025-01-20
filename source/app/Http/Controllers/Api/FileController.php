<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    public function download_foto(Request $req)
    {
        try {
            $fileName = $req->file_name;
            $filePath = 'mcu/foto_peserta/' . $fileName;
            if (!Storage::disk('public')->exists($filePath)) {
                return response()->json([
                    'error' => 'File not found.'
                ], Response::HTTP_NOT_FOUND);
            }
            $fileContents = Storage::disk('public')->get($filePath);
            if (empty($fileContents)) {
                return response()->json([
                    'error' => 'File is empty or could not be read.'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $mimeType = mime_content_type(storage_path('app/public/' . $filePath));
            $headers = [
                'Content-Type' => $mimeType, 
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ];
            return response($fileContents, 200, $headers);
        
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function downlad_citra_poliklinik(Request $req){
        try {
            $fileName = $req->file_name;
            $jenis_poli = str_replace('poli_', '', $req->jenis_poli);
            $filePath = 'mcu/poliklinik/'.$jenis_poli.'/' . $fileName;
            Log::info($filePath);
            if (!Storage::disk('public')->exists($filePath)) {
                return response()->json([
                    'error' => 'File not found.'
                ], Response::HTTP_NOT_FOUND);
            }
            $fileContents = Storage::disk('public')->get($filePath);
            if (empty($fileContents)) {
                return response()->json([
                    'error' => 'File is empty or could not be read.'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $mimeType = mime_content_type(storage_path('app/public/' . $filePath));
            $headers = [
                'Content-Type' => $mimeType, 
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ];
            return response($fileContents, 200, $headers);
        
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function download_surat_pengantar(Request $req){
        try {
            $fileName = $req->file_name;
            $filePath = 'file_surat_pengantar/' . $fileName;
            if (!Storage::disk('public')->exists($filePath)) {
                return response()->json([
                    'error' => 'File not found.'
                ], Response::HTTP_NOT_FOUND);
            }
            $fileContents = Storage::disk('public')->get($filePath);
            if (empty($fileContents)) {
                return response()->json([
                    'error' => 'File is empty or could not be read.'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $mimeType = mime_content_type(storage_path('app/public/' . $filePath));
            $headers = [
                'Content-Type' => $mimeType, 
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ];
            return response($fileContents, 200, $headers);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
