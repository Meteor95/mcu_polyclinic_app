let formValidasi = $('#formulir_pendaftaran_peserta');
let id_paket_mcu;
$(document).ready(function() {
    flatpickr("#tanggal_lahir_peserta", {
        dateFormat: "d-m-Y",
        maxDate: moment().subtract(15, 'years').format('DD-MM-YYYY'),
    });
    flatpickr("#tanggal_pendaftaran", {
        dateFormat: "d-m-Y",
        maxDate: "today",
    });
    callselect2mcu()
    
});
$("#btnKonfirmasiPendaftaran").on("click", function(event) {
    event.preventDefault();
    formValidasi.addClass('was-validated');
    if ($("#nomor_identitas").val() == "" || $("#nama_peserta").val() == "" || $("#tempat_lahir").val() == "" || $("#tanggal_lahir_peserta").val() == "" || $("#select2_perusahaan").val() == "" || $("#select2_departemen").val() == "") {
        return createToast('Kesalahan Penggunaan', 'top-right', 'Silahkan masukan data peserta untuk peserta ini sebelum anda melakukan transaksi selanjutnya', 'error', 3000);
    }
    if ($("#jenis_transaksi_pendaftaran").val() == "MCU") {
        id_paket_mcu = $('#select2_paket_mcu').val();
    }else{
        id_paket_mcu = 1;
    }
    if ($('#select2_perusahaan').val() == null || $('#select2_departemen').val() == null) {
        return createToast('Kesalahan Penggunaan', 'top-right', 'Tentukan asalah dari perusahaan dan departemen peserta sebelum anda melakukan pendaftaran', 'error', 3000);
    }
    let selectedProsesKerja = [];
    $('input[name="proses_kerja"]:checked').each(function() {
        selectedProsesKerja.push($(this).val());
    });
    if (selectedProsesKerja.length === 0) {
        return createToast('Kesalahan Penggunaan', 'top-right', "Dalam pendaftaran peserta, harap pilih proses kerja terlebih dahulu minimal 1 atau Kombinasi", 'error', 3000);
    }
    if ($('#jenis_transaksi_pendaftaran').val() == 'MCU') {
        if ($('#select2_paket_mcu').val() == null) {
            return createToast('Kesalahan Penggunaan', 'top-right', 'Peserta memilih MCU, harap tentukan paket MCU yang akan diambil oleh peserta sebelum anda melakukan pendaftaran untuk melanjutkan transaksi selanjutnya', 'error', 3000);
        }
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Formulir Pendaftaran Peserta</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan formulir informasi Pendaftaran Peserta dengan atas nama : <strong>' + $("#nama_peserta").val() + '</strong> ?</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Daftarkan Peserta',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response){
                $.ajax({
                    url: baseurlapi + '/transaksi/simpanpeserta',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    method: 'POST', 
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    data: (function() {
                        const formData = new FormData();
                        formData.append('id_detail_transaksi_mcu', id_detail_transaksi_mcu);
                        formData.append('isedit', isedit);
                        formData.append('nomor_identitas', $('#nomor_identitas').val());
                        formData.append('nama_peserta', $('#nama_peserta').val());
                        formData.append('tempat_lahir', $('#tempat_lahir').val());
                        formData.append('tanggal_lahir_peserta', $('#tanggal_lahir_peserta').val());
                        formData.append('tipe_identitas', $('#tipe_identitas').val());
                        formData.append('status_kawin', $('#status_kawin').val());
                        formData.append('jenis_kelamin', $('#jenis_kelamin').val());
                        formData.append('no_telepon', $('#no_telepon').val());
                        formData.append('email', $('#email').val());
                        formData.append('alamat', $('#alamat').val());
                        formData.append('_token', response.csrf_token);
                        formData.append('type_data_peserta', $('#type_data_peserta').val());
                        formData.append('tanggal_transaksi', $('#tanggal_pendaftaran').val());
                        formData.append('jenis_transaksi_pendaftaran', $('#jenis_transaksi_pendaftaran').val());
                        formData.append('perusahaan_id', $('#select2_perusahaan').val());
                        formData.append('departemen_id', $('#select2_departemen').val());
                        formData.append('id_paket_mcu', id_paket_mcu);
                        formData.append('proses_kerja', JSON.stringify(selectedProsesKerja));
                        return formData;
                    })(),
                    success: function(response_data){
                        if (response_data.rc == 200) {
                            createToast('Berhasil', 'top-right', response_data.message, 'success', 3000);
                            return Swal.fire({
                                html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/bf2bdd2d-1dac-4285-aa3d-9548be13b15d/zzf9qF3Q23.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player>Informasi berhasil disimpan ke dalam sistem MCU Artha Medica. Silahkan tambah informasi detail MCU berdasarkan Nomor Indetitas yang sudah didaftarkan ['+$('#nomor_identitas').val()+']. Aksi apa yang ingin anda lakukan selanjutnya?<div>',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: 'orange',
                                confirmButtonText: 'Lihat Data Pasien',
                                cancelButtonText: 'Tambah Data Baru',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '/pendaftaran/daftar_pasien';
                                }else{
                                    window.location.reload();
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error){
                        createToast('Kesalahan Cek Data', 'top-right', xhr.responseJSON.message, 'error', 3000);
                    }
                });
            });
        }
    });
});
function callselect2mcu(){
    $.get('/generate-csrf-token', function(response) {
        $('#select2_perusahaan').select2({ 
            placeholder: 'Pilih Perusahaan',
            ajax: {
                url: baseurlapi + '/masterdata/daftarperusahaan',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                method: 'GET',
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        _token : response.csrf_token,
                        parameter_pencarian : (typeof params.term === "undefined" ? "" : params.term),
                        start : 0,
                        length : 1000,
                    }
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: `[${item.company_code}] - ${item.company_name}`,
                                id: item.id,
                            }
                        })
                    }
                    
                },
                error: function(xhr, status, error) {
                    return createToast('Kesalahan Penggunaan', 'top-right', xhr.responseJSON.message, 'error', 3000);
                }
            },
        }); 
        $('#select2_paket_mcu').select2({
            placeholder: 'Pilih Paket MCU',
            ajax: {
                url: baseurlapi + '/masterdata/daftarpaketmcu',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                method: 'GET',
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        _token: response.csrf_token,
                        parameter_pencarian: params.term || "",
                        start: 0,
                        length: 1000,
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: `[${item.kode_paket}] - ${item.nama_paket} | Harga : Rp ${new Intl.NumberFormat('id-ID').format(item.harga_paket)}`,
                                id: `${item.id}|${item.kode_paket}|${item.nama_paket}|${item.harga_paket}`,
                            };
                        })
                    };
                },
                error: function(xhr, status, error) {
                    return createToast('Kesalahan Penggunaan', 'top-right', xhr.responseJSON.message, 'error', 3000);
                }
            }
        });
        $('#select2_departemen').select2({ 
            placeholder: 'Pilih Departemen',
            ajax: {
                url: baseurlapi + '/masterdata/daftardepartemenpeserta',
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                method: 'GET',
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        _token : response.csrf_token,
                        parameter_pencarian : (typeof params.term === "undefined" ? "" : params.term),
                        start : 0,
                        length : 1000,
                    }
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: `[${item.kode_departemen}] - ${item.nama_departemen}`,
                                id: `${item.id}`,
                            }
                        })
                    }
                    
                },
                error: function(xhr, status, error) {
                    return createToast('Kesalahan Penggunaan', 'top-right', xhr.responseJSON.message, 'error', 3000);
                }
            },
        }); 
        $('#pencarian_member_mcu').select2({ 
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
                        tipe: 1,
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
$("#jenis_transaksi_pendaftaran").on('change', function(){
    if ($('#jenis_transaksi_pendaftaran').val() != 'MCU') {
        $('#select2_paket_mcu').prop('disabled', true);
        $('#select2_paket_mcu').val(null).trigger('change');
    }else{
        $('#select2_paket_mcu').prop('disabled', false);
    }
});
$("#btnIsiFormulirPakaiDataIni").on("click", function(){
    $.get('/generate-csrf-token', function(response){
        $.ajax({
            url: baseurlapi + '/pendaftaran/getdatapeserta',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            method: 'GET',
            dataType: 'json',
            data: {
                _token: response.csrf_token,
                nomor_identitas: $("#nomor_identitas_temp").html(),
            },
            success: function(response){
                if (response.rc == 200) {
                    Swal.fire({
                        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/bf2bdd2d-1dac-4285-aa3d-9548be13b15d/zzf9qF3Q23.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player>'+response.message_info+'<div>',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: 'orange',
                        confirmButtonText: 'Gunakan Data Ini',
                        cancelButtonText: 'Nanti Dulu!!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("#nomor_identitas").val(response.data.nomor_identitas);
                            $("#nama_peserta").val(response.data.nama_peserta);
                            $("#jenis_kelamin").val(response.data.jenis_kelamin);
                            $("#tempat_lahir").val(response.data.tempat_lahir);
                            $("#tanggal_lahir_peserta").val(moment(response.data.tanggal_lahir).format('DD-MM-YYYY'));
                            $("#alamat").val(response.data.alamat);
                            $("#no_telepon").val(response.data.no_telepon);
                            $("#email").val(response.data.email);
                            $("#jenis_kelamin").val(response.data.jenis_kelamin).trigger('change');
                            $("#status_kawin").val(response.data.status_kawin).trigger('change');
                            $("#tipe_identitas").val(response.data.tipe_identitas).trigger('change');
                        }
                    });
                }else{
                    Swal.fire({
                        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/8a0f0bc2-25f9-446a-b59c-3d8b15262c0a/kSttVfRFiv.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player>'+response.message_info+'<div>',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: 'orange',
                        confirmButtonText: 'Daftar dan Gunakan Data Ini',
                        cancelButtonText: 'Nanti Dulu!!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("#nomor_identitas").val(response.data.nomor_identitas);
                            $("#nama_peserta").val(response.data.nama_peserta);
                            $("#jenis_kelamin").val(response.data.jenis_kelamin);
                            $("#tempat_lahir").val(response.data.tempat_lahir);
                            $("#tanggal_lahir_peserta").val(moment(response.data.tanggal_lahir).format('DD-MM-YYYY'));
                            $("#alamat").val(response.data.alamat);
                            $("#no_telepon").val(response.data.no_telepon);
                            $("#email").val(response.data.email);
                            $("#jenis_kelamin").val(response.data.jenis_kelamin).trigger('change');
                            $("#status_kawin").val(response.data.status_kawin).trigger('change');
                            $("#tipe_identitas").val(response.data.tipe_identitas).trigger('change');
                        }
                    });
                }
            },
            error: function(xhr, status, error){
                createToast('Kesalahan Cek Data', 'top-right', xhr.responseJSON.message, 'error', 3000);
            }
        });
    });
});
$('#pencarian_member_mcu').on('select2:select', function (e) {
    let selectedData = e.params.data;
    $.get('/generate-csrf-token', function(response){
        $.ajax({
            url: baseurlapi + '/pendaftaran/getdatapeserta',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            method: 'GET',
            dataType: 'json',
            data: {
                _token: response.csrf_token,
                nomor_identitas: selectedData.id,
            },
            success: function(response){
                if (response.rc == 200) {
                    Swal.fire({
                        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/bf2bdd2d-1dac-4285-aa3d-9548be13b15d/zzf9qF3Q23.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player>'+response.message_info+'<div>',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: 'orange',
                        confirmButtonText: 'Gunakan Data Ini',
                        cancelButtonText: 'Nanti Dulu!!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("#nomor_identitas").val(response.data.nomor_identitas);
                            $("#nama_peserta").val(response.data.nama_peserta);
                            $("#jenis_kelamin").val(response.data.jenis_kelamin);
                            $("#tempat_lahir").val(response.data.tempat_lahir);
                            $("#tanggal_lahir_peserta").val(moment(response.data.tanggal_lahir).format('DD-MM-YYYY'));
                            $("#alamat").val(response.data.alamat);
                            $("#no_telepon").val(response.data.no_telepon);
                            $("#email").val(response.data.email);
                            $("#jenis_kelamin").val(response.data.jenis_kelamin).trigger('change');
                            $("#status_kawin").val(response.data.status_kawin).trigger('change');
                            $("#tipe_identitas").val(response.data.tipe_identitas).trigger('change');
                        }
                    });
                }
            },
            error: function(xhr, status, error){
                createToast('Kesalahan Cek Data', 'top-right', xhr.responseJSON.message, 'error', 3000);
            }
        });
    });
});