let table, isedit;
$(document).ready(function(){
    isedit = false;
    callGlobalSelect2SearchByMember('pencarian_member_mcu');
    onload_datatables_penglihatan();
});
function onload_datatables_penglihatan(){
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_penglihatan").DataTable({
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
                "url": baseurlapi + '/pemeriksaan_fisik/daftar_penglihatan',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $("#kotak_pencarian_penglihatan").val();
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
                            Tanggal Transaksi Penglihatan: ${row.tanggal_transaksi_penglihatan}<br>
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
                                <button onclick="detail_penglihatan('${row.user_id}','${row.transaksi_id}','${row.nomor_identitas}','${row.nama_peserta}')" class="btn btn-success w-100">
                                    <i class="fa fa-eye"></i> Lihat Data
                                </button>
                                <button onclick="detail_penglihatan_tabel('${row.user_id}','${row.transaksi_id}','${row.nomor_identitas}','${row.nama_peserta}')" class="btn btn-primary w-100">
                                    <i class="fa fa-edit"></i> Ubah Data
                                </button>
                                <button onclick="hapus_penglihatan('${row.transaksi_id}','${row.nomor_identitas}','${row.nama_peserta}','${row.user_id}')" class="btn btn-danger w-100">
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
$("#simpan_penglihatan").on('click', function(){
    if ($("#pencarian_member_mcu").val() == null) {
        return createToast('Kesalahan Penyimpanan','top-right','Silahkan tentukan peserta MCU terlebih dahulu sebelum menyimpan data atas formulir bahaya riwayat lingkungan kerja diatas','error',3000);
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Simpan Formulir Test Penglihatan</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan formulir informasi Test Penglihatan Peserta MCU dengan atas nama : <strong>' + $("#nama_peserta_temp_1").text() + '</strong> ?</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/pemeriksaan_fisik/simpanpenglihatan',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token : response.csrf_token,
                        isedit : isedit,
                        transaksi_id : $("#id_transaksi_mcu").text(),
                        user_id : $("#user_id_temp").text(),
                        visus_os_tanpa_kacamata_jauh : $("#visus_os_tanpa_kacamata_jauh").val(),
                        visus_od_tanpa_kacamata_jauh : $("#visus_od_tanpa_kacamata_jauh").val(),
                        visus_os_kacamata_jauh : $("#visus_os_kacamata_jauh").val(),
                        visus_od_kacamata_jauh : $("#visus_od_kacamata_jauh").val(),
                        visus_os_tanpa_kacamata_dekat : $("#visus_os_tanpa_kacamata_dekat").val(),
                        visus_od_tanpa_kacamata_dekat : $("#visus_od_tanpa_kacamata_dekat").val(),
                        visus_os_kacamata_dekat : $("#visus_os_kacamata_dekat").val(),
                        visus_od_kacamata_dekat : $("#visus_od_kacamata_dekat").val(),
                        buta_warna : $('input[name="buta_warna"]:checked').val(),
                        lapang_pandang_superior_os : $("#lapang_pandang_superior_os").val(),
                        lapang_pandang_inferior_os : $("#lapang_pandang_inferior_os").val(),
                        lapang_pandang_temporal_os : $("#lapang_pandang_temporal_os").val(),
                        lapang_pandang_nasal_os : $("#lapang_pandang_nasal_os").val(),
                        lapang_pandang_keterangan_os : $("#lapang_pandang_keterangan_os").val(),
                        lapang_pandang_superior_od : $("#lapang_pandang_superior_od").val(),
                        lapang_pandang_inferior_od : $("#lapang_pandang_inferior_od").val(),
                        lapang_pandang_temporal_od : $("#lapang_pandang_temporal_od").val(),
                        lapang_pandang_nasal_od : $("#lapang_pandang_nasal_od").val(),
                        lapang_pandang_keterangan_od : $("#lapang_pandang_keterangan_od").val(),
                    },
                    success: function(response) {
                        if (response.success){
                            clear_penglihatan();
                            $("#datatables_penglihatan").DataTable().ajax.reload();
                            return createToast('LKP VIsus Penglihatan', 'top-right', response.message, 'success', 3000);
                        }
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
                    },
                });
            });
        }
    });
});
function clear_penglihatan(){
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
    $("#visus_os_tanpa_kacamata_jauh").val("");
    $("#visus_od_tanpa_kacamata_jauh").val("");
    $("#visus_os_kacamata_jauh").val("Tidak").trigger('change');
    $("#visus_od_kacamata_jauh").val("Tidak").trigger('change');
    $("#visus_os_tanpa_kacamata_dekat").val("");
    $("#visus_od_tanpa_kacamata_dekat").val("");
    $("#visus_os_kacamata_dekat").val("Tidak").trigger('change');
    $("#visus_od_kacamata_dekat").val("Tidak").trigger('change');
    $('#buta_warna_tidak').prop('checked', true); 
    $("#lapang_pandang_superior_os").val("+").trigger('change');
    $("#lapang_pandang_inferior_os").val("+").trigger('change');
    $("#lapang_pandang_temporal_os").val("+").trigger('change');
    $("#lapang_pandang_nasal_os").val("+").trigger('change');
    $("#lapang_pandang_keterangan_os").val("");
    $("#lapang_pandang_superior_od").val("+").trigger('change');
    $("#lapang_pandang_inferior_od").val("+").trigger('change');
    $("#lapang_pandang_temporal_od").val("+").trigger('change');
    $("#lapang_pandang_nasal_od").val("+").trigger('change');
    $("#lapang_pandang_keterangan_od").val("");
    $("#pencarian_member_mcu").val(null).trigger('change');
}
$("#bersihkan_penglihatan").on('click', function() {
    clear_penglihatan();
});
function hapus_penglihatan(transaksi_id, nomor_identitas, nama_peserta, user_id){
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53a48ece-27d3-4b85-9150-8005e7c27aa4/usrEqiqrei.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Hapus Data Test Penglihatan</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus data Test Penglihatan Peserta MCU dengan atas nama : <strong>' + nama_peserta + '</strong> ?</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/pemeriksaan_fisik/hapus_penglihatan',
                    type: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token : response.csrf_token,
                        transaksi_id : transaksi_id,
                        user_id : user_id,
                        nomor_identitas : nomor_identitas,
                        nama_peserta : nama_peserta,
                    },
                    success: function(response) {
                        if (response.success){
                            $("#datatables_penglihatan").DataTable().ajax.reload();
                            return createToast('Hapus Data Test Penglihatan', 'top-right', response.message, 'success', 3000);
                        }
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Hapus Data', 'top-right', error, 'error', 3000);
                    },
                });
            });
        }
    });
}
function fill_detail_penglihatan(user_id,transaksi_id,nama_peserta,detail){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/pemeriksaan_fisik/get_penglihatan',
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
                    $("#visus_os_tanpa_kacamata_jauh_modal").text(response.data.visus_os_tanpa_kacamata_jauh);
                    $("#visus_od_tanpa_kacamata_jauh_modal").text(response.data.visus_od_tanpa_kacamata_jauh);
                    $("#visus_os_kacamata_jauh_modal").text(response.data.visus_os_kacamata_jauh);
                    $("#visus_od_kacamata_jauh_modal").text(response.data.visus_od_kacamata_jauh);
                    $("#visus_os_tanpa_kacamata_dekat_modal").text(response.data.visus_os_tanpa_kacamata_dekat);
                    $("#visus_od_tanpa_kacamata_dekat_modal").text(response.data.visus_od_tanpa_kacamata_dekat);
                    $("#visus_os_kacamata_dekat_modal_modal").text(response.data.visus_os_kacamata_dekat);
                    $("#visus_od_kacamata_dekat_modal_modal").text(response.data.visus_od_kacamata_dekat);
                    $("#buta_warna_modal").text(response.data.buta_warna.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' '));
                    $("#lapang_pandang_superior_os_modal").text(response.data.lapang_pandang_superior_os);
                    $("#lapang_pandang_inferior_os_modal").text(response.data.lapang_pandang_inferior_os);
                    $("#lapang_pandang_temporal_os_modal").text(response.data.lapang_pandang_temporal_os);
                    $("#lapang_pandang_nasal_os_modal").text(response.data.lapang_pandang_nasal_os);
                    $("#lapang_pandang_keterangan_os_modal").text(response.data.lapang_pandang_keterangan_os);
                    $("#lapang_pandang_superior_od_modal").text(response.data.lapang_pandang_superior_od);
                    $("#lapang_pandang_inferior_od_modal").text(response.data.lapang_pandang_inferior_od);
                    $("#lapang_pandang_temporal_od_modal").text(response.data.lapang_pandang_temporal_od);
                    $("#lapang_pandang_nasal_od_modal").text(response.data.lapang_pandang_nasal_od);
                    $("#lapang_pandang_keterangan_od_modal").text(response.data.lapang_pandang_keterangan_od);
                    $("#modalLihatParameter").modal('show');
                }else{
                    createToast('LKP MCU', 'top-right', response.message, 'success', 3000);
                    $("#visus_os_tanpa_kacamata_jauh").val(response.data.visus_os_tanpa_kacamata_jauh);
                    $("#visus_od_tanpa_kacamata_jauh").val(response.data.visus_od_tanpa_kacamata_jauh);
                    $("#visus_os_kacamata_jauh").val(response.data.visus_os_kacamata_jauh);
                    $("#visus_od_kacamata_jauh").val(response.data.visus_od_kacamata_jauh);
                    $("#visus_os_tanpa_kacamata_dekat").val(response.data.visus_os_tanpa_kacamata_dekat);
                    $("#visus_od_tanpa_kacamata_dekat").val(response.data.visus_od_tanpa_kacamata_dekat);
                    $("#visus_os_kacamata_dekat").val(response.data.visus_os_kacamata_dekat);
                    $("#visus_od_kacamata_dekat").val(response.data.visus_od_kacamata_dekat);
                    $(`input[name="buta_warna"][value="${response.data.buta_warna}"]`).prop('checked', true);
                    $("#lapang_pandang_superior_os").val(response.data.lapang_pandang_superior_os);
                    $("#lapang_pandang_inferior_os").val(response.data.lapang_pandang_inferior_os);
                    $("#lapang_pandang_temporal_os").val(response.data.lapang_pandang_temporal_os);
                    $("#lapang_pandang_nasal_os").val(response.data.lapang_pandang_nasal_os);
                    $("#lapang_pandang_keterangan_os").val(response.data.lapang_pandang_keterangan_os);
                    $("#lapang_pandang_superior_od").val(response.data.lapang_pandang_superior_od);
                    $("#lapang_pandang_inferior_od").val(response.data.lapang_pandang_inferior_od);
                    $("#lapang_pandang_temporal_od").val(response.data.lapang_pandang_temporal_od);
                    $("#lapang_pandang_nasal_od").val(response.data.lapang_pandang_nasal_od);
                    $("#lapang_pandang_keterangan_od").val(response.data.lapang_pandang_keterangan_od);
                }
            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Penyimpanan', 'top-right', xhr.responseJSON.message, 'error', 3000);
            }
        });
    });
}
function detail_penglihatan(user_id,transaksi_id,nama_peserta){
    isedit = false;
    fill_detail_penglihatan(user_id,transaksi_id,nama_peserta,true);
}
function detail_penglihatan_tabel(user_id, transaksi_id, nomor_identitas, nama_peserta){
    isedit = true;
    let newOption = new Option('['+nomor_identitas+'] - '+nama_peserta, nomor_identitas, true, false);
    $("#pencarian_member_mcu").append(newOption).trigger('change');
    $("#pencarian_member_mcu").val(nomor_identitas).trigger('change');
    fill_detail_penglihatan(user_id,transaksi_id,nama_peserta);
}