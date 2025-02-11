var global_akses_tindakan = [];
function setDefaultDate(namaid,mundurberapatahunkebelakakang) {
    let currentDate = new Date();
    currentDate.setFullYear(currentDate.getFullYear() - mundurberapatahunkebelakakang);
    let year = currentDate.getFullYear();
    let month = ('0' + (currentDate.getMonth() + 1)).slice(-2);
    let day = ('0' + currentDate.getDate()).slice(-2);
    let hours = ('0' + currentDate.getHours()).slice(-2);
    let minutes = ('0' + currentDate.getMinutes()).slice(-2);
    let formattedDate = `${year}-${month}-${day}T${hours}:${minutes}`;
    document.getElementById(namaid).value = formattedDate;
}
function debounce(func, delay) {
    let timeoutId;
    return function(...args) {
        const context = this;
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            func.apply(context, args);
        }, delay);
    };
}
/* call global select2 search by member */
function callGlobalSelect2SearchByMember(namaid){
    $.get('/generate-csrf-token', function(response) {
        $('#'+namaid).select2({ 
            placeholder: 'Masukan informasi member seperti Nomor Identitas / Nama',
            allowClear: true,
            ajax: {
                url: baseurlapi + '/masterdata/daftarmembermcu',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                method: 'GET',
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        _token : response.csrf_token,
                        parameter_pencarian : (typeof params.term === "undefined" ? "" : params.term),
                        start : 0,
                        length : 100,
                    }
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: `[${item.nomor_identitas}] - ${item.nama_peserta}`,
                                id: `${item.nomor_identitas}`,
                            }
                        })
                    }
                    
                },
                error: function(xhr, status, error) {
                    return createToast('Kesalahan Penggunaan', 'top-right', xhr.responseJSON.message, 'error', 3000);
                }
            },
        }); 
    });  
}
$("#pencarian_member_mcu").on('change', function() {
    if ($(this).attr('id') === 'pencarian_member_mcu' && !$(this).hasClass('override')) {
        $.get('/generate-csrf-token', function(response) {
            $.ajax({
                url: baseurlapi + '/pendaftaran/getdatapasien',
                type: 'GET',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                data: {
                    _token : response.csrf_token,
                    nomor_identitas : $("#pencarian_member_mcu").val()
                },
                success: function(response) {
                    if (response.success) {
                        $(".formulir_group").removeClass('blur-grayscale');
                        let div_formulir_group = document.querySelector('.formulir_group');
                        if (div_formulir_group){
                            let elemen = div_formulir_group.querySelectorAll('input, button, select, textarea, a');
                            elemen.forEach((item) => {
                                item.disabled = false;
                            });
                            $(".formulir_group_button").show();
                        }
                        let currentLocation = window.location.pathname;
                        let pathParts = currentLocation.split('/').filter(Boolean);
                        if (response.data.akses_tindakan != null){
                            izinkan_akses_tindakan(response.data.akses_tindakan,pathParts);
                        }
                        $("#id_transaksi_mcu").text(response.data.id_transaksi);
                        $("#user_id_temp").text(response.data.user_id);
                        $("#nomor_identitas_temp").text(response.data.nomor_identitas);
                        $("#nama_peserta_temp_1").text(response.data.nama_peserta);
                        $("#nama_peserta_temp").text(response.data.nama_peserta);
                        $("#no_telepon_temp").text(response.data.no_telepon);
                        $("#jenis_kelamin_temp").text(response.data.jenis_kelamin);
                        $("#nomor_transaksi_temp").text(response.data.no_transaksi);
                        $("#email_temp").text(response.data.email);
                        $("#tempat_lahir_temp").text(response.data.company_name);
                        $("#status_kawin_temp").text(response.data.nama_departemen);
                    }
                }
            });
        });
    }
});
function izinkan_akses_tindakan(hak_akses,pathParts){
    let div_formulir_group = document.querySelector('.formulir_group');
    let elemen = div_formulir_group.querySelectorAll('input, button, select, textarea, a');
    let cari_hak_akses = ""
    if (pathParts[1] === "foto_pasien") {
        cari_hak_akses = "foto_data_diri";
    } else if (pathParts[1] === "lingkungan_kerja") {
        cari_hak_akses = "lingkungan_kerja";
    } else if (pathParts[1] === "kecelakaan_kerja") {
        cari_hak_akses = "kecelakaan_kerja";
    } else if (pathParts[1] === "kebiasaan_hidup") {
        cari_hak_akses = "kebiasaan_hidup";
    } else if (pathParts[1] === "penyakit_terdahulu") {
        cari_hak_akses = "penyakit_terdahulu";
    } else if (pathParts[1] === "penyakit_keluarga") {
        cari_hak_akses = "penyakit_keluarga";
    } else if (pathParts[1] === "imunisasi") {
        cari_hak_akses = "imunisasi";
    } else if (pathParts[1] === "tingkat_kesadaran") {
        cari_hak_akses = "tingkat_kesadaran";
    } else if (pathParts[1] === "penglihatan") {
        cari_hak_akses = "penglihatan";
    } else if (pathParts[1] === "tanda_vital") {
        cari_hak_akses = "poliklinik_tanda_vital";
    } else if (pathParts[1] === "kondisi_fisik") {
        if (pathParts[2] === "kepala"){
            cari_hak_akses = "kondisi_fisik_kepala"; 
        }else if (pathParts[2] === "telinga") {
            cari_hak_akses = "kondisi_fisik_telinga";
        } else if (pathParts[2] === "mata") {
            cari_hak_akses = "kondisi_fisik_mata";
        } else if (pathParts[2] === "tenggorokan") {
            cari_hak_akses = "kondisi_fisik_tenggorokan";
        } else if (pathParts[2] === "mulut") {
            cari_hak_akses = "kondisi_fisik_mulut";
        } else if (pathParts[2] === "gigi") {
            cari_hak_akses = "kondisi_fisik_gigi";
        } else if (pathParts[2] === "leher") {
            cari_hak_akses = "kondisi_fisik_leher";
        } else if (pathParts[2] === "thorax") {
            cari_hak_akses = "kondisi_fisik_thorax";
        } else if (pathParts[2] === "abdomen_urogenital") {
            cari_hak_akses = "kondisi_fisik_abdomen_ugenital";
        } else if (pathParts[2] === "anorectal_genital") {
            cari_hak_akses = "kondisi_fisik_anorectal_genitalia";
        } else if (pathParts[2] === "ekstremitas") {
            cari_hak_akses = "kondisi_fisik_ekstremitas";
        } else if (pathParts[2] === "neurologis") {
            cari_hak_akses = "kondisi_fisik_neurologis";
        }
    } else if (pathParts[1] === "spirometri"){
        cari_hak_akses = "poliklinik_spirometri";
    } else if (pathParts[1] === "audiometri"){
        cari_hak_akses = "poliklinik_audiometri";
    } else if (pathParts[1] === "ekg"){
        cari_hak_akses = "poliklinik_ekg";
    } else if (pathParts[1] === "threadmill"){
        cari_hak_akses = "poliklinik_threadmill";
    } else if (pathParts[1] === "rontgen_thorax"){
        cari_hak_akses = "poliklinik_rontgen_thorax";
    } else if (pathParts[1] === "rontgen_lumbosacral"){
        cari_hak_akses = "poliklinik_rontgen_lumbosacral";
    } else if (pathParts[1] === "usg_ubdomain"){
        cari_hak_akses = "poliklinik_usg_ubdomain";
    } else if (pathParts[1] === "farmingham_score"){
        cari_hak_akses = "poliklinik_farmingham_score";
    }
    let hasil = JSON.parse(hak_akses).find(item => item.akses === cari_hak_akses);
    if (hasil.status.toLowerCase() === "true"){
        $(".formulir_group").removeClass('blur-grayscale');
        elemen.forEach((item) => {
            item.disabled = false;
        });
        $(".formulir_group_button").show();
    }else{
        $(".formulir_group").addClass('blur-grayscale');
        elemen.forEach((item) => {
            item.disabled = true;
        });
        $(".formulir_group_button").hide();
    }
}
