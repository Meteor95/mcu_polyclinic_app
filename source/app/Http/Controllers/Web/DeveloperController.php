<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeveloperController extends Controller
{
    private function getData($req, $title, $breadcrumb) {
        return [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
            'user_details' => $req->attributes->get('user_details'),
        ];
    }
    public function error_log(Request $request)
    {
        $data = $this->getData($request, 'Error Log Pada Aplikasi', [
            'Beranda' => route('admin.beranda'),
        ]);
        return view('paneladmin.developer.error_log', ['data' => $data]);
    }
}
