let table;
$(document).ready(function(){
    callGlobalSelect2SearchByMember('pencarian_member_mcu');
    onload_datatables();
    onload_datatables_tanda_vital();
});
function onload_datatables() {
    let tables = [];
    ["#datatables_tanda_vital", "#datatables_tanda_gizi"].forEach(function(tableId) {
        let table = $(tableId).DataTable({
            paging: false,
            ordering: false,
            searching: false,
            info: false,
            keys: true,
        }).on('key-focus', function(e, datatable, cell, originalEvent) {
            $('input', cell.node()).focus();
        }).on("focus", "td input", function() {
            $(this).select();
        });
        tables.push(table);
    });
    tables.forEach(function(table) {
        table.on('key', function(e, dt, code) {
            if (code === 13) {
                table.keys.move('down');
            }
        });
    });
}
function onload_datatables_tanda_vital(){
    $.get('/generate-csrf-token', function(response) {
        $("#datatables_tanda_vital_list").DataTable({
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
                "url": baseurlapi + '/pemeriksaan_fisik/daftar_tanda_vital',
                "type": "GET",
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('token_ajax'));
                },
                "data": function(d) {
                    d._token = response.csrf_token;
                    d.parameter_pencarian = $("#kotak_pencarian_daftar_imunisasi").val();
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
                            Tanggal Transaksi Tanda Vital: ${row.tanggal_transaksi_tanda_vital}<br>
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
                                <button onclick="detail_tanda_vital('${row.user_id}','${row.transaksi_id}','${row.nomor_identitas}','${row.nama_peserta}')" class="btn btn-success w-100">
                                    <i class="fa fa-eye"></i> Lihat Data
                                </button>
                                <button onclick="detail_tanda_vital_tabel('${row.user_id}','${row.transaksi_id}','${row.nomor_identitas}','${row.nama_peserta}')" class="btn btn-primary w-100">
                                    <i class="fa fa-edit"></i> Ubah Data
                                </button>
                                <button onclick="hapus_tanda_vital('${row.transaksi_id}','${row.nomor_identitas}','${row.nama_peserta}','${row.user_id}')" class="btn btn-danger w-100">
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
function clear_tanda_vital(){
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
    $('#datatables_tanda_vital input').each(function() {
        $(this).val('');
    });
    $('#datatables_tanda_gizi input').each(function() {
        $(this).val('');
    });
}
$("#bersihkan_tanda_vital").on('click', function() {
    clear_tanda_vital();
});
$("#simpan_tanda_vital").on('click', function() {
    if ($("#pencarian_member_mcu").val() == null) {
        return createToast('Kesalahan Penyimpanan','top-right','Silahkan tentukan peserta MCU terlebih dahulu sebelum menyimpan data atas formulir bahaya riwayat lingkungan kerja diatas','error',3000);
    }
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Simpan Formulir Tanda Vital</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menyimpan formulir informasi Tanda Vital Peserta MCU dengan atas nama : <strong>' + $("#nama_peserta_temp_1").text() + '</strong> ?</p></div></div>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Simpan Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            let bulkData = [];
            ["#datatables_tanda_vital", "#datatables_tanda_gizi"].forEach(function(tableId) {
                let table = $(tableId).DataTable();
                table.rows().every(function() {
                    let row = this.data();
                    let rowId = row[0];
                    let id_atribut_lv = row[0];
                    let nama_atribut_saat_ini = row[1];
                    let nilai_tanda_vital = $(`#nilai_tanda_vital_${rowId}`).val();
                    let satuan_tanda_vital = row[3];
                    let keterangan_tanda_vital = $(`#keterangan_tanda_vital_${rowId}`).val();
                    let rowData = {
                        user_id: $("#user_id_temp").text(),
                        transaksi_id: $("#id_transaksi_mcu").text(),
                        id_atribut_lv: id_atribut_lv,
                        nama_atribut_saat_ini: nama_atribut_saat_ini,
                        nilai_tanda_vital: nilai_tanda_vital,
                        satuan_tanda_vital: satuan_tanda_vital,
                        keterangan_tanda_vital: keterangan_tanda_vital,
                        jenis_tanda_vital: tableId === "#datatables_tanda_vital" ? "tanda_vital" : "tanda_gizi",
                    };
                    bulkData.push(rowData);
                });
            });
            $.get('/generate-csrf-token', function(response) {
                let data = {
                    _token: response.csrf_token,
                    informasi_tanda_vital: bulkData,
                };
                $.ajax({
                    url: baseurlapi + '/pemeriksaan_fisik/simpantandavital',
                    type: 'POST',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token_ajax') },
                    data: data,
                    success: function(response) {
                        clear_tanda_vital();
                        $("#datatables_tanda_vital_list").DataTable().ajax.reload();
                        return createToast('Berhasil', 'top-right', response.message, 'success', 3000);
                    },
                    error: function(xhr, status, error) {
                        return createToast('Kesalahan Penyimpanan', 'top-right', error, 'error', 3000);
                    },
                });
            });
        }
    });
});
function hapus_tanda_vital(transaksi_id,nomor_identitas,nama_peserta,user_id){
    Swal.fire({
        html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53a48ece-27d3-4b85-9150-8005e7c27aa4/usrEqiqrei.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Konfirmasi Hapus Data Tanda Vital</h4><p class="text-muted mx-4 mb-0">Apakah anda yakin ingin menghapus data Tanda Vital Peserta MCU dengan atas nama : <strong>'+nama_peserta+'</strong> ?. Jika terjadi kesalahan silahakn ubah sesuai kebutuhan',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: 'orange',
        confirmButtonText: 'Hapus Data',
        cancelButtonText: 'Nanti Dulu!!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.get('/generate-csrf-token', function(response){
                $.ajax({
                    url: baseurlapi + '/pemeriksaan_fisik/hapustandavital',
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
                            clear_tanda_vital();
                            $('#datatables_tanda_vital_list').DataTable().ajax.reload();
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
function fill_form_tanda_vital_tabel(user_id,transaksi_id,nama_peserta,detail){
    $.get('/generate-csrf-token', function(response) {
        $.ajax({
            url: baseurlapi + '/pemeriksaan_fisik/get_tandavital',
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
                    $('#datatables_tanda_vital_modal_tanda_vital tbody').empty();
                    $('#datatables_tanda_vital_modal_tanda_gizi tbody').empty();
                    $("#modal_nama_peserta_parameter").text(nama_peserta);
                    for (let i = 0; i < response.data.length; i++) {
                        let no = i + 1;
                        let item = response.data[i];
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
                    $("#modalLihatParameter").modal('show');
                }else{
                    createToast('LKP MCU', 'top-right', response.message, 'success', 3000);
                    response.data.forEach(function(item) {
                        let rowVital = $('#datatables_tanda_vital tbody tr').filter(function() {
                            return $(this).find('td:eq(0)').text() == item.id_atribut_lv;
                        });
                        let rowGizi = $('#datatables_tanda_gizi tbody tr').filter(function() {
                            return $(this).find('td:eq(0)').text() == item.id_atribut_lv;
                        });
                        if (response.data.length > 0 && item.jenis_tanda_vital == 'tanda_vital') {
                            rowVital.find('#nilai_tanda_vital_' + item.id_atribut_lv).val(item.nilai_tanda_vital);
                            rowVital.find('#keterangan_tanda_vital_' + item.id_atribut_lv).val(item.keterangan_tanda_vital ? item.keterangan_tanda_vital : "");
                        }
                        if (response.data.length > 0 && item.jenis_tanda_vital == 'tanda_gizi') {
                            rowGizi.find('#nilai_tanda_vital_' + item.id_atribut_lv).val(item.nilai_tanda_vital);
                            rowGizi.find('#keterangan_tanda_vital_' + item.id_atribut_lv).val(item.keterangan_tanda_vital ? item.keterangan_tanda_vital : "");
                        }
                    }); 
                }
            },
            error: function(xhr, status, error) {
                return createToast('Kesalahan Penyimpanan', 'top-right', xhr.responseJSON.message, 'error', 3000);
            }
        });
    });
}
function detail_tanda_vital_tabel(user_id,transaksi_id,nomor_identitas,nama_peserta){
    isedit = true;
    let newOption = new Option('['+nomor_identitas+'] - '+nama_peserta, nomor_identitas, true, false);
    $("#pencarian_member_mcu").append(newOption).trigger('change');
    $("#pencarian_member_mcu").val(nomor_identitas).trigger('change');
    fill_form_tanda_vital_tabel(user_id,transaksi_id,nama_peserta);
}
function detail_tanda_vital(user_id,transaksi_id,nama_peserta){
    isedit = false;
    fill_form_tanda_vital_tabel(user_id,transaksi_id,nama_peserta,true);
}
