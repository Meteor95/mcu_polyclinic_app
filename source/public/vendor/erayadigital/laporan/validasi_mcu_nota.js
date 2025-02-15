const pemeriksaanConfig = [
    { id: 'riwayat_medis', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Riwayat Medis peserta ini' },
    { id: 'pemeriksaan_fisik', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Fisik peserta ini' },
    { id: 'pemeriksaan_laboratorium', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Laboratorium peserta ini' },
    { id: 'pemeriksaan_threadmill', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Threadmill peserta ini' },
    { id: 'pemeriksaan_rontgen_thorax', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Rontgen Thorax peserta ini' },
    { id: 'pemeriksaan_rontgen_lumbosacral', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Rontgen Lumbosacral peserta ini' },
    { id: 'pemeriksaan_usg_ubdomain', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan USG Ubdomain peserta ini' },
    { id: 'pemeriksaan_farmingham_score', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Farmingham Score peserta ini' },
    { id: 'pemeriksaan_ekg', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan EKG peserta ini' },
    { id: 'pemeriksaan_audiometri_kiri', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Audiometri Kiri peserta ini' },
    { id: 'pemeriksaan_audiometri_kanan', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Audiometri Kanan peserta ini' },
    { id: 'pemeriksaan_spirometri_restriksi', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Spirometri Restriksi peserta ini' },
    { id: 'pemeriksaan_spirometri_obstruksi', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Spirometri Obstruksi peserta ini' },
    { id: 'pemeriksaan_tindakan_saran', placeholder: 'Berikan penjelasan mengenai status kesimpulan dari Pemeriksaan Tindakan Saran peserta ini' }
];
let pemeriksaan_laboratorium_kondisi_select_id = document.getElementById('pemeriksaan_laboratorium_kondisi_select');
let choice_pemeriksaan_laboratorium_kondisi_select = new Choices(pemeriksaan_laboratorium_kondisi_select_id, {
    searchEnabled: true,
    shouldSort: false,
    placeholder: true,
    placeholderValue: 'Pilih Status Kesimpulan Laboratorium',
});
let quillInstances = {};
$(window).scroll(function() {
    let scrollTop = $(this).scrollTop();
    if (scrollTop >= 300) {
        $('#card-sticky-validasi-mcu-nota').css('position', 'fixed').css('top', '85px');
    } else {
        $('#card-sticky-validasi-mcu-nota').css('position', '').css('top', '0');
    }
});
const quill_detail = new Quill('#editor_riwayat_kecelakaan_kerja', {
    theme: 'snow'
});
const quill_informasi = new Quill('#detail_kesimpulan_informasi', {
    theme: 'snow'
});
$(document).ready(function() {
    load_data_document();
    pemeriksaanConfig.forEach(item => {
        quillInstances[item.id] = new Quill(`#${item.id}_quill`, {
            placeholder: item.placeholder,
            readOnly: true
        });
    });
});
function updateProgress(selector, condition, text, response = null) {
    console.log(response);
    let detail_transaksi_code = '';
    const icon = condition 
        ? '<i style="color:green" class="fa-regular fa-thumbs-up fa-lg"></i>' 
        : '<i style="color:red" class="fa-regular fa-thumbs-down fa-lg"></i>';
    $(selector).html(icon + ' ' + text);
    if (text == 'LAB') {

        if (response.detail_informasi_user) {
            detail_transaksi_code = encodeURIComponent(btoa(response.detail_informasi_user.id+'|'+response.detail_informasi_user.no_mcu+'|'+response.detail_informasi_user.nomor_identitas+'|'+response.detail_informasi_user.nama_peserta));
            $(selector+"_bawah").html(`<a href="/laboratorium/tindakan?paramter_tindakan=${detail_transaksi_code}" target="_blank" class="btn btn-amc-orange w-100"><i class="fa fa-edit"></i> Buka Tindakan</a>`);
        }else{
            let detail_transaksi_code = "YIHAE@example.com";
        }
    }
}
function load_data_document() {
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/laporan/validasi_mcu_nota',
            type: 'GET',
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
            },
            data: {
                _token: response.csrf_token,
                no_nota: no_mcu_js,
            },
            success: function(response) {
                /* Riwayat Informasi */
                updateProgress('.progress_fdd', response.jumlah_data_foto_data_diri > 0, 'FDD');
                updateProgress('.progress_lk', response.jumlah_data_lingkungan_kerja > 0, 'LK');
                updateProgress('.progress_kk', response.jumlah_data_kecelakaan_kerja > 0, 'KK');
                updateProgress('.progress_kh', response.jumlah_data_kebiasaan_hidup > 0, 'KH');
                updateProgress('.progress_pt', response.jumlah_data_penyakit_terdahulu > 0, 'PT');
                updateProgress('.progress_pk', response.jumlah_data_penyakit_keluarga > 0, 'PK');
                updateProgress('.progress_im', response.jumlah_data_imunisasi > 0, 'IM');
                /* Pemeriksaan Fisik */
                updateProgress('.progress_tk', response.jumlah_data_tingkat_kesehatan > 0, 'TK');
                updateProgress('.progress_tv', response.jumlah_data_tanda_tanda_vital > 0, 'TV');
                updateProgress('.progress_eye', response.jumlah_data_penglihatan > 0, 'EYE');
                updateProgress('.progress_kp', response.jumlah_data_kepala > 0, 'KP');
                updateProgress('.progress_tlg', response.jumlah_data_telinga > 0, 'TLG');
                updateProgress('.progress_mt', response.jumlah_data_mata > 0, 'MT');
                updateProgress('.progress_tng', response.jumlah_data_tenggorokan > 0, 'TNG');
                updateProgress('.progress_mlt', response.jumlah_data_mulut > 0, 'MLT');
                updateProgress('.progress_gg', response.jumlah_data_gigi > 0, 'GG');
                updateProgress('.progress_lhr', response.jumlah_data_leher > 0, 'LHR');
                updateProgress('.progress_thx', response.jumlah_data_thorax > 0, 'THX');
                updateProgress('.progress_anu', response.jumlah_data_abdomen_urogenital > 0, 'AnU');
                updateProgress('.progress_ang', response.jumlah_data_anorectal_genital > 0, 'AnG');
                updateProgress('.progress_etm', response.jumlah_data_ekstremitas > 0, 'ETM');
                updateProgress('.progress_nu', response.jumlah_data_neurologis > 0, 'NU');
                /* Poliklinik */
                updateProgress('.progress_sp', response.jumlah_data_spirometri > 0, 'SP');
                updateProgress('.progress_ekg', response.jumlah_data_ekg > 0, 'EKG');
                updateProgress('.progress_tm', response.jumlah_data_threadmill > 0, 'TM');
                updateProgress('.progress_rsn_thorax', response.jumlah_data_rontgen_thorax > 0, 'THX');
                updateProgress('.progress_rsn_lumbosacral', response.jumlah_data_rontgen_lumbosacral > 0, 'LBS');
                updateProgress('.progress_usg_ubdomain', response.jumlah_data_usg_ubdomain > 0, 'USG');
                updateProgress('.progress_farmingham_score', response.jumlah_data_farmingham_score > 0, 'FS');
                updateProgress('.progress_au', response.jumlah_data_audiometri > 0, 'AU');
                /* Lab */
                updateProgress('.progress_lab', response.jumlah_data_lab > 0, 'LAB', response);
                if (!response.detail_informasi_user) {
                    $('#error_transaksi').show();
                    return createToast('Tidak Ada Tindakan', 'top-right', 'Pengguna ini tidak memiliki data Tindakan. Silahkan lakukan tindakan pada menu TINDAKAN baik MCU atau NON MCU', 'error', 3000);
                }
                $("#btn_lab_url").attr('href', response.url_lab);
                $('#data_transaksi').show();
                if (response.data_foto_diri) {
                    $('#foto_peserta_mcu').attr('src', response.data_foto_diri);
                }
                $("#select_lab_mcu_verifikasi").val(response.detail_informasi_user.status_peserta).trigger('change');
                $('#nama_peserta').html(response.detail_informasi_user.nama_peserta);
                $('#umur_peserta').html(response.detail_informasi_user.umur);  
                $('#harga_paket').html(parseInt(response.detail_informasi_user.total_transaksi).toLocaleString('id-ID'));
                $('#nama_peserta_card').html(response.detail_informasi_user.nama_peserta);
                $('#alamat_peserta').html(response.detail_informasi_user.alamat);
                $('#kedatangan').html(response.detail_informasi_user.kedatangan);
                $('#terakhir_datang').html(moment(response.detail_informasi_user.terakhir_datang).format('DD-MM-YYYY'));
                $('#valuasi').html(formatAngkaSingkatan(parseInt(response.detail_informasi_user.valuasi)).toLocaleString('id-ID'));
                $('#nomor_identitas_tabel').html(response.detail_informasi_user.nomor_identitas);
                $('#tanggal_mcu_tabel').html(moment(response.detail_informasi_user.waktu_trx).format('DD-MM-YYYY HH:mm:ss'));
                $('#ttl_tabel').html(response.detail_informasi_user.tempat_lahir + ', ' + moment(response.detail_informasi_user.tanggal_lahir).format('DD-MM-YYYY'));
                $('#jenis_identitas_tabel').html(response.detail_informasi_user.tipe_identitas);
                $('#status_perkawinan_tabel').html(response.detail_informasi_user.status_kawin);
                $('#asal_perusahaan_tabel').html(response.detail_informasi_user.company_name);
                $('#departemen_tabel').html(response.detail_informasi_user.nama_departemen);
            },
            error: function(xhr, status, error) {
                createToast('Kesalahan Penghapusan Data', 'top-right', error, 'error', 3000);
            }
        })
    })
}
$("#btn_lab_mcu_batal").click(function() {
    validasi_rekap_kesimpulan_nota(no_mcu_js);
});
function validasi_rekap_kesimpulan_nota(no_mcu_js) {
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/laporan/validasi_rekap_kesimpulan',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
                id_mcu_let: "",
                nomor_mcu_let: atob(no_mcu_js),
            },
            success: function(response) {
                if (response.data_poliklinik.count_poliklinik_spirometri > 0) {
                    $(".pemeriksaan_spirometri").show();
                }
                if (response.data_poliklinik.count_poliklinik_ekg > 0) {
                    $(".pemeriksaan_ekg").show();
                }
                if (response.data_poliklinik.count_poliklinik_threadmill > 0) {
                    $(".pemeriksaan_threadmill").show();
                }
                if (response.data_poliklinik.count_poliklinik_rontgen_thorax > 0) {
                    $(".pemeriksaan_rontgen_thorax").show();
                }
                if (response.data_poliklinik.count_poliklinik_rontgen_lumbosacral > 0) {
                    $(".pemeriksaan_rontgen_lumbosacral").show();
                }
                if (response.data_poliklinik.count_poliklinik_usg_ubdomain > 0) {
                    $(".pemeriksaan_usg_ubdomain").show();
                }
                if (response.data_poliklinik.count_poliklinik_farmingham_score > 0) {
                    $(".pemeriksaan_farmingham_score").show();
                }
                if (response.data_poliklinik.count_poliklinik_audiometri > 0) {
                    $(".pemeriksaan_audiometri").show();
                }
                if (response.data && Object.keys(response.data).length > 0) {
                    quillInstances['riwayat_medis'].setContents(JSON.parse(response.data.kesimpulan_riwayat_medis));
                    quillInstances['pemeriksaan_fisik'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_fisik));
                    choice_pemeriksaan_laboratorium_kondisi_select.setChoiceByValue(response.data.status_pemeriksaan_laboratorium);
                    quillInstances['pemeriksaan_laboratorium'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_laboratorum));
                    quillInstances['pemeriksaan_threadmill'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_threadmill));
                    quillInstances['pemeriksaan_rontgen_thorax'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_rontgen_thorax));
                    quillInstances['pemeriksaan_rontgen_lumbosacral'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_rontgen_lumbosacral));
                    quillInstances['pemeriksaan_usg_ubdomain'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_usg_ubdomain));
                    quillInstances['pemeriksaan_farmingham_score'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_farmingham_score));
                    quillInstances['pemeriksaan_ekg'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_ekg));
                    quillInstances['pemeriksaan_audiometri_kiri'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_audio_kiri));
                    quillInstances['pemeriksaan_audiometri_kanan'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_audio_kanan));
                    quillInstances['pemeriksaan_spirometri_restriksi'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_spiro_restriksi));
                    quillInstances['pemeriksaan_spirometri_obstruksi'].setContents(JSON.parse(response.data.kesimpulan_pemeriksaan_spiro_obstruksi));
                    $("#kesimpulan_keseluruhan").html(response.data.kesimpulan_hasil_medical_checkup.toUpperCase().replaceAll('_', ' '));
                    $("#status_kesehatan").html(response.data_kesimpulan_tindakan_status.status+" "+response.data_kesimpulan_tindakan_status.kategori+" ["+response.data_kesimpulan_tindakan_status.catatan+"]");
                    quillInstances['pemeriksaan_tindakan_saran'].setContents(JSON.parse(response.data.saran_keseluruhan));
                }
                $("#modal_validasi_rekap_kesimpulan_text").html('Validasi Kesimpulan Tindakan Pasien');
                $("#modal_validasi_rekap_kesimpulan").modal('show');
            },
            error: function(xhr, status, error) {
                createToast('Kesalahan Validasi Kesimpulan', 'top-right', error, 'error', 3000);
            }
        });
    });
}
function process_ajax(kondisi,modal,lokasi_fisik = null){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/laporan/validasi_mcu_modal',
            type: 'GET',
            beforeSend: function(xhr) {
                xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
            },
            data: {
                _token: response.csrf_token,
                no_nota: no_mcu_js,
                kondisi: kondisi,
            },
            success: function(response) {
                aksesmodal(response,modal,lokasi_fisik);
            },
            error: function(xhr, status, error) {
                createToast('Kesalahan Penghapusan Data', 'top-right', error, 'error', 3000);
            }
        })
    })
}
function aksesmodal(response,modal,lokasi_fisik = null){
    if (modal == 'modalLihatFoto') {
        $("#foto_lihat").attr('src', response.informasi_mcu.data_foto);
        $("#nama_peserta_foto").text(response.informasi_mcu.lokasi_gambar);
    }
    if (modal == 'modalLingkunganKerja') {
        if (response.informasi_mcu.length == 0) {
            return createToast('Validasi Lingkungan Kerja', 'top-right', 'Peserta ini tidak memiliki data Lingkungan Kerja. Silahkan lakukan pengisian data Lingkungan Kerja pada menu yang disediakan sesuai dengan hak akses dan informasi yang dimiliki', 'error', 3000);
        }
        $('#datatables_riwayat_lingkungan_kerja_modal tbody').empty();
        for (let i = 0; i < response.informasi_mcu.length; i++) {
            let item = response.informasi_mcu[i];
            let row = $('#datatables_riwayat_lingkungan_kerja_modal tbody tr').filter(function() {
                return $(this).find('td:eq(0)').text() == item.id_atribut_lk;
            });
            if (row.length === 0) {
                $('#datatables_riwayat_lingkungan_kerja_modal tbody').append(`
                    <tr>
                        <td>${item.nama_atribut_saat_ini}</td>
                        <td>${item.status == 0 ? 'Tidak' : 'Ya'}</td>
                        <td>${item.nilai_jam_per_hari}</td>
                        <td>${item.nilai_selama_x_tahun}</td>
                        <td>${item.keterangan ? item.keterangan : 'Tidak Ada Keterangan'}</td>
                    </tr>
                `);
            }
        }
    }
    if (modal == 'modalKecelakaanKerja') {
        quill_detail.setContents(JSON.parse(response.informasi_mcu[0].riwayat_kecelakaan_kerja));
    }
    if (modal == 'modalKebiasaanHidup') {
        $('#datatables_riwayat_kebiasaan_hidup_modal tbody').empty();
        $('#datatables_riwayat_kebiasaan_hidup_perempuan_modal tbody').empty();
        for (let i = 0; i < response.informasi_mcu.length; i++) {
            let item = response.informasi_mcu[i];
            let row = $('#datatables_riwayat_kebiasaan_hidup_modal tbody tr').filter(function() {
                return $(this).find('td:eq(0)').text() == item.id_atribut_kb;
            });
            if (row.length === 0 && item.jenis_kebiasaan == 1) {
                $('#datatables_riwayat_kebiasaan_hidup_modal tbody').append(`
                    <tr>
                        <td>${item.nama_kebiasaan}</td>
                        <td>${item.status_kebiasaan == 0 ? 'Tidak' : 'Ya'}</td>
                        <td>${item.nilai_kebiasaan}</td>
                        <td>${item.satuan_kebiasaan}</td>
                        <td>${item.keterangan ? item.keterangan : 'Tidak Ada Keterangan'}</td>
                    </tr>
                `);
            }
            if (row.length === 0 && item.jenis_kebiasaan == 2) {
                $('#datatables_riwayat_kebiasaan_hidup_perempuan_modal tbody').append(`
                    <tr>
                        <td>${item.nama_kebiasaan}</td>
                        <td>${item.status_kebiasaan == 0 ? 'Tidak' : 'Ya'}</td>
                        <td>${moment(item.waktu_kebiasaan).format('DD-MM-YYYY HH:mm:ss')}</td>
                        <td>${item.satuan_kebiasaan}</td>
                        <td>${item.keterangan ? item.keterangan : 'Tidak Ada Keterangan'}</td>
                    </tr>
                `);
            }
        }    
    }
    if (modal == 'modalPenyakitTerdahulu') {
        $('#datatables_penyakit_terdahulu_modal tbody').empty();
        for (let i = 0; i < response.informasi_mcu.length; i++) {
            let item = response.informasi_mcu[i];
            let row = $('#datatables_penyakit_terdahulu_modal tbody tr').filter(function() {
                return $(this).find('td:eq(0)').text() == item.id_atribut_pt;
            });
            if (row.length === 0) {
                $('#datatables_penyakit_terdahulu_modal tbody').append(`
                    <tr>
                        <td>${item.nama_atribut_saat_ini}</td>
                        <td>${item.status == 0 ? 'Tidak' : 'Ya'}</td>
                        <td>${item.keterangan ? item.keterangan : 'Tidak Ada Keterangan'}</td>
                    </tr>
                `);
            }
        }   
    }
    if (modal == 'modalPenyakitKeluarga') {
        $('#datatables_riwayat_penyakit_keluarga_modal tbody').empty();
        for (let i = 0; i < response.informasi_mcu.length; i++) {
            let item = response.informasi_mcu[i];
            let row = $('#datatables_riwayat_penyakit_keluarga_modal tbody tr').filter(function() {
                return $(this).find('td:eq(0)').text() == item.id_atribut_pk;
            });
            if (row.length === 0) {
                $('#datatables_riwayat_penyakit_keluarga_modal tbody').append(`
                    <tr>
                        <td>${item.nama_atribut_saat_ini}</td>
                        <td>${item.status == 0 ? 'Tidak' : 'Ya'}</td>
                        <td>${item.keterangan ? item.keterangan : 'Tidak Ada Keterangan'}</td>
                    </tr>
                `);
            }
        }           
    }
    if (modal == 'modalImunisasi') {
        $('#datatables_imunisasi_modal tbody').empty();
        $("#modal_nama_peserta_parameter").text(response.informasi_mcu[0].nama_peserta);
        for (let i = 0; i < response.informasi_mcu.length; i++) {
            let item = response.informasi_mcu[i];
            let row = $('#datatables_imunisasi_modal tbody tr').filter(function() {
                return $(this).find('td:eq(0)').text() == item.id_atribut_im;
            });
            if (row.length === 0) {
                $('#datatables_imunisasi_modal tbody').append(`
                    <tr>
                        <td>${item.nama_atribut_saat_ini}</td>
                        <td>${item.status == 0 ? 'Tidak' : 'Ya'}</td>
                        <td>${item.keterangan ? item.keterangan : 'Tidak Ada Keterangan'}</td>
                    </tr>
                `);
            }
        }     
    }
    if (modal == 'modalTingkatKesadaran') {
        $("#modal_keadaan_umum_temp").text(response.informasi_mcu[0].nama_atribut_tingkat_kesadaran);
        $("#modal_keterangan_keadaan_umum_temp").text(response.informasi_mcu[0].keterangan_tingkat_kesadaran);
        $("#modal_status_kesadaran_temp").text(response.informasi_mcu[0].nama_atribut_status_tingkat_kesadaran);
        $("#modal_keterangan_status_kesadaran_temp").text(response.informasi_mcu[0].keterangan_status_tingkat_kesadaran);
        $("#modal_keluhan_temp").text(response.informasi_mcu[0].keluhan);
    }
    if (modal == 'modalTandaVital') {
        $('#datatables_tanda_vital_modal_tanda_vital tbody').empty();
        $('#datatables_tanda_vital_modal_tanda_gizi tbody').empty();
        for (let i = 0; i < response.informasi_mcu.length; i++) {
            let no = i + 1;
            let item = response.informasi_mcu[i];
            let row = $('#datatables_tanda_vital_modal_tanda_vital tbody tr').filter(function() {
                return $(this).find('td:eq(0)').text() == item.id_atribut_lv;
            });
            if (row.length === 0 && item.jenis_tanda_vital === 'tanda_vital') {
                $('#datatables_tanda_vital_modal_tanda_vital tbody').append(`
                    <tr>
                        <td>${no}</td>
                        <td>${item.nama_atribut_saat_ini}</td>
                        <td>${item.nilai_tanda_vital} ${item.satuan_tanda_vital}</td>
                        <td>${item.keterangan_tanda_vital ? item.keterangan_tanda_vital : 'Tidak Ada Keterangan'}</td>
                    </tr>
                `);
            }
            if (row.length === 0 && item.jenis_tanda_vital === 'tanda_gizi') {
                $('#datatables_tanda_vital_modal_tanda_gizi tbody').append(`
                    <tr>
                        <td>${no}</td>
                        <td>${item.nama_atribut_saat_ini}</td>
                        <td>${item.nilai_tanda_vital} ${item.satuan_tanda_vital}</td>
                        <td>${item.keterangan_tanda_vital ? item.keterangan_tanda_vital : 'Tidak Ada Keterangan'}</td>
                    </tr>
                `);
            }
        } 
    }
    if (modal == 'modalPenglihatan') {
        $("#visus_os_tanpa_kacamata_jauh_modal").text(response.informasi_mcu[0].visus_os_tanpa_kacamata_jauh);
        $("#visus_od_tanpa_kacamata_jauh_modal").text(response.informasi_mcu[0].visus_od_tanpa_kacamata_jauh);
        $("#visus_os_kacamata_jauh_modal").text(response.informasi_mcu[0].visus_os_kacamata_jauh);
        $("#visus_od_kacamata_jauh_modal").text(response.informasi_mcu[0].visus_od_kacamata_jauh);
        $("#visus_os_tanpa_kacamata_dekat_modal").text(response.informasi_mcu[0].visus_os_tanpa_kacamata_dekat);
        $("#visus_od_tanpa_kacamata_dekat_modal").text(response.informasi_mcu[0].visus_od_tanpa_kacamata_dekat);
        $("#visus_os_kacamata_dekat_modal_modal").text(response.informasi_mcu[0].visus_os_kacamata_dekat);
        $("#visus_od_kacamata_dekat_modal_modal").text(response.informasi_mcu[0].visus_od_kacamata_dekat);
        $("#buta_warna_modal").text(response.informasi_mcu[0].buta_warna.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' '));
        $("#lapang_pandang_superior_os_modal").text(response.informasi_mcu[0].lapang_pandang_superior_os);
        $("#lapang_pandang_inferior_os_modal").text(response.informasi_mcu[0].lapang_pandang_inferior_os);
        $("#lapang_pandang_temporal_os_modal").text(response.informasi_mcu[0].lapang_pandang_temporal_os);
        $("#lapang_pandang_nasal_os_modal").text(response.informasi_mcu[0].lapang_pandang_nasal_os);
        $("#lapang_pandang_keterangan_os_modal").text(response.informasi_mcu[0].lapang_pandang_keterangan_os);
        $("#lapang_pandang_superior_od_modal").text(response.informasi_mcu[0].lapang_pandang_superior_od);
        $("#lapang_pandang_inferior_od_modal").text(response.informasi_mcu[0].lapang_pandang_inferior_od);
        $("#lapang_pandang_temporal_od_modal").text(response.informasi_mcu[0].lapang_pandang_temporal_od);
        $("#lapang_pandang_nasal_od_modal").text(response.informasi_mcu[0].lapang_pandang_nasal_od);
        $("#lapang_pandang_keterangan_od_modal").text(response.informasi_mcu[0].lapang_pandang_keterangan_od);
    }
    if (modal == 'modalFisik') {
        $('#datatables_kondisi_fisik_log_modal tbody').empty();
        $("#modal_fisik_lokasi").text(lokasi_fisik.charAt(0).toUpperCase() + lokasi_fisik.slice(1));
        if (lokasi_fisik === 'Gigi') {
            const data = response.informasi_mcu_gigi[0];
            ['atas', 'bawah'].forEach(posisi => {
                ['kanan', 'kiri'].forEach(sisi => {
                    for (let i = 1; i <= 8; i++) {
                        const key = `${posisi}_${sisi}_${i}`;
                        $(`#${key}_modal`).text(data[key]); 
                    }
                });
            });
            $('#modal_fisik_gigi').show();
        }else{
            $('#modal_fisik_gigi').hide();
        }
        for (let i = 0; i < response.informasi_mcu.length; i++) {
            let item = response.informasi_mcu[i];
            let row = $('#datatables_kondisi_fisik_log_modal tbody tr').filter(function() {
                return $(this).find('td:eq(0)').text() == item.id_atribut_pt;
            });
            if (row.length === 0) {
                $('#datatables_kondisi_fisik_log_modal tbody').append(`
                    <tr>
                        <td>${(i+1)}</td>
                        <td>${item.jenis_atribut}</td>
                        <td style="text-align: center;">${item.status_atribut === 'abnormal' ? '✅' : '❌'}</td>
                        <td style="text-align: center;">${item.status_atribut === 'normal' ? '✅' : '❌'}</td>
                        <td>${item.keterangan_atribut ? item.keterangan_atribut : 'Tidak Ada Keterangan'}</td>
                    </tr>
                `);
            }
        }                    
    }
    if (modal == 'modalPoliklinik') {
        $("#modal_poliklinik_nama").html(lokasi_fisik);
        $("#judul_laporan_informasi").html(response.informasi_mcu.judul_laporan);
        $("#kesimpulan_informasi").html(response.informasi_mcu.kesimpulan);
        quill_informasi.setContents(JSON.parse(response.informasi_mcu.detail_kesimpulan));
    }
    if (modal == 'modalLab') {
        
    }
    $("#"+modal).modal('show');
}
$("#btn_lab_mcu_verifikasi").click(function(){
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Validasi Akhir Dokumen Ini</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin validasi akhir dokumen ini menjadi <strong>"'+$("#select_lab_mcu_verifikasi option:selected").text()+'</strong> ?. Dokumen yang tervalidasi SELESAI dan VALID baru dapat dijadikan laporan MCU dan dicetak serta dapat diberikan kepada peserta atau pihak yang membutuhkan</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Validasi Akhir',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/laporan/validasi_mcu_nota_akhir',
                    type: 'GET',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token: response.csrf_token,
                        mcu_transaksi_id: no_mcu_js,
                        status: $("#select_lab_mcu_verifikasi").val(),
                        status_text: $("#select_lab_mcu_verifikasi option:selected").text()
                    },
                    success: function(response) {
                        createToast('Validasi Akhir Dokumen', 'top-right', response.message, 'success', 3000);
                        if (response.success) {
                            $("#modal_lab_mcu_verifikasi").modal('hide');
                        }
                    },
                    error: function(xhr, status, error) {
                        createToast('Kesalahan Validasi Akhir Dokumen', 'top-right', error, 'error', 3000);
                    }
                });
            });
        }
    })
})