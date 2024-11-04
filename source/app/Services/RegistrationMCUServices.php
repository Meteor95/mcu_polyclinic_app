<?php

namespace App\Services;

use Illuminate\Support\Facades\{DB, Hash, Storage};
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Pendaftaran\Peserta;

use Exception;

class RegistrationMCUServices
{
    function handleTransactionDeletePeserta($data)
    {
        return Peserta::where('uuid', '=', $data['id'])->delete();
    }
}
