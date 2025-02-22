<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class FileController extends Controller
{
    public function showSignature($filename)
    {
        $path = 'user/ttd/' . $filename;
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }
        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);
        return response($file, 200)->header('Content-Type', $mimeType);
    }
}
