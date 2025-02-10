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
    switch(pathParts[1]){
        case "foto_pasien":
            cari_hak_akses = "foto_data_diri";
            break;
        case "lingkungan_kerja":
            cari_hak_akses = "lingkungan_kerja";
            break;
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
