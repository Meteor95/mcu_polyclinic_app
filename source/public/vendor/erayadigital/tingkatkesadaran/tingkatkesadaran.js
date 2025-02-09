let table, isedit;
$(document).ready(function(){
    callGlobalSelect2SearchByMember('pencarian_member_mcu');
    onload_datatables_tingkat_kesadaran();
    onloadfromnavigation(param_nomor_identitas, param_nama_peserta);
    if (param_nomor_identitas == "" && param_nama_peserta == ""){
        $("#kunci_id_tingkat_kesadaran").addClass("btn-danger").removeClass("btn-success");
    }else{
        $("#kunci_id_tingkat_kesadaran").addClass("btn-success").removeClass("btn-danger");
    }
});
$("#kunci_id_tingkat_kesadaran").on("click", function(){
    $(this).removeClass("btn-danger").addClass("btn-success");
    if ($("#nomor_identitas_temp").html() == "" || $("#nama_peserta_temp_1").html() == ""){
        $(this).removeClass("btn-success").addClass("btn-danger");
        return createToast('Kesalahan Penyimpanan', 'top-right', 'Kunci ID Navigasi hanya bisa aktif ketika ada data peserta MCU yang dipilih', 'error', 3000);
    }
    location.href = baseurl + '/pemeriksaan_fisik/tingkat_kesadaran?nomor_identitas=' + $("#nomor_identitas_temp").html() + '&nama_peserta=' + $("#nama_peserta_temp_1").html();
})
function onload_datatables_tingkat_kesadaran(){
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_tingkat_kesadaran").DataTable({
            searching: false,
            bProcessing: true,
            serverSide: true,
            pagingType: "full_numbers",
            language: {
                "paginate": {
                    "first": '<i class="fa fa-angle-double-left"></i>',
                    "last": '<i class="fa fa-angle-double-right"></i>',
                    "next": '<i class="fa fa-angle-right"></i>',
                    "previous": '<i class="fa fa-angle-left"></i>',
                },
            },
            ajax: {
                "url": baseurlapi + '/pemeriksaan_fisik/daftar_tingkat_kesadaran',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $("#kotak_pencarian_daftar_riwayat_penyakit_terdahulu").val();
                },
                "dataSrc": function(json) {
                    let detailData = json.data;
                    let mergedData = detailData.map(item => {
                        return {
                            ...item,
                            recordsFiltered: json.recordsFiltered,
                        };
                    });
                    return mergedData;
                },
            },
            infoCallback: function(settings) {
                if (typeof settings.json !== "undefined") {
                    const currentPage = Math.floor(settings._iDisplayStart / settings._iDisplayLength) + 1;
                    const recordsFiltered = settings.json.recordsFiltered;
                    const infoString = 'Hal Ke: ' + currentPage + ' Ditampilkan: ' + 10 + ' Dari Total : ' + recordsFiltered + ' Data';
                    return infoString;
                }
            },
            columnDefs: [{
                defaultContent: "-",
                targets: "_all"
            }],
            columns: [
                {
                    title: "No",
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    title: "Informasi Peserta MCU",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `Nomor MCU : ${row.no_transaksi}<br>
                            Nama Peserta : ${row.nama_peserta} (${row.umur}Th)<br>
                            Tanggal Transaksi MCU: ${row.tanggal_transaksi}<br>
                            Tanggal Transaksi Tingkat Kesadaran: ${row.tanggal_transaksi_tingkat_kesadaran}<br>
                            `;
                        }
                        return data;
                    }
                },
                {
                    title: "Informasi Pekerjaan",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `Nama Perusahaan : ${row.company_name}<br>
                            Departemen : ${row.nama_departemen}<br>
                            Alamat Perusahaan: ${row.alamat}<br>
                            `;
                        }
                        return data;
                    }
                },
                {
                    title: "Aksi",
                    className: "dtfc-fixed-right_header",
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return `<div class="d-flex justify-content-between gap-2">
                                <button onclick="detail_data_tingkat_kesadaran('${row.user_id}','${row.transaksi_id}','${row.nama_peserta}')" class="btn btn-success w-100">
                                    <i class="fa fa-eye"></i> Lihat Data
                                </button>
                                <button onclick="detail_data_tingkat_kesadaran_tabel('${row.user_id}','${row.transaksi_id}','${row.nomor_identitas}','${row.nama_peserta}')" class="btn btn-primary w-100">
                                    <i class="fa fa-edit"></i> Ubah Data
                                </button>
                                <button onclick="hapus_data_tingkat_kesadaran('${row.transaksi_id}','${row.nomor_identitas}','${row.nama_peserta}','${row.user_id}')" class="btn btn-danger w-100">
                                    <i class="fa fa-trash-o"></i> Hapus Data
                                </button>
                            </div>`;
                        }       
                        return data;
                    }
                }
            ]
        });
    });
}
function clear_form(){
    isedit = false;
    $("#user_id_temp").text("");
    $("#nomor_identitas_temp").text("");
    $("#id_transaksi_mcu").text("");
    $("#nama_peserta_temp_1").text("");
    $("#nama_peserta_temp").text("");
    $("#no_telepon_temp").text("");
    $("#jenis_kelamin_temp").text("");
    $("#nomor_transaksi_temp").text("");
    $("#email_temp").text("");
    $("#tempat_lahir_temp").text("");
    $("#status_kawin_temp").text("");
    $("#pencarian_member_mcu").val(null).trigger('change'); 
    $("#tingkat_kesadaran_keadaan_umum").val(null).trigger('change');
    $("#tingkat_kesadaran_status_kesadaran").val(null).trigger('change');
    $("#keterangan_tingkat_kesadaran_keadaan_umum").val("");
    $("#keterangan_tingkat_kesadaran_status_kesadaran").val("");
    $("#keterangan_keluhan").val("");
}

$("#tingkat_kesadaran_status_kesadaran").on("change", function () {
    const selectedOption = $(this).find(":selected");
    const keterangan = selectedOption.data("keterangan");
    const textarea = $("#keterangan_tingkat_kesadaran_status_kesadaran");
    textarea.val(keterangan || "");
    adjustTextareaHeight(textarea[0]);
});
function adjustTextareaHeight(el) {
    el.style.height = "auto";
    el.style.height = el.scrollHeight + "px";
}
$("#bersihkan_tingkat_kesadaran").on("click", function(){
    clear_form();
});
$("#simpan_tingkat_kesadaran").on("click", function(){
    if ($("#pencarian_member_mcu").val() == null){
        return createToast('Kesalahan Penyimpanan', 'top-right', 'Silahkan tentukan peserta MCU terlebih dahulu sebelum menyimpan data atas formulir bahaya riwayat lingkungan kerja diatas', 'error', 3000);
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Simpan Formulir Tingkat Kesadaran</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan formulir informasi Tingkat Kesadaran Peserta MCU dengan atas nama : <strong>'+$("#nama_peserta_temp_1").text()+'</strong> ?. Jika terjadi kesalahan silahakn ubah sesuai kebutuhan',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/pemeriksaan_fisik/simpantingkatkesadaran',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token: response.csrf_token,
                        isedit: isedit,
                        user_id: $("#user_id_temp").text(),
                        transaksi_id: $("#id_transaksi_mcu").text(),
                        id_atribut_tingkat_kesadaran: $("#tingkat_kesadaran_keadaan_umum").val(),
                        nama_atribut_tingkat_kesadaran: $("#tingkat_kesadaran_keadaan_umum option:selected").text(),
                        keterangan_tingkat_kesadaran: $("#keterangan_tingkat_kesadaran_keadaan_umum").val(),
                        id_atribut_status_tingkat_kesadaran: $("#tingkat_kesadaran_status_kesadaran").val(),
                        nama_atribut_status_tingkat_kesadaran: $("#tingkat_kesadaran_status_kesadaran option:selected").text(),
                        keterangan_status_tingkat_kesadaran: $("#keterangan_tingkat_kesadaran_status_kesadaran").val(),
                        keluhan: $("#keterangan_keluhan").val(),
                        nama_pasien: $("#nama_peserta_temp_1").text()
                    },
                    success: function(response) {
                       if (response.success){
                            clear_form();
                            $('#datatables_tingkat_kesadaran').DataTable().ajax.reload();    
                            return createToast('LKP MCU', 'top-right', response.message, 'success', 3000);
                        }
                        return createToast('LKP MCU', 'top-right', response.message, 'error', 3000);
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
                    }
                });
            });
        }
    });
});
function hapus_data_tingkat_kesadaran(transaksi_id,nomor_identitas,nama_peserta,user_id){
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53a48ece-27d3-4b85-9150-8005e7c27aa4/usrEqiqrei.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Hapus Data Tingkat Kesadaran</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus data Tingkat Kesadaran Peserta MCU dengan atas nama : <strong>'+nama_peserta+'</strong> ?. Jika terjadi kesalahan silahakn ubah sesuai kebutuhan',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response){
                $.ajax({
                    url: baseurlapi + '/pemeriksaan_fisik/hapus_tingkat_kesadaran',
                    type: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token: response.csrf_token,
                        transaksi_id: transaksi_id,
                        nomor_identitas: nomor_identitas,
                        nama_peserta: nama_peserta,
                        user_id: user_id
                    },
                    success: function(response) {
                        if (response.success){
                            clear_form();
                            $('#datatables_tingkat_kesadaran').DataTable().ajax.reload();
                            return createToast('LKP MCU', 'top-right', response.message, 'success', 3000);
                        }
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
                    }
                });
            });
        }
    });
}

function fill_form_tingkat_kesadaran_tabel(user_id,transaksi_id, nama_peserta, detail = false){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/pemeriksaan_fisik/get_tingkat_kesadaran',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
                user_id: user_id,
                transaksi_id: transaksi_id
            },
            success: function(response) {
                if (!response.success){
                    return createToast('LKP MCU', 'top-right', response.message, 'error', 3000);
                }
                if (detail){
                    $("#tingkat_kesadaran_keadaan_umum").val(response.data.id_atribut_tingkat_kesadaran).trigger('change');
                    $("#keterangan_tingkat_kesadaran_keadaan_umum").val(response.data.keterangan_tingkat_kesadaran);
                    $("#tingkat_kesadaran_status_kesadaran").val(response.data.id_atribut_status_tingkat_kesadaran).trigger('change');
                    $("#keterangan_tingkat_kesadaran_status_kesadaran").val(response.data.keterangan_status_tingkat_kesadaran);
                    $("#keterangan_keluhan").val(response.data.keluhan);
                }else{
                    $("#modal_nama_peserta_parameter").text(nama_peserta);
                    $("#modal_keadaan_umum_temp").text(response.data.nama_atribut_tingkat_kesadaran);
                    $("#modal_keterangan_keadaan_umum_temp").text(response.data.keterangan_tingkat_kesadaran);
                    $("#modal_status_kesadaran_temp").text(response.data.nama_atribut_status_tingkat_kesadaran);
                    $("#modal_keterangan_status_kesadaran_temp").text(response.data.keterangan_status_tingkat_kesadaran);
                    $("#modal_keluhan_temp").text(response.data.keluhan);
                    $("#modalLihatParameter").modal('show');
                }
            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Penyimpanan', 'top-right', xhr.responseJSON.message, 'error', 3000);
            }
        });
    });
}
function detail_data_tingkat_kesadaran_tabel(user_id,transaksi_id,nomor_identitas,nama_peserta){
    isedit = true;
    let newOption = new Option('['+nomor_identitas+'] - '+nama_peserta, nomor_identitas, true, false);
    $("#pencarian_member_mcu").append(newOption).trigger('change');
    $("#pencarian_member_mcu").val(nomor_identitas).trigger('change');
    fill_form_tingkat_kesadaran_tabel(user_id,transaksi_id,nama_peserta,true);
}
function detail_data_tingkat_kesadaran(user_id,transaksi_id,nama_peserta){
    isedit = false;
    fill_form_tingkat_kesadaran_tabel(user_id,transaksi_id,nama_peserta);
}