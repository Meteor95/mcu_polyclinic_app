<?php

namespace App\Services;

use Mpdf\Mpdf;

class MpdfService
{
    protected $mpdf;

    /**
     * Load Blade view ke mPDF
     */
    public function loadView(string $view, array $data = [], array $options = [])
    {
        $html = view($view, $data)->render();

        $defaultOptions = [
            'format'        => 'Legal',
            'orientation'   => 'P',
            'margin_left'   => 0,
            'margin_right'  => 0,
            'margin_top'    => 0,
            'margin_bottom' => 0,
        ];

        $this->mpdf = new Mpdf(array_merge($defaultOptions, $options));
        $this->mpdf->WriteHTML($html);

        return $this; // biar chaining
    }

    /**
     * Akses langsung instance mPDF
     */
    public function render()
    {
        return $this->mpdf;
    }

    /**
     * Simpan PDF ke file
     */
    public function save(string $path)
    {
        $this->mpdf->Output($path, \Mpdf\Output\Destination::FILE);
        return $path;
    }

    /**
     * Download PDF ke browser
     */
    public function download(string $filename = 'document.pdf')
    {
        return response($this->mpdf->Output($filename, 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    /**
     * Stream PDF langsung ke browser
     */
    public function stream(string $filename = 'document.pdf')
    {
        return response($this->mpdf->Output($filename, 'S'))
            ->header('Content-Type', 'application/pdf');
    }

    /**
     * Proxy ke semua method mPDF asli (misal Output, SetHTMLFooter, dll)
     */
    public function __call($method, $arguments)
    {
        if ($this->mpdf && method_exists($this->mpdf, $method)) {
            return call_user_func_array([$this->mpdf, $method], $arguments);
        }
        throw new \BadMethodCallException("Method {$method} does not exist.");
    }
}
