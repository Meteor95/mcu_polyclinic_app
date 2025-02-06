<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $userPermissions = session('user_permissions_' . session('user_id'))->toArray();
        $isSuperAdmin = $this->hasPermission($userPermissions, 'super_admin', false);
        $permissionsToCheck = [
            'hasAccessBeranda' => 'akses_beranda',
            'hasAccessKasir' => 'akses_kasir',
            /* Pendaftaran */
            'hasAccessPendaftaran' => 'akses_pendaftaran',
            'hasAccessPendaftaranDaftarPeserta' => 'akses_pendaftaran_daftar_peserta',
            'hasAccessPendaftaranDaftarPasien' => 'akses_pendaftaran_daftar_pasien',
            /* Riwayat Informasi */
            'hasAccessPendaftaranFotoPasien' => 'akses_pendaftaran_foto_pasien',
            'hasAccessPendaftaranLingkunganKerja' => 'akses_pendaftaran_lingkungan_kerja',
            'hasAccessPendaftaranKecelakaanKerja' => 'akses_pendaftaran_kecelakaan_kerja',
            'hasAccessPendaftaranKebiasaanHidup' => 'akses_pendaftaran_kebiasaan_hidup',
            'hasAccessPendaftaranPenyakitTerdahulu' => 'akses_pendaftaran_penyakit_terdahulu',
            'hasAccessPendaftaranPenyakitKeluarga' => 'akses_pendaftaran_penyakit_keluarga',
            'hasAccessPendaftaranImunisasi' => 'akses_pendaftaran_imunisasi',
            /* Pemeriksaan Fisik */
            'hasAccessPemeriksaanFisik' => 'akses_pemeriksaan_fisik',
            'hasAccessTingkatKesadaran' => 'akses_pemeriksaan_fisik_tingkat_kesadaran',
            'hasAccessTandaVital' => 'akses_pemeriksaan_fisik_tanda_vital',
            'hasAccessPenglihatan' => 'akses_pemeriksaan_fisik_penglihatan',
            'hasAccessKondisiFisikKepala' => 'akses_pemeriksaan_fisik_kondisi_fisik_kepala',
            'hasAccessKondisiFisikTelinga' => 'akses_pemeriksaan_fisik_kondisi_fisik_telinga',
            'hasAccessKondisiFisikMata' => 'akses_pemeriksaan_fisik_kondisi_fisik_mata',
            'hasAccessKondisiFisikTenggorokan' => 'akses_pemeriksaan_fisik_kondisi_fisik_tenggorokan',
            'hasAccessKondisiFisikMulut' => 'akses_pemeriksaan_fisik_kondisi_fisik_mulut',
            'hasAccessKondisiFisikGigi' => 'akses_pemeriksaan_fisik_kondisi_fisik_gigi',
            'hasAccessKondisiFisikLeher' => 'akses_pemeriksaan_fisik_kondisi_fisik_leher',
            'hasAccessKondisiFisikThorax' => 'akses_pemeriksaan_fisik_kondisi_fisik_thorax',
            'hasAccessKondisiFisikAbdomenUrogenital' => 'akses_pemeriksaan_fisik_kondisi_fisik_abdomen_urogenital',
            'hasAccessKondisiFisikAnorectalGenital' => 'akses_pemeriksaan_fisik_kondisi_fisik_anorectal_genital',
            'hasAccessKondisiFisikEkstremitas' => 'akses_pemeriksaan_fisik_kondisi_fisik_ekstremitas',
            'hasAccessKondisiFisikNeurologis' => 'akses_pemeriksaan_fisik_kondisi_fisik_neurologis',
            /* Poliklinik */
            'hasAccessPoliklinik' => 'akses_poliklinik',
            'hasAccessSpirometri' => 'akses_spirometri',
            'hasAccessAudiometri' => 'akses_audiometri',
            'hasAccessEkg' => 'akses_ekg',
            'hasAccessThreadmill' => 'akses_threadmill',
            'hasAccessRontgenThorax' => 'akses_rontgen_thorax',
            'hasAccessRontgenLumbosacral' => 'akses_rontgen_lumbosacral',
            'hasAccessUSGUbdomain' => 'akses_usg_ubdomain',
            'hasAccessFarminghamScore' => 'akses_farmingham_score',
            /* Laboratorium */
            'hasAccessTarifLaboratorium' => 'akses_tarif_laboratorium',
            'hasAccessKategoriLaboratorium' => 'akses_kategori_laboratorium',
            'hasAccessSatuanLaboratorium' => 'akses_satuan_laboratorium',
            'hasAccessRentangKenormalanLaboratorium' => 'akses_rentang_kenormalan_laboratorium',
            'hasAccessRentangTemplating' => 'akses_rentang_templating',
            /* Pemeriksaan */
            'hasAccessTindakanLaboratorium' => 'akses_tindakan_laboratorium',
            'hasAccessTindakanObat' => 'akses_tindakan_obat',
            /* Laporan */
            'hasAccessValidasiMcu' => 'akses_validasi_mcu',
            'hasAccessArciveMCU' => 'akses_berkas_tindakan_mcu',
            'hasAccessArciveLaboratorium' => 'akses_berkas_tindakan_laboratorium',
            'hasAccessArciveNota' => 'akses_berkas_tindakan_kwitansi',
            'hasAccessBerkasMCU' => 'akses_berkas_tindakan_mcu',
            'hasAccessLaporanPenjualan' => 'akses_laporan_penjualan',
            'hasAccessLaporanHutang' => 'akses_laporan_hutang',
            /* Master Data */
            'hasAccessMasterData' => 'akses_master_data',
            'hasAccessMasterPerusahaan' => 'akses_master_perusahaan',
            'hasAccessPaketHarga' => 'akses_paket_harga',
            'hasAccessJasaPelayanan' => 'akses_jasa_pelayanan',
            'hasAccessDepartemenPeserta' => 'akses_departemen_peserta',
            'hasAccessMemberMcu' => 'akses_member_mcu',
            'hasAccessDaftarBank' => 'akses_daftar_bank',
            /* Petugas */
            'hasAccessPetugas' => 'akses_petugas',
            'hasAccessPenggunaAplikasi' => 'akses_pengguna_aplikasi',
            'hasAccessHakAkses' => 'akses_hak_akses',
            'hasAccessPermission' => 'akses_hak_permission',
        ];
        $permissionsShared = [];
        foreach ($permissionsToCheck as $key => $permissionName) {
            $permissionsShared[$key] = $this->hasPermission($userPermissions, $permissionName, $isSuperAdmin);
        }
        if ($this->hasPermission($userPermissions, $permission, $isSuperAdmin)) {
            view()->share($permissionsShared);
            return $next($request);
        }
        return abort(403, 'Unauthorized');
    }
    function hasPermission($permissions, $permissionName, $isSuperAdmin) {
        return $isSuperAdmin || in_array($permissionName, array_column($permissions, 'name'));
    }
}
