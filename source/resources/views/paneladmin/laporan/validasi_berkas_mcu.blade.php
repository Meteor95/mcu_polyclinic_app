<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Validasi Berkas MCU</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
        <style>
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }
            .page {
                background: #f0f2f8;
                min-height: 100vh;
                font-family: "Nunito", sans-serif;
            }
            .topbar {
                background: #1e2a4a;
                padding: 13px 20px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .logo {
                display: flex;
                align-items: center;
                gap: 9px;
            }
            .logo-box {
                width: 32px;
                height: 32px;
                background: #e8410a;
                border-radius: 7px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 800;
                color: #fff;
                font-size: 11px;
            }
            .logo-txt {
                color: #fff;
                font-weight: 800;
                font-size: 14px;
            }
            .logo-txt span {
                color: #f97c3c;
            }
            .scan-tag {
                font-size: 10px;
                color: rgba(255, 255, 255, 0.4);
                border: 1px solid rgba(255, 255, 255, 0.15);
                padding: 3px 9px;
                border-radius: 20px;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .body {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px 16px 48px;
            }

            .status-card {
                border-radius: 16px;
                overflow: hidden;
                border: 1.5px solid #e5e7eb;
                background: #fff;
                margin-bottom: 16px;
            }

            .banner-asli {
                background: #f0fdf4;
                padding: 24px 20px;
                display: flex;
                align-items: center;
                gap: 14px;
                border-bottom: 1.5px solid #bbf7d0;
            }
            .banner-palsu {
                background: #fff1f2;
                padding: 24px 20px;
                display: flex;
                align-items: center;
                gap: 14px;
                border-bottom: 1.5px solid #fecdd3;
            }

            .big-icon {
                width: 56px;
                height: 56px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                flex-shrink: 0;
            }
            .big-icon.ok {
                background: #dcfce7;
            }
            .big-icon.no {
                background: #fee2e2;
            }

            .verdict-asli {
                font-size: 20px;
                font-weight: 800;
                color: #15803d;
                line-height: 1.1;
            }
            .verdict-palsu {
                font-size: 20px;
                font-weight: 800;
                color: #b91c1c;
                line-height: 1.1;
            }
            .verdict-sub {
                font-size: 12px;
                color: #6b7280;
                margin-top: 4px;
                line-height: 1.5;
            }
            .verdict-sub b {
                color: #374151;
            }

            .card-section {
                padding: 16px 20px;
            }
            .sec-label {
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.7px;
                color: #9ca3af;
                margin-bottom: 10px;
                display: flex;
                align-items: center;
                gap: 5px;
            }
            .row {
                display: flex;
                justify-content: space-between;
                align-items: baseline;
                padding: 7px 0;
                border-bottom: 1px solid #f3f4f6;
            }
            .row:last-child {
                border: none;
            }
            .rk {
                font-size: 12px;
                color: #6b7280;
            }
            .rv {
                font-size: 13px;
                font-weight: 700;
                color: #1e2a4a;
                text-align: right;
            }
            .divider {
                height: 1px;
                background: #f3f4f6;
            }

            .badge {
                display: inline-flex;
                align-items: center;
                gap: 3px;
                padding: 2px 9px;
                border-radius: 20px;
                font-size: 11px;
                font-weight: 700;
            }
            .bg {
                background: #dcfce7;
                color: #15803d;
            }
            .bb {
                background: #dbeafe;
                color: #1d4ed8;
            }
            .bp {
                background: #ede9fe;
                color: #5b21b6;
            }
            .br {
                background: #fee2e2;
                color: #b91c1c;
            }

            .pem-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 5px;
                margin-top: 2px;
            }
            .pem {
                padding: 5px 8px;
                border-radius: 7px;
                font-size: 11px;
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 5px;
            }
            .pem.ok {
                background: #f0fdf4;
                color: #15803d;
            }
            .pem.no {
                background: #fff1f2;
                color: #b91c1c;
            }
            .pem.nd {
                background: #f5f3ff;
                color: #5b21b6;
            }

            .warn {
                background: #fef9c3;
                border: 1px solid #fde68a;
                border-radius: 10px;
                padding: 11px 13px;
                font-size: 12px;
                color: #a16207;
                display: flex;
                gap: 8px;
                line-height: 1.5;
            }
            .danger {
                background: #fff1f2;
                border: 1px solid #fecdd3;
                border-radius: 10px;
                padding: 11px 13px;
                font-size: 12px;
                color: #b91c1c;
                display: flex;
                gap: 8px;
                line-height: 1.5;
                margin-top: 8px;
            }

            .stamp-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 12px 20px;
                background: #f8fafc;
                border-top: 1px solid #f3f4f6;
            }
            .stamp-left {
                font-size: 11px;
                color: #9ca3af;
                line-height: 1.6;
            }
            .stamp-left b {
                color: #374151;
                display: block;
                font-size: 12px;
            }
            .stamp-qr {
                width: 40px;
                height: 40px;
                background: #e5e7eb;
                border-radius: 6px;
                display: grid;
                place-items: center;
                font-size: 9px;
                color: #9ca3af;
                text-align: center;
                line-height: 1.3;
            }

            .toggle-btns {
                display: flex;
                gap: 8px;
                margin-bottom: 16px;
            }
            .toggle-btn {
                flex: 1;
                padding: 9px;
                border-radius: 10px;
                border: 1.5px solid #e5e7eb;
                background: #fff;
                font-size: 12px;
                font-weight: 700;
                font-family: "Nunito", sans-serif;
                cursor: pointer;
                transition: all 0.2s;
                color: #6b7280;
            }
            .toggle-btn.active-asli {
                border-color: #16a34a;
                background: #f0fdf4;
                color: #15803d;
            }
            .toggle-btn.active-palsu {
                border-color: #dc2626;
                background: #fff1f2;
                color: #b91c1c;
            }

            .footer {
                text-align: center;
                font-size: 11px;
                color: #9ca3af;
                margin-top: 20px;
                line-height: 1.8;
            }
        </style>
        <link
            href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap"
            rel="stylesheet"
        />
    </head>
    <body>
        <div class="page">
            <div class="topbar">
                <div class="logo">
                    <div class="logo-box">AMC</div>
                    <div class="logo-txt">Arta <span>Medica</span> Centre</div>
                </div>
                <div class="scan-tag">📷 Hasil Scan QR</div>
            </div>
            <div class="body">
                <div class="toggle-btns">
                    <button class="toggle-btn {{ $isValid ? 'active-asli' : '' }}">✅ Tampilan Dokumen Asli</button>
                    <button class="toggle-btn {{ !$isValid ? 'active-palsu' : '' }}">❌ Tampilan Dokumen Palsu</button>
                </div>
                <div id="view-asli" style="{{ $isValid ? '' : 'display:none' }}">
                    <div class="status-card">
                        <div class="banner-asli">
                            <div class="big-icon ok">✅</div>
                            <div>
                                <div class="verdict-asli">Dokumen Asli dan Terdaftar</div>
                                <div class="verdict-sub">
                                    Berkas ini telah terverifikasi dan dinyatakan <b style="color: red">SAH</b> oleh
                                    Arta Medica Centre. Pastikan informasi yang ada dokumentasi ini sesuai dengan dasar
                                    informasi yang kami berikan
                                </div>
                            </div>
                        </div>

                        <div class="card-section">
                            <div class="sec-label">👤 Identitas Peserta</div>

                            <div class="row">
                                <span class="rk">Nomor Medical Checkup</span>
                                <span class="rv"> {{ $nomor_mcu ?? '' }} </span>
                            </div>

                            <div class="row">
                                <span class="rk">Nama Lengkap</span>
                                <span class="rv"> {{ $informasi_data_diri->nama_peserta ?? '' }} </span>
                            </div>

                            <div class="row">
                                <span class="rk">NIK / NRR</span>
                                <span class="rv"> {{ $informasi_data_diri->nomor_identitas ?? '' }} </span>
                            </div>

                            <div class="row">
                                <span class="rk">Tempat, Tgl Lahir / Umur</span>
                                <span class="rv">
                                    {{ $informasi_data_diri->tempat_lahir ?? '' }}, {{
                                    isset($informasi_data_diri->tanggal_lahir) ? date('d-m-Y',
                                    strtotime($informasi_data_diri->tanggal_lahir)) : '' }} / {{
                                    $informasi_data_diri->umur ?? '' }} Tahun
                                </span>
                            </div>

                            <div class="row">
                                <span class="rk">Jenis Kelamin</span>
                                <span class="rv"> {{ $informasi_data_diri->jenis_kelamin ?? '' }} </span>
                            </div>

                            <div class="row">
                                <span class="rk">Perusahaan</span>
                                <span class="rv"> {{ $informasi_data_diri->company_name ?? '' }} </span>
                            </div>

                            <div class="row">
                                <span class="rk">Departemen Jabatan</span>
                                <span class="rv"> {{ $informasi_data_diri->nama_departemen ?? '' }} </span>
                            </div>

                            <div class="row">
                                <span class="rk">Alamat</span>
                                <span class="rv"> {{ $informasi_data_diri->alamat ?? '' }} </span>
                            </div>

                            <div class="row">
                                <span class="rk">Tanggal MCU / Tipe MCU</span>
                                <span class="rv">
                                    {{ isset($informasi_data_diri->tanggal_mcu) ? date('d-m-Y',
                                    strtotime($informasi_data_diri->tanggal_mcu)) : '' }} / {{
                                    $informasi_data_diri->tipe_mcu_peserta ?? '' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- VIEW PALSU --}}
                <div id="view-palsu" style="{{ !$isValid ? '' : 'display:none' }}">
                    <div class="status-card">
                        <div class="banner-palsu">
                            <div class="big-icon no">❌</div>

                            <div>
                                <div class="verdict-palsu">Dokumen TIDAK SAH</div>

                                <div class="verdict-sub">
                                    Berkas ini dinyatakan
                                    <b>tidak lolos verifikasi</b>
                                    oleh Arta Medica Centre.
                                </div>
                            </div>
                        </div>

                        <div class="card-section">
                            <div class="sec-label">⚠️ Informasi Validasi</div>

                            <div class="danger mb-3">
                                ⛔
                                <span>
                                    Dokumen tidak ditemukan, telah dimodifikasi, atau hash verifikasi tidak sesuai
                                    dengan data resmi pada sistem Arta Medica Centre.
                                </span>
                            </div>
                            <br />
                            <div class="warn">
                                ⚠️
                                <span>
                                    Segala bentuk pemalsuan, perubahan, manipulasi, atau penggunaan dokumen Medical
                                    Check Up (MCU) tanpa hak merupakan tindakan yang melanggar hukum dan dapat dikenakan
                                    sanksi pidana sesuai ketentuan peraturan perundang-undangan yang berlaku di Republik
                                    Indonesia.
                                </span>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="card-section">
                            <div class="sec-label">🔒 Status Sistem</div>

                            <div class="row">
                                <span class="rk">Status Verifikasi</span>
                                <span class="rv">
                                    <span class="badge br"> ✘ Tidak Valid </span>
                                </span>
                            </div>

                            <div class="row">
                                <span class="rk">Waktu Pemeriksaan</span>
                                <span class="rv"> {{ now()->format('d-m-Y H:i:s') }} </span>
                            </div>

                            <div class="row">
                                <span class="rk">Sumber Verifikasi</span>
                                <span class="rv"> Sistem Digital AMC </span>
                            </div>
                        </div>

                        <div class="stamp-row">
                            <div class="stamp-left">
                                <b>Sistem Verifikasi AMC</b><br />
                                Dokumen ini tidak terdaftar pada sistem resmi Arta Medica Centre.
                            </div>

                            <div class="stamp-qr">INVALID</div>
                        </div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="footer">
                    Arta Medica Centre · Portal Resmi Verifikasi Berkas MCU
                    <br />
                    Hubungi kami: support@arthamedical.com · 0823-4402-9902
                </div>
            </div>
        </div>

        <script>
            function show(which, btn) {
                document.getElementById("view-asli").style.display = which === "asli" ? "block" : "none";
                document.getElementById("view-palsu").style.display = which === "palsu" ? "block" : "none";
                document.querySelectorAll(".toggle-btn").forEach((b) => (b.className = "toggle-btn"));
                btn.className = "toggle-btn active-" + which;
            }
        </script>
    </body>
</html>
