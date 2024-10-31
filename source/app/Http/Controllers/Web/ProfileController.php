<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private function getData($req, $title, $breadcrumb) {
        return [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
            'user_details' => $req->attributes->get('user_details'),
        ];
    }
    public function profile(Request $req){
        $data = $this->getData($req,'Profile', [
            'Profile' => route('admin.akun.profile'),
        ]);
        return view('paneladmin.akun.profile', ['data' => $data]);
    }
}
