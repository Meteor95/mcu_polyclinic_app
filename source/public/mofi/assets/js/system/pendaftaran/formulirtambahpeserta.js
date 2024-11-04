$(document).ready(function() {
    callselect2mcu()
    updateCardStyles()
    onload()
    
});
function onload(){
    flatpickr("#tanggal_lahir", {
        dateFormat: "d-m-Y",
        maxDate: moment().subtract(15, 'years').format('DD-MM-YYYY'),
    });
    $('#tanggal_pendaftaran').val(moment().format("DD-MM-YYYY"));
    flatpickr("#tanggal_pendaftaran", {
        dateFormat: "d-m-Y",
        maxDate: "today",
    });
    const nominalbayar = new AutoNumeric('#nominal_bayar, #nominal_kembalian', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 2,
    });
}
$("#btnKonfirmasiPendaftaran").on("click", function() {
   $("#modalKonfimasiPendaftaran").modal("show");
});
function updateCardStyles() {
    if ($('#hutang').is(':checked')) {
        $('#card-hutang').css({'border': '2px solid #ccc', 'background-color': '#f8f9fa'});
        $('#card-tunai').css({'border': '', 'background-color': ''});
        $('#pembayaran_tunai').hide();
    } else if ($('#tunai').is(':checked')) {
        $('#card-tunai').css({'border': '2px solid #ccc', 'background-color': '#f8f9fa'});
        $('#card-hutang').css({'border': '', 'background-color': ''});
        $('#pembayaran_tunai').show();
    }
}
$('input[name="metode_pembayaran"]').on('change', updateCardStyles);
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
                                id: `${item.id}|${item.kode_paket}|${item.nama_paket}|${item.harga_paket}|${item.akses_poli}`, // Tambahkan item.akses_poli ke ID
                            };
                        })
                    };
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
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
                    
                }
            },
        }); 
    });
}
$('#select2_paket_mcu').on('select2:select', function (e) {
    const select2Container = document.querySelector('.select2-container');
    $('#akses_poli_dipilih').text(select2Container.innerText);
    $('#akses_poli_dipilih_konfirmasi').text(select2Container.innerText);
});
$('#select2_paket_mcu').on('select2:select', function (e) {
    const selectedData = e.params.data.id.split('|');
    const aksesPoli = selectedData[4];
    const aksesPoliElement = $('#akses_poli_dipilih');
    const aksesPoliElementKonfirmasi = $('#akses_poli_dipilih_konfirmasi');
    if (aksesPoliElement) {
        const poliList = aksesPoli.split(',');
        aksesPoliElement.empty();
        aksesPoliElement.addClass('text-center');
        poliList.forEach(poli => {
            aksesPoliElement.append(`<span class="badge badge-success me-2">${poli.trim()}</span>`);
        });
    }
    if (aksesPoliElementKonfirmasi) {
        const poliListKonfirmasi = aksesPoli.split(',');
        aksesPoliElementKonfirmasi.empty();
        aksesPoliElementKonfirmasi.append(`<div class="d-flex justify-content-center flex-wrap">`);
        poliListKonfirmasi.forEach(poli => {
            aksesPoliElementKonfirmasi.append(`<button class="btn btn-success me-2 mb-2">${poli.trim()}</button>`);
        });
        aksesPoliElementKonfirmasi.append(`</div>`);
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
                if (response.status == 404) {
                    Swal.fire({
                        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/8a0f0bc2-25f9-446a-b59c-3d8b15262c0a/kSttVfRFiv.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4> <strong>'+$(nama_peserta).val()+'</strong> ?. Jika sudah silahkan tentukan paket MCU',
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
                            $("#alamat").val(response.data.alamat);
                            $("#no_telepon").val(response.data.no_telepon);
                            $("#email").val(response.data.email);
                        }
                    });
                }else{
                    createToast('Informasi peserta berhasil diambil', 'top-right', xhr.responseJSON.message, 'success', 3000);
                }
            },
            error: function(xhr, status, error){
                createToast('Kesalahan Cek Data', 'top-right', xhr.responseJSON.message, 'error', 3000);
            }
        });
    });
});