let status_atribut_pemeriksaan = 0,tables,isedit;
$(document).ready(function(){
    callGlobalSelect2SearchByMember('pencarian_member_mcu');
    onload_datatables();
    onload_kondisi_fisik();
});
function onload_datatables(){
    let tables = [];
    let table = $('#datatables_kondisi_fisik').DataTable({
    paging: false,
    ordering: false,
    searching: false,
    info: false,
    keys: true,
    rowGroup: {
        dataSrc: 1,
        startRender: function (rows, group) {
            return $('<tr style="background-color: #f0f0f0; font-weight: bold;font-size: 16px;">')
                .append("<td colspan='6'>Kategori Lokasi Fisik : " + group.toUpperCase() + "</td>")
                .append('</tr>');
        },
    },
    }).on('key-focus', function(e, datatable, cell, originalEvent) {
        $('input', cell.node()).focus();
    }).on("focus", "td input", function() {
        $(this).select();
    });
    tables.push(table);
    tables.forEach(function(table) {
        table.on('key', function(e, dt, code) {
            if (code === 13) {
                table.keys.move('down');
            }
        });
    });
}

function cek_ab_normal(id,kondisi) {
    if (kondisi == 0) {
        status_atribut_pemeriksaan = 0;
        $('#ab_normal_' + id).prop('checked', true);
        $('#normal_' + id).prop('checked', false);
    } else if (kondisi == 1) {
        status_atribut_pemeriksaan = 1;
        $('#ab_normal_' + id).prop('checked', false);
        $('#normal_' + id).prop('checked', true);
    }
}
function onload_kondisi_fisik(){
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_kondisi_fisik_log").DataTable({
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
                "url": baseurlapi + '/pemeriksaan_fisik/daftar_kondisi_fisik',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $("#kotak_pencarian_kondisi_fisik").val();
                    d.lokasi_fisik = lokasi_fisik_let;
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
                            Tanggal Transaksi Penglihatan: ${row.tanggal_transaksi}<br>
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
                                <button onclick="detail_kondisi_fisik('${row.user_id}','${row.transaksi_id}','${row.nama_peserta}','${row.nomor_identitas}',)" class="btn btn-success w-100">
                                    <i class="fa fa-eye"></i> Lihat Data
                                </button>
                                <button onclick="detail_kondisi_fisik_tabel('${row.user_id}','${row.transaksi_id}','${row.nama_peserta}','${row.nomor_identitas}')" class="btn btn-primary w-100">
                                    <i class="fa fa-edit"></i> Ubah Data
                                </button>
                                <button onclick="hapus_kondisi_fisik('${row.transaksi_id}','${row.nomor_identitas}','${row.nama_peserta}','${row.user_id}')" class="btn btn-danger w-100">
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
$("#simpan_kondisi_fisik").click(function(){
    if ($("#pencarian_member_mcu").val() == null) {
        return createToast('Kesalahan Penyimpanan','top-right','Silahkan tentukan peserta MCU terlebih dahulu sebelum menyimpan data atas formulir bahaya riwayat lingkungan kerja diatas','error',3000);
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Simpan Formulir Kondisi Fisik '+lokasi_fisik_let.charAt(0).toUpperCase() + lokasi_fisik_let.slice(1)+'</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan formulir informasi Kondisi Fisik '+lokasi_fisik_let.charAt(0).toUpperCase() + lokasi_fisik_let.slice(1)+' Peserta MCU dengan atas nama : <strong>' + $("#nama_peserta_temp_1").text() + '</strong> ?</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                let bulkData = [];
                let bulkDataTambahan = [];
                $("#datatables_kondisi_fisik").DataTable().rows().every(function() {
                    let row = this.data();
                    let rowId = row[0];
                    let kategori_atribut = row[1];
                    let jenis_atribut = row[2];
                    let keterangan_atribut = $('#keterangan_'+rowId).val();
                    if($('#ab_normal_'+rowId).is(':checked')){
                        status_atribut_pemeriksaan = 'abnormal';
                    }else if($('#normal_'+rowId).is(':checked')){
                        status_atribut_pemeriksaan = 'normal';
                    }
                    let rowData = {
                        _token: response.csrf_token,
                        user_id: $("#user_id_temp").text(),
                        transaksi_id: $("#id_transaksi_mcu").text(),
                        id_atribut:rowId,
                        nama_atribut:lokasi_fisik_let,
                        kategori_atribut:kategori_atribut,
                        jenis_atribut:jenis_atribut,
                        status_atribut:status_atribut_pemeriksaan,
                        keterangan_atribut:keterangan_atribut
                    };
                    bulkData.push(rowData);
                });
                if (lokasi_fisik_let.toLowerCase() === 'gigi') {
                    let rowDataTambahan = {};
                    ['atas', 'bawah'].forEach(posisi => {
                        ['kanan', 'kiri'].forEach(sisi => {
                            for (let i = 1; i <= 8; i++) {
                                const key = `${posisi}_${sisi}_${i}`;
                                rowDataTambahan[key] = $(`#${key}`).val();
                            }
                        });
                    });
                    bulkDataTambahan.push(rowDataTambahan);
                }
                $.ajax({
                    url: baseurlapi + '/pemeriksaan_fisik/simpankondisifisik',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        isedit:isedit,
                        kondisi_fisik:bulkData,
                        kondisi_fisik_tambahan:bulkDataTambahan,
                    },
                    success: function(response) {
                        if (response.success == false){
                            return createToast('Kesalahan Hapus Data', 'top-right', response.message, 'error', 3000);
                        }
                        clear_kondisi_fisik();
                        $("#datatables_kondisi_fisik_log").DataTable().ajax.reload();
                        return createToast('LKP Kondisi Fisik '+lokasi_fisik_let.charAt(0).toUpperCase() + lokasi_fisik_let.slice(1), 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
                    },
                });
            });
        }
    });
});
$("#bersihkan_kondisi_fisik").click(function(){
    clear_kondisi_fisik();
});
function clear_kondisi_fisik(){
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
    $('#datatables_kondisi_fisik input').each(function() {
        $(this).val('');
    });
    $('#datatables_kondisi_fisik input[type="checkbox"]').prop('checked', false);
    $('#datatables_gigi input').each(function() {
        $(this).val('');
    });
}
function hapus_kondisi_fisik(transaksi_id,nomor_identitas,nama_peserta,user_id){
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53a48ece-27d3-4b85-9150-8005e7c27aa4/usrEqiqrei.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Hapus Data Formulir Kondisi Fisik '+lokasi_fisik_let.charAt(0).toUpperCase() + lokasi_fisik_let.slice(1)+'</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus data Formulir Kondisi Fisik Peserta MCU dengan atas nama : <strong>' + nama_peserta + '</strong> ?</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response) {
                $.ajax({
                    url: baseurlapi + '/pemeriksaan_fisik/hapus_kondisi_fisik',
                    type: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: {
                        _token : response.csrf_token,
                        transaksi_id : transaksi_id,
                        user_id : user_id,
                        nama_peserta : nama_peserta,
                        lokasi_fisik : lokasi_fisik_let,
                    },
                    success: function(response) {
                        if (response.success){
                            clear_kondisi_fisik();
                            $("#datatables_kondisi_fisik_log").DataTable().ajax.reload();
                            return createToast('Hapus Data Formulir Kondisi Fisik '+lokasi_fisik_let.charAt(0).toUpperCase() + lokasi_fisik_let.slice(1), 'top-right', response.message, 'success', 3000);
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
function fill_detail_kondisi_fisik(user_id,transaksi_id,nama_peserta,detail=false){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/pemeriksaan_fisik/get_kondisi_fisik',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
            data: {
                _token: response.csrf_token,
                user_id: user_id,
                transaksi_id: transaksi_id,
                lokasi_fisik: lokasi_fisik_let,
            },
            success: function(response) {
                if (!response.success){
                    return createToast('LKP MCU', 'top-right', response.message, 'error', 3000);
                }
                if (detail){
                    $('#datatables_kondisi_fisik_log_modal tbody').empty();
                    $("#modal_nama_peserta_parameter").text(nama_peserta);
                    $("#modal_lokasi_fisik_parameter").text(lokasi_fisik_let);
                    for (let i = 0; i < response.data.length; i++) {
                        let item = response.data[i];
                        let row = $('#datatables_kondisi_fisik_log_modal tbody tr').filter(function() {
                            return $(this).find('td:eq(0)').text() == item.id_atribut_pt; // Adjust based on key
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
                    $("#modalLihatParameter").modal('show');
                }else{
                    createToast('LKP MCU', 'top-right', response.message, 'success', 3000);
                    response.data.forEach(function(item) {
                        let row = $('#datatables_kondisi_fisik tbody tr').filter(function() {
                            return $(this).find('td:eq(0)').text() == item.id_atribut;
                        });
                        if (response.data.length > 0) {
                            row.find('#ab_normal_' + item.id_atribut).prop('checked', item.status_atribut == 'abnormal');
                            row.find('#normal_' + item.id_atribut).prop('checked', item.status_atribut == 'normal');
                            row.find('#keterangan_' + item.id_atribut).val(item.keterangan_atribut ? item.keterangan_atribut : "");
                            row.find('td:eq(1)').text(item.nama_atribut_saat_ini);
                        }
                    });
                }
                if (typeof detail_addons_kondisi_fisik === "function") {
                    detail_addons_kondisi_fisik(lokasi_fisik_let, response.data, detail);
                }
            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Penyimpanan', 'top-right', xhr.responseJSON.message, 'error', 3000);
            }
        });
    });
}
function detail_kondisi_fisik_tabel(user_id,transaksi_id,nama_peserta,nomor_identitas){
    isedit = true;
    let newOption = new Option('['+nomor_identitas+'] - '+nama_peserta, nomor_identitas, true, false);
    $("#pencarian_member_mcu").append(newOption).trigger('change');
    $("#pencarian_member_mcu").val(nomor_identitas).trigger('change');
    fill_detail_kondisi_fisik(user_id,transaksi_id,nama_peserta);
}
function detail_kondisi_fisik(user_id,transaksi_id,nama_peserta){
    isedit = false;
    fill_detail_kondisi_fisik(user_id,transaksi_id,nama_peserta,true);
}