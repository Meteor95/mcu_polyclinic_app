<?php

namespace App\Traits;

trait NavigasiTrait
{
    public function getNavigasi(string $text_sebelumnya, string $url_sebelumnya, string $text_selanjutnya, string $url_selanjutnya, bool $visible_sebelumnya, bool $visible_selanjutnya): array
    {
        return [
            'text_sebelumnya' => $text_sebelumnya,
            'text_selanjutnya' => $text_selanjutnya,
            'url_sebelumnya' => $url_sebelumnya,
            'url_selanjutnya' => $url_selanjutnya,
            'visible_sebelumnya' => $visible_sebelumnya,
            'visible_selanjutnya' => $visible_selanjutnya,
        ];
    }
}
